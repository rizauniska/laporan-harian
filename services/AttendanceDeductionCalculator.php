<?php
declare(strict_types=1);

namespace App\Services;

use App\Config\Database;
use DateTimeImmutable;
use DateInterval;
use PDO;

class AttendanceDeductionCalculator
{
    private PDO $pdo;

    public function __construct(?PDO $pdo = null)
    {
        $this->pdo = $pdo ?? Database::getInstance()->getConnection();
    }

    public function calculate(
        int $employeeId,
        array $dailySchedule,
        DateTimeImmutable $start,
        DateTimeImmutable $end
    ): array {
        $stmt = $this->pdo->prepare("
            SELECT type, start_date, end_date, notes
            FROM attendance_notes
            WHERE employee_id = :emp_id
              AND start_date <= :end_date
              AND end_date >= :start_date
        ");
        $stmt->execute([
            ':emp_id'     => $employeeId,
            ':start_date' => $start->format('Y-m-d'),
            ':end_date'   => $end->format('Y-m-d')
        ]);
        $notes = $stmt->fetchAll();

        // Build map: 'Y-m-d' => ['type' => 'sakit', 'notes' => '...']
        $noteMap = [];
        $oneDay = new DateInterval('P1D');

        foreach ($notes as $n) {
            $nStart = new DateTimeImmutable($n['start_date']);
            $nEnd   = new DateTimeImmutable($n['end_date']);
            $cur    = $nStart;

            while ($cur <= $nEnd) {
                $k = $cur->format('Y-m-d');
                if (!isset($noteMap[$k])) {
                    $noteMap[$k] = $n;
                }
                $cur = $cur->add($oneDay);
            }
        }

        $sakit = 0;
        $izin = 0;
        $cuti = 0;
        $deductionDetail = [];

        foreach ($dailySchedule as $day) {
            $dateKey = $day['date'];
            if ($day['should_work'] && isset($noteMap[$dateKey])) {
                $type = strtolower($noteMap[$dateKey]['type']);
                match ($type) {
                    'sakit' => $sakit++,
                    'izin'  => $izin++,
                    'cuti'  => $cuti++,
                    default => null,
                };
                $deductionDetail[] = [
                    'date'   => $dateKey,
                    'type'   => $type,
                    'reason' => ucfirst($type),
                    'notes'  => $noteMap[$dateKey]['notes'] ?? '',
                ];
            }
        }

        return [
            'sakit'            => $sakit,
            'izin'             => $izin,
            'cuti'             => $cuti,
            'total'            => $sakit + $izin + $cuti,
            'deduction_detail' => $deductionDetail,
            'note_map'         => $noteMap,
        ];
    }
}
