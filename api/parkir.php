<?php
// api/parkir.php – API data pendapatan parkir per tanggal
require_once __DIR__ . '/../bootstrap.php';

use App\Config\Database;

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    jsonResponse(['status' => 'ok']);
}

try {
    $pdo = Database::getInstance()->getConnection();

    // Optional filter tanggal
    $start = $_GET['start'] ?? '';
    $end   = $_GET['end']   ?? '';

    $where = "kp.parkir > 0";
    $params = [];

    if ($start) {
        $where .= " AND l.tanggal >= :start";
        $params[':start'] = $start;
    }
    if ($end) {
        $where .= " AND l.tanggal <= :end";
        $params[':end'] = $end;
    }

    $sql = "
        SELECT l.tanggal, kp.parkir
        FROM laporan l
        INNER JOIN kasir_pendaftaran kp ON l.id = kp.laporan_id
        WHERE {$where}
        ORDER BY l.tanggal ASC
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $rows = $stmt->fetchAll();

    $total = array_sum(array_column($rows, 'parkir'));

    jsonResponse([
        'success' => true,
        'data'    => $rows,
        'total'   => $total,
        'count'   => count($rows)
    ]);

} catch (\Throwable $e) {
    jsonResponse(['success' => false, 'error' => $e->getMessage()], 500);
}
