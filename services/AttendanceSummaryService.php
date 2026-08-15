<?php
declare(strict_types=1);

namespace App\Services;

use App\Config\Database;
use DateTimeImmutable;
use PDO;

class AttendanceSummaryService
{
    private HolidayService                $holidayService;
    private PeriodService                 $periodService;
    private ScheduleSettingService        $settingService;
    private NormalScheduleCalculator      $normalCalc;
    private PharmacistScheduleCalculator  $pharmacistCalc;
    private SecurityScheduleCalculator    $securityCalc;
    private CleaningServiceCalculator     $csCalc;
    private AttendanceDeductionCalculator $deductionCalc;
    private PDO                           $pdo;

    public function __construct(?PDO $pdo = null)
    {
        $this->pdo            = $pdo ?? Database::getInstance()->getConnection();
        $this->holidayService = new HolidayService($this->pdo);
        $this->periodService  = new PeriodService();
        $this->settingService = new ScheduleSettingService($this->pdo);
        $this->normalCalc     = new NormalScheduleCalculator($this->holidayService, $this->periodService);
        $this->pharmacistCalc = new PharmacistScheduleCalculator($this->holidayService, $this->periodService, $this->settingService);
        $this->securityCalc   = new SecurityScheduleCalculator($this->periodService, $this->settingService);
        $this->csCalc         = new CleaningServiceCalculator($this->holidayService, $this->periodService, $this->settingService);
        $this->deductionCalc  = new AttendanceDeductionCalculator($this->pdo);
    }

    public function calculateForEmployee(array $employee, DateTimeImmutable $start, DateTimeImmutable $end): array
    {
        $this->holidayService->loadForPeriod($start, $end);

        $type = strtoupper((string) ($employee['schedule_type'] ?? 'NORMAL'));
        $dailySchedule = match ($type) {
            'NORMAL'           => $this->normalCalc->getDailySchedule($start, $end),
            'APOTEKER'         => $this->pharmacistCalc->getDailySchedule($employee, $start, $end),
            'SECURITY'         => $this->securityCalc->getDailySchedule($employee, $start, $end),
            'CLEANING_SERVICE' => $this->csCalc->getDailySchedule($employee, $start, $end),
            default            => $this->normalCalc->getDailySchedule($start, $end),
        };

        // Working days count
        $workingDays = 0;
        foreach ($dailySchedule as $d) {
            if ($d['should_work']) $workingDays++;
        }

        // Deductions
        $deductions = $this->deductionCalc->calculate((int) $employee['id'], $dailySchedule, $start, $end);
        $noteMap = $deductions['note_map'] ?? [];

        $mergedSchedule = [];
        foreach ($dailySchedule as $day) {
            $k = $day['date'];
            $note = $noteMap[$k] ?? null;
            $counted = ($day['should_work'] && !$note) ? 1 : 0;

            $mergedSchedule[] = array_merge($day, [
                'note'    => $note ? $note['type'] : null,
                'notes'   => $note ? ($note['notes'] ?? '') : null,
                'counted' => $counted,
            ]);
        }

        $totalMasuk = max(0, $workingDays - $deductions['total']);

        return [
            'employee'          => $employee,
            'period_start'      => $start->format('Y-m-d'),
            'period_end'        => $end->format('Y-m-d'),
            'working_days'      => $workingDays,
            'hari_kerja'        => $workingDays,
            'sakit'             => $deductions['sakit'],
            'izin'              => $deductions['izin'],
            'cuti'              => $deductions['cuti'],
            'total_deduction'   => $deductions['total'],
            'total_pengurangan' => $deductions['total'],
            'total_masuk'       => $totalMasuk,
            'daily_schedule'    => $mergedSchedule,
            'deduction_detail'  => $deductions['deduction_detail'],
        ];
    }

    public function calculateAll(DateTimeImmutable $start, DateTimeImmutable $end): array
    {
        $stmt = $this->pdo->query("SELECT * FROM employees WHERE active = 1 ORDER BY position ASC, name ASC");
        $employees = $stmt->fetchAll();

        $summaries = [];
        foreach ($employees as $emp) {
            $summaries[] = $this->calculateForEmployee($emp, $start, $end);
        }

        return $summaries;
    }
}
