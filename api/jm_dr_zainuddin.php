<?php
// api/jm_dr_zainuddin.php – API Data JM dr. Zainuddin per tanggal
// JM dr. Zainuddin yang ditampilkan = jm_dr_zainuddin - jm_dr_ali_program
require_once __DIR__ . '/../bootstrap.php';

use App\Config\Database;

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    jsonResponse(['status' => 'ok']);
}

try {
    $pdo = Database::getInstance()->getConnection();

    $start = $_GET['start'] ?? '';
    $end   = $_GET['end']   ?? '';

    // Tampilkan baris di mana hasil bersih (zainuddin - ali_program) > 0
    $where  = "(COALESCE(ka.jm_dr_zainuddin, ka.jm_dokter, 0) - COALESCE(ka.jm_dr_ali_program, 0)) > 0";
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
               COALESCE(ka.jm_dr_zainuddin, ka.jm_dokter, 0)      AS jm_dr_zainuddin_raw,
               COALESCE(ka.jm_dr_ali_program, 0)                    AS jm_dr_ali_program,
               (COALESCE(ka.jm_dr_zainuddin, ka.jm_dokter, 0)
                - COALESCE(ka.jm_dr_ali_program, 0))               AS jm_dr_zainuddin
        FROM laporan l
        INNER JOIN kasir_apotek ka ON l.id = ka.laporan_id
        WHERE {$where}
        ORDER BY l.tanggal ASC
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $rows = $stmt->fetchAll();

    $total = array_sum(array_column($rows, 'jm_dr_zainuddin'));

    jsonResponse([
        'success' => true,
        'data'    => $rows,
        'total'   => $total,
        'count'   => count($rows)
    ]);

} catch (\Throwable $e) {
    jsonResponse(['success' => false, 'error' => $e->getMessage()], 500);
}
