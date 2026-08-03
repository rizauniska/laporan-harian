<?php
// api/laboratorium.php – API Data Laboratorium per tanggal
require_once __DIR__ . '/../bootstrap.php';

use App\Config\Database;

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    jsonResponse(['status' => 'ok']);
}

try {
    $pdo = Database::getInstance()->getConnection();

    $start = $_GET['start'] ?? '';
    $end   = $_GET['end']   ?? '';

    $where = "1=1";
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
        SELECT l.tanggal,
               SUM(li.nominal) AS total_lab,
               COUNT(li.id) AS item_count
        FROM laporan l
        INNER JOIN lab_items li ON l.id = li.laporan_id
        WHERE {$where}
        GROUP BY l.id, l.tanggal
        HAVING total_lab > 0
        ORDER BY l.tanggal ASC
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $rows = $stmt->fetchAll();

    $total = array_sum(array_column($rows, 'total_lab'));
    $totalItems = array_sum(array_column($rows, 'item_count'));

    jsonResponse([
        'success'     => true,
        'data'        => $rows,
        'total'       => $total,
        'total_items' => $totalItems,
        'count'       => count($rows)
    ]);

} catch (\Throwable $e) {
    jsonResponse(['success' => false, 'error' => $e->getMessage()], 500);
}
