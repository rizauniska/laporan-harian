<?php
// api/absensi_periode.php – API for Work Periods
declare(strict_types=1);

require_once __DIR__ . '/../bootstrap.php';

use App\Config\Database;

try {
    $pdo = Database::getInstance()->getConnection();
    $method = $_SERVER['REQUEST_METHOD'];

    if ($method === 'GET') {
        $stmt = $pdo->query("SELECT * FROM work_periods ORDER BY start_date DESC");
        $periods = $stmt->fetchAll();
        jsonResponse(['success' => true, 'data' => $periods]);
    }

    if ($method === 'POST') {
        $body = json_decode(file_get_contents('php://input'), true);
        if (!$body) {
            jsonResponse(['success' => false, 'message' => 'Format JSON tidak valid.'], 400);
        }

        $action = $body['action'] ?? 'save';

        // Action: Set Active
        if ($action === 'set_active') {
            $id = (int)($body['id'] ?? 0);
            if ($id <= 0) {
                jsonResponse(['success' => false, 'message' => 'ID periode tidak valid.'], 400);
            }
            $pdo->exec("UPDATE work_periods SET status = 'closed'");
            $stmt = $pdo->prepare("UPDATE work_periods SET status = 'active' WHERE id = :id");
            $stmt->execute([':id' => $id]);

            jsonResponse(['success' => true, 'message' => 'Periode berhasil diaktifkan.']);
        }

        $id        = isset($body['id']) ? (int)$body['id'] : 0;
        $name      = trim((string)($body['name'] ?? ''));
        $startDate = trim((string)($body['start_date'] ?? ''));
        $endDate   = trim((string)($body['end_date'] ?? ''));
        $status    = strtolower(trim((string)($body['status'] ?? 'active')));

        if ($name === '' || $startDate === '' || $endDate === '') {
            jsonResponse(['success' => false, 'message' => 'Nama Periode, Tanggal Mulai, dan Tanggal Selesai wajib diisi.'], 422);
        }

        if ($status === 'active') {
            $pdo->exec("UPDATE work_periods SET status = 'closed'");
        }

        if ($id > 0) {
            $stmt = $pdo->prepare("
                UPDATE work_periods 
                SET name = :name, start_date = :sdate, end_date = :edate, status = :status
                WHERE id = :id
            ");
            $stmt->execute([
                ':name'   => $name,
                ':sdate'  => $startDate,
                ':edate'  => $endDate,
                ':status' => $status,
                ':id'     => $id,
            ]);
            $msg = 'Periode kerja berhasil diperbarui.';
        } else {
            $stmt = $pdo->prepare("
                INSERT INTO work_periods (name, start_date, end_date, status)
                VALUES (:name, :sdate, :edate, :status)
            ");
            $stmt->execute([
                ':name'   => $name,
                ':sdate'  => $startDate,
                ':edate'  => $endDate,
                ':status' => $status,
            ]);
            $id = (int) $pdo->lastInsertId();
            $msg = 'Periode kerja baru berhasil disimpan.';
        }

        jsonResponse(['success' => true, 'message' => $msg, 'id' => $id]);
    }

    if ($method === 'DELETE') {
        $body = json_decode(file_get_contents('php://input'), true);
        $id = isset($body['id']) ? (int)$body['id'] : 0;

        if ($id <= 0) {
            jsonResponse(['success' => false, 'message' => 'ID periode tidak valid.'], 400);
        }

        $stmt = $pdo->prepare("DELETE FROM work_periods WHERE id = :id");
        $stmt->execute([':id' => $id]);

        jsonResponse(['success' => true, 'message' => 'Periode berhasil dihapus.']);
    }

    jsonResponse(['success' => false, 'message' => 'Metode HTTP tidak didukung.'], 405);
} catch (\Throwable $e) {
    jsonResponse(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
}
