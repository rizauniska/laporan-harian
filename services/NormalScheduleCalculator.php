<?php
declare(strict_types=1);

namespace App\Services;

use DateTimeImmutable;

class NormalScheduleCalculator
{
    public function __construct(
        private HolidayService $holidayService,
        private PeriodService  $periodService
    ) {}

    public function getDailySchedule(DateTimeImmutable $start, DateTimeImmutable $end): array
    {
        $dates = $this->periodService->getAllDates($start, $end);
        $schedule = [];

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

            $schedule[] = [
                'date'        => $date->format('Y-m-d'),
                'should_work' => true,
                'reason'      => 'Hari Kerja',
                'shift'       => null,
            ];
        }

        return $schedule;
    }
}
