<?php
// api/absensi_detail.php – API to calculate detailed calendar for one employee
declare(strict_types=1);

require_once __DIR__ . '/../bootstrap.php';

use App\Config\Database;
use App\Services\AttendanceSummaryService;

try {
    $pdo = Database::getInstance()->getConnection();
    
    $employeeId = isset($_GET['employee_id']) ? (int)$_GET['employee_id'] : 0;
    $periodId   = isset($_GET['period_id']) ? (int)$_GET['period_id'] : 0;

    if ($employeeId <= 0) {
        jsonResponse(['success' => false, 'message' => 'Parameter employee_id wajib diisi.'], 400);
    }

    $stmt = $pdo->prepare("SELECT * FROM employees WHERE id = :id LIMIT 1");
    $stmt->execute([':id' => $employeeId]);
    $employee = $stmt->fetch();

    if (!$employee) {
        jsonResponse(['success' => false, 'message' => 'Karyawan tidak ditemukan.'], 404);
    }

    if ($periodId > 0) {
        $stmt = $pdo->prepare("SELECT * FROM work_periods WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $periodId]);
        $period = $stmt->fetch();
    } else {
        $period = $pdo->query("SELECT * FROM work_periods WHERE status = 'active' ORDER BY start_date DESC LIMIT 1")->fetch();
    }

    if (!$period) {
        jsonResponse(['success' => false, 'message' => 'Periode kerja tidak ditemukan.'], 404);
    }

    $start = new DateTimeImmutable($period['start_date']);
    $end   = new DateTimeImmutable($period['end_date']);

    $service = new AttendanceSummaryService($pdo);
    $summary = $service->calculateForEmployee($employee, $start, $end);

    // Other security names map
    $securityMap = [];
    $secRes = $pdo->query("SELECT id, name FROM employees WHERE schedule_type = 'SECURITY'")->fetchAll();
    foreach ($secRes as $r) {
        $securityMap[$r['id']] = $r['name'];
    }

    // Other CS names map
    $csMap = [];
    $csRes = $pdo->query("SELECT id, name FROM employees WHERE schedule_type = 'CLEANING_SERVICE'")->fetchAll();
    foreach ($csRes as $r) {
        $csMap[$r['id']] = $r['name'];
    }

    jsonResponse([
        'success'      => true,
        'employee'     => $employee,
        'period'       => $period,
        'summary'      => $summary,
        'security_map' => $securityMap,
        'cs_map'       => $csMap,
    ]);
} catch (\Throwable $e) {
    jsonResponse([
        'success' => false,
        'message' => 'Terjadi kesalahan: ' . $e->getMessage()
    ], 500);
}
