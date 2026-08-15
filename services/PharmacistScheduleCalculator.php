<?php
declare(strict_types=1);

namespace App\Services;

use DateTimeImmutable;

class PharmacistScheduleCalculator
{
    public function __construct(
        private HolidayService         $holidayService,
        private PeriodService          $periodService,
        private ScheduleSettingService $settingService
    ) {}

    public function getWorkDayNumbers(int $employeeId): array
    {
        $key = 'apoteker.employee_' . $employeeId . '.workdays';
        $setting = $this->settingService->getValue($key);
        return is_array($setting) ? array_map('intval', $setting) : [1, 2, 3, 4, 5, 6];
    }

    public function getDailySchedule(array $employee, DateTimeImmutable $start, DateTimeImmutable $end): array
    {
        $workDays = $this->getWorkDayNumbers((int) $employee['id']);
        $dates = $this->periodService->getAllDates($start, $end);
        $schedule = [];

        $dayNames = ['', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];

        foreach ($dates as $date) {
            if ($this->holidayService->isSunday($date)) {
                $schedule[] = [
                    'date'        => $date->format('Y-m-d'),
                    'should_work' => false,
                    'reason'      => 'Hari Minggu',
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
                    'shift'       => null,
                ];
                continue;
            }

            // ISO weekday: 1 = Mon, ..., 7 = Sun
            $isoDow = (int) $date->format('N');
            if (!in_array($isoDow, $workDays, true)) {
                $schedule[] = [
                    'date'        => $date->format('Y-m-d'),
                    'should_work' => false,
                    'reason'      => 'Bukan hari kerja apoteker (' . ($dayNames[$isoDow] ?? '') . ')',
                    'shift'       => null,
                ];
                continue;
            }

            $schedule[] = [
                'date'        => $date->format('Y-m-d'),
                'should_work' => true,
                'reason'      => 'Hari Kerja Apoteker',
                'shift'       => null,
            ];
        }

        return $schedule;
    }
}
