<?php
declare(strict_types=1);

namespace App\Services;

use App\Config\Database;
use DateTimeImmutable;
use PDO;

class HolidayService
{
    private array $holidayDates = [];
    private PDO $pdo;

    public function __construct(?PDO $pdo = null)
    {
        $this->pdo = $pdo ?? Database::getInstance()->getConnection();
    }

    public function loadForPeriod(DateTimeImmutable $start, DateTimeImmutable $end): void
    {
        $stmt = $this->pdo->prepare("
            SELECT date, name, description 
            FROM holidays 
            WHERE active = 1 AND date BETWEEN :start AND :end
            ORDER BY date ASC
        ");
        $stmt->execute([
            ':start' => $start->format('Y-m-d'),
            ':end'   => $end->format('Y-m-d')
        ]);
        $rows = $stmt->fetchAll();

        $this->holidayDates = [];
        foreach ($rows as $r) {
            $this->holidayDates[$r['date']] = $r;
        }
    }

    public function isHoliday(DateTimeImmutable $date): bool
    {
        return isset($this->holidayDates[$date->format('Y-m-d')]);
    }

    public function getHoliday(DateTimeImmutable $date): ?array
    {
        return $this->holidayDates[$date->format('Y-m-d')] ?? null;
    }

    public function isSunday(DateTimeImmutable $date): bool
    {
        return (int) $date->format('w') === 0; // 0 = Sunday
    }

    public function isNonWorkingDay(DateTimeImmutable $date): bool
    {
        return $this->isSunday($date) || $this->isHoliday($date);
    }
}
