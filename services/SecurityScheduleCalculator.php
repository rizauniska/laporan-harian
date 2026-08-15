<?php
declare(strict_types=1);

namespace App\Services;

use DateTimeImmutable;

class SecurityScheduleCalculator
{
    private ?array $rotationConfig = null;

    public function __construct(
        private PeriodService          $periodService,
        private ScheduleSettingService $settingService
    ) {}

    private function loadConfig(): array
    {
        if ($this->rotationConfig === null) {
            $this->rotationConfig = $this->settingService->getValue('security.rotation', [
                'reference_date' => '2026-07-27',
                'pattern'        => [
                    ['pagi' => 0, 'malam' => 0, 'libur' => 0],
                ],
            ]);
        }
        return $this->rotationConfig;
    }

    /**
     * Get shift assignment for a specific date (Pagi, Malam, Libur).
     * Continuous rotation: never stops for Sundays, holidays, or period boundaries.
     */
    public function getShiftForDate(DateTimeImmutable $date): array
    {
        $config = $this->loadConfig();
        $refDate = new DateTimeImmutable($config['reference_date'] ?? '2026-07-27');
        $pattern = $config['pattern'] ?? [];
        $patternLen = count($pattern);

        if ($patternLen === 0) {
            return ['pagi' => 0, 'malam' => 0, 'libur' => 0];
        }

        // Calculate days difference (supports negative if before reference date)
        $diff = $refDate->diff($date);
        $days = (int) $diff->format('%r%a');

        $idx = (($days % $patternLen) + $patternLen) % $patternLen;
        return $pattern[$idx];
    }

    public function getEmployeeShiftStatus(int $employeeId, DateTimeImmutable $date): string
    {
        $shift = $this->getShiftForDate($date);
        if ((int) ($shift['pagi'] ?? 0) === $employeeId) return 'pagi';
        if ((int) ($shift['malam'] ?? 0) === $employeeId) return 'malam';
        return 'libur';
    }

    public function getDailySchedule(array $employee, DateTimeImmutable $start, DateTimeImmutable $end): array
    {
        $dates = $this->periodService->getAllDates($start, $end);
        $empId = (int) $employee['id'];
        $schedule = [];

        foreach ($dates as $date) {
            $shiftStatus = $this->getEmployeeShiftStatus($empId, $date);
            $shouldWork = in_array($shiftStatus, ['pagi', 'malam'], true);
            $fullShift = $this->getShiftForDate($date);

            $reason = match ($shiftStatus) {
                'pagi'  => 'Shift Pagi',
                'malam' => 'Shift Malam',
                'libur' => 'Libur Rotasi',
            };

            $schedule[] = [
                'date'        => $date->format('Y-m-d'),
                'should_work' => $shouldWork,
                'shift'       => $shiftStatus,
                'reason'      => $reason,
                'full_shift'  => $fullShift,
            ];
        }

        return $schedule;
    }
}
