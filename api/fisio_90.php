<?php
// api/fisio_90.php – API Data Pasien Fisioterapi Rp 90.000 per tanggal
declare(strict_types=1);

require_once __DIR__ . '/../bootstrap.php';

use App\Config\Database;

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    jsonResponse(['status' => 'ok']);
}

try {
    $pdo = Database::getInstance()->getConnection();

    $start = $_GET['start'] ?? '';
    $end   = $_GET['end']   ?? '';

    $where = "kp.fisio_90_pasien > 0";
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
               COALESCE(kp.fisio_90_pasien, 0) AS fisio_90_pasien,
               (COALESCE(kp.fisio_90_pasien, 0) * 90000) AS total_nominal,
               (COALESCE(kp.fisio_90_pasien, 0) * 90000) AS fisio_90_total
        FROM laporan l
        INNER JOIN kasir_pendaftaran kp ON l.id = kp.laporan_id
        WHERE {$where}
        ORDER BY l.tanggal ASC
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $rows = $stmt->fetchAll();

    $totalPasien  = (int) array_sum(array_column($rows, 'fisio_90_pasien'));
    $totalNominal = (float) array_sum(array_column($rows, 'total_nominal'));

    jsonResponse([
        'success'       => true,
        'data'          => $rows,
        'total_pasien'  => $totalPasien,
        'total_nominal' => $totalNominal,
        'total'         => $totalNominal,
        'count'         => count($rows)
    ]);

} catch (\Throwable $e) {
    jsonResponse(['success' => false, 'error' => $e->getMessage()], 500);
}
