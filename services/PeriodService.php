<?php
declare(strict_types=1);

namespace App\Services;

use DateTimeImmutable;
use DateInterval;

class PeriodService
{
    /**
     * Returns array of DateTimeImmutable for each day in the period inclusive.
     * @return DateTimeImmutable[]
     */
    public function getAllDates(DateTimeImmutable $start, DateTimeImmutable $end): array
    {
        $dates = [];
        $current = $start;
        $oneDay = new DateInterval('P1D');

        while ($current <= $end) {
            $dates[] = $current;
            $current = $current->add($oneDay);
        }

        return $dates;
    }
}
