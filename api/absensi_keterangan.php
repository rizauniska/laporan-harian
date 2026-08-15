<?php
// api/absensi_keterangan.php – API for Attendance Notes (Sakit, Izin, Cuti)
declare(strict_types=1);

require_once __DIR__ . '/../bootstrap.php';

use App\Config\Database;

try {
    $pdo = Database::getInstance()->getConnection();
    $method = $_SERVER['REQUEST_METHOD'];

    if ($method === 'GET') {
        $stmt = $pdo->query("
            SELECT n.*, e.name AS employee_name, e.position AS employee_position, e.schedule_type
            FROM attendance_notes n
            JOIN employees e ON n.employee_id = e.id
            ORDER BY n.start_date DESC, n.id DESC
        ");
        $notes = $stmt->fetchAll();

        // Also fetch active employees list for the select dropdown
        $employees = $pdo->query("SELECT id, name, position, schedule_type FROM employees WHERE active = 1 ORDER BY name ASC")->fetchAll();

        jsonResponse([
            'success'   => true,
            'data'      => $notes,
            'employees' => $employees,
        ]);
    }

    if ($method === 'POST') {
        $body = json_decode(file_get_contents('php://input'), true);
        if (!$body) {
            jsonResponse(['success' => false, 'message' => 'Format JSON tidak valid.'], 400);
        }

        $id         = isset($body['id']) ? (int)$body['id'] : 0;
        $employeeId = isset($body['employee_id']) ? (int)$body['employee_id'] : 0;
        $type       = strtolower(trim((string)($body['type'] ?? 'sakit')));
        $startDate  = trim((string)($body['start_date'] ?? ''));
        $endDate    = trim((string)($body['end_date'] ?? ''));
        $notes      = trim((string)($body['notes'] ?? ''));

        if ($employeeId <= 0 || $startDate === '' || $endDate === '') {
            jsonResponse(['success' => false, 'message' => 'Karyawan, Tanggal Mulai, dan Tanggal Selesai wajib diisi.'], 422);
        }

        if (!in_array($type, ['sakit', 'izin', 'cuti'], true)) {
            jsonResponse(['success' => false, 'message' => 'Jenis keterangan harus sakit, izin, atau cuti.'], 422);
        }

        if ($endDate < $startDate) {
            jsonResponse(['success' => false, 'message' => 'Tanggal selesai tidak boleh lebih awal dari tanggal mulai.'], 422);
        }

        if ($id > 0) {
            $stmt = $pdo->prepare("
                UPDATE attendance_notes
                SET employee_id = :emp_id, type = :type, start_date = :sdate, end_date = :edate, notes = :notes
                WHERE id = :id
            ");
            $stmt->execute([
                ':emp_id' => $employeeId,
                ':type'   => $type,
                ':sdate'  => $startDate,
                ':edate'  => $endDate,
                ':notes'  => $notes,
                ':id'     => $id,
            ]);
            $msg = 'Keterangan absensi berhasil diperbarui.';
        } else {
            $stmt = $pdo->prepare("
                INSERT INTO attendance_notes (employee_id, type, start_date, end_date, notes)
                VALUES (:emp_id, :type, :sdate, :edate, :notes)
            ");
            $stmt->execute([
                ':emp_id' => $employeeId,
                ':type'   => $type,
                ':sdate'  => $startDate,
                ':edate'  => $endDate,
                ':notes'  => $notes,
            ]);
            $id = (int) $pdo->lastInsertId();
            $msg = 'Keterangan absensi berhasil ditambahkan.';
        }

        jsonResponse(['success' => true, 'message' => $msg, 'id' => $id]);
    }

    if ($method === 'DELETE') {
        $body = json_decode(file_get_contents('php://input'), true);
        $id = isset($body['id']) ? (int)$body['id'] : 0;

        if ($id <= 0) {
            jsonResponse(['success' => false, 'message' => 'ID keterangan tidak valid.'], 400);
        }

        $stmt = $pdo->prepare("DELETE FROM attendance_notes WHERE id = :id");
        $stmt->execute([':id' => $id]);

        jsonResponse(['success' => true, 'message' => 'Keterangan absensi berhasil dihapus.']);
    }

    jsonResponse(['success' => false, 'message' => 'Metode HTTP tidak didukung.'], 405);
} catch (\Throwable $e) {
    jsonResponse(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
}
