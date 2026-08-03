<?php
// api/fisioterapi.php – API Data Keseluruhan Fisioterapi per tanggal
require_once __DIR__ . '/../bootstrap.php';

use App\Config\Database;

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    jsonResponse(['status' => 'ok']);
}

try {
    $pdo = Database::getInstance()->getConnection();

    $start = $_GET['start'] ?? '';
    $end   = $_GET['end']   ?? '';

    $where = "(kp.fisio_120_pasien > 0 OR kp.fisio_90_pasien > 0)";
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
               COALESCE(kp.fisio_120_pasien, 0) AS fisio_120_pasien,
               (COALESCE(kp.fisio_120_pasien, 0) * 120000) AS fisio_120_total,
               COALESCE(kp.fisio_90_pasien, 0) AS fisio_90_pasien,
               (COALESCE(kp.fisio_90_pasien, 0) * 90000) AS fisio_90_total,
               ((COALESCE(kp.fisio_120_pasien, 0) * 120000) + (COALESCE(kp.fisio_90_pasien, 0) * 90000)) AS total_fisio,
               (COALESCE(kp.fisio_120_pasien, 0) + COALESCE(kp.fisio_90_pasien, 0)) AS total_pasien
        FROM laporan l
        INNER JOIN kasir_pendaftaran kp ON l.id = kp.laporan_id
        WHERE {$where}
        ORDER BY l.tanggal ASC
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $rows = $stmt->fetchAll();

    $totFisio120Pasien = array_sum(array_column($rows, 'fisio_120_pasien'));
    $totFisio120Nom    = array_sum(array_column($rows, 'fisio_120_total'));
    $totFisio90Pasien  = array_sum(array_column($rows, 'fisio_90_pasien'));
    $totFisio90Nom     = array_sum(array_column($rows, 'fisio_90_total'));
    $totalPendapatan   = array_sum(array_column($rows, 'total_fisio'));
    $totalPasien       = array_sum(array_column($rows, 'total_pasien'));

    jsonResponse([
        'success'           => true,
        'data'              => $rows,
        'tot_fisio120_p'    => $totFisio120Pasien,
        'tot_fisio120_n'    => $totFisio120Nom,
        'tot_fisio90_p'     => $totFisio90Pasien,
        'tot_fisio90_n'     => $totFisio90Nom,
        'total_pendapatan'  => $totalPendapatan,
        'total_pasien'      => $totalPasien,
        'count'             => count($rows)
    ]);

} catch (\Throwable $e) {
    jsonResponse(['success' => false, 'error' => $e->getMessage()], 500);
}
