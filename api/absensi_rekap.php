<?php
// api/absensi_rekap.php – API to calculate and fetch attendance summary
declare(strict_types=1);

require_once __DIR__ . '/../bootstrap.php';

use App\Config\Database;
use App\Services\AttendanceSummaryService;

try {
    $pdo = Database::getInstance()->getConnection();
    
    // Get period ID or use active
    $periodId = isset($_GET['period_id']) ? (int)$_GET['period_id'] : 0;
    
    if ($periodId > 0) {
        $stmt = $pdo->prepare("SELECT * FROM work_periods WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $periodId]);
        $period = $stmt->fetch();
    } else {
        $period = $pdo->query("SELECT * FROM work_periods WHERE status = 'active' ORDER BY start_date DESC LIMIT 1")->fetch();
        if (!$period) {
            $period = $pdo->query("SELECT * FROM work_periods ORDER BY start_date DESC LIMIT 1")->fetch();
        }
    }

    if (!$period) {
        jsonResponse([
            'success' => false,
            'message' => 'Belum ada periode kerja yang ditentukan.'
        ], 404);
    }

    $start = new DateTimeImmutable($period['start_date']);
    $end   = new DateTimeImmutable($period['end_date']);

    $service = new AttendanceSummaryService($pdo);
    $summaries = $service->calculateAll($start, $end);

    // Also get all periods for dropdown selector
    $allPeriods = $pdo->query("SELECT * FROM work_periods ORDER BY start_date DESC")->fetchAll();

    jsonResponse([
        'success'     => true,
        'period'      => $period,
        'periods'     => $allPeriods,
        'summaries'   => $summaries,
    ]);
} catch (\Throwable $e) {
    jsonResponse([
        'success' => false,
        'message' => 'Terjadi kesalahan: ' . $e->getMessage()
    ], 500);
}
