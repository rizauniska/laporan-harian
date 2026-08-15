<?php
declare(strict_types=1);

namespace App\Services;

use DateTimeImmutable;
use DateInterval;

class CleaningServiceCalculator
{
    private ?array $config = null;

    public function __construct(
        private HolidayService         $holidayService,
        private PeriodService          $periodService,
        private ScheduleSettingService $settingService
    ) {}

    private function loadConfig(): array
    {
        if ($this->config === null) {
            $this->config = $this->settingService->getValue('cleaning_service.sequence', [
                'reference_date' => '2026-07-27',
                'sequence'       => [
                    ['employee_id' => 0, 'days' => 2],
                    ['employee_id' => 0, 'days' => 2],
                ],
            ]);
        }
        return $this->config;
    }

    /**
     * WORKING-DAY SEQUENCE.
     * Sundays & holidays are not working days and do NOT advance the sequence counter.
     */
    public function getAssignedEmployeeId(DateTimeImmutable $date): int
    {
        if ($this->holidayService->isNonWorkingDay($date)) {
            return 0;
        }

        $config = $this->loadConfig();
        $refDate = new DateTimeImmutable($config['reference_date'] ?? '2026-07-27');
        $sequence = $config['sequence'] ?? [];

        $cycleLength = array_sum(array_column($sequence, 'days'));
        if ($cycleLength === 0) return 0;

        $workingDaysSinceRef = $this->countWorkingDaysBetween($refDate, $date);
        $posInCycle = ($workingDaysSinceRef - 1) % $cycleLength;
        if ($posInCycle < 0) $posInCycle = 0;

        $cumulative = 0;
        foreach ($sequence as $slot) {
            $cumulative += $slot['days'];
            if ($posInCycle < $cumulative) {
                return (int) $slot['employee_id'];
            }
        }

        return (int) ($sequence[0]['employee_id'] ?? 0);
    }

    private function countWorkingDaysBetween(DateTimeImmutable $from, DateTimeImmutable $to): int
    {
        $count = 0;
        $current = $from;
        $oneDay = new DateInterval('P1D');

        while ($current <= $to) {
            if (!$this->holidayService->isNonWorkingDay($current)) {
                $count++;
            }
            $current = $current->add($oneDay);
        }

        return $count;
    }

    public function getDailySchedule(array $employee, DateTimeImmutable $start, DateTimeImmutable $end): array
    {
        $dates = $this->periodService->getAllDates($start, $end);
        $empId = (int) $employee['id'];
        $schedule = [];

        foreach ($dates as $date) {
            if ($this->holidayService->isSunday($date)) {
                $schedule[] = [
                    'date'        => $date->format('Y-m-d'),
                    'should_work' => false,
                    'reason'      => 'Hari Minggu',
                    'assigned_to' => 0,
                    'is_my_turn'  => false,
                    'shift'       => null,
                ];
                continue;
            }

            if ($this->holidayService->isHoliday($date)) {
                $hol = $this->holidayService->getHoliday($date);
                $schedule[] = [
                    'date'        => $date->format('Y-m-d'),
                    'should_work' => false,
                    'reason'      => 'Libur Nasional (' . ($hol['name'] ?? 'Libur') . ')',
                    'assigned_to' => 0,
                    'is_my_turn'  => false,
                    'shift'       => null,
                ];
                continue;
            }

            $assignedId = $this->getAssignedEmployeeId($date);
            $isMyTurn = ($assignedId === $empId);

            $schedule[] = [
                'date'        => $date->format('Y-m-d'),
                'should_work' => $isMyTurn,
                'reason'      => $isMyTurn ? 'Giliran Kerja' : 'Giliran Karyawan Lain',
                'assigned_to' => $assignedId,
                'is_my_turn'  => $isMyTurn,
                'shift'       => null,
            ];
        }

        return $schedule;
    }
}
