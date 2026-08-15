<?php
// api/absensi_libur.php – API for National Holidays
declare(strict_types=1);

require_once __DIR__ . '/../bootstrap.php';

use App\Config\Database;

try {
    $pdo = Database::getInstance()->getConnection();
    $method = $_SERVER['REQUEST_METHOD'];

    if ($method === 'GET') {
        $stmt = $pdo->query("SELECT * FROM holidays ORDER BY date ASC");
        $holidays = $stmt->fetchAll();
        jsonResponse(['success' => true, 'data' => $holidays]);
    }

    if ($method === 'POST') {
        $body = json_decode(file_get_contents('php://input'), true);
        if (!$body) {
            jsonResponse(['success' => false, 'message' => 'Format JSON tidak valid.'], 400);
        }

        $id          = isset($body['id']) ? (int)$body['id'] : 0;
        $date        = trim((string)($body['date'] ?? ''));
        $name        = trim((string)($body['name'] ?? ''));
        $description = trim((string)($body['description'] ?? ''));
        $active      = isset($body['active']) ? (int)$body['active'] : 1;

        if ($date === '' || $name === '') {
            jsonResponse(['success' => false, 'message' => 'Tanggal dan Nama Libur wajib diisi.'], 422);
        }

        if ($id > 0) {
            $stmt = $pdo->prepare("
                UPDATE holidays
                SET date = :date, name = :name, description = :desc, active = :act
                WHERE id = :id
            ");
            $stmt->execute([
                ':date' => $date,
                ':name' => $name,
                ':desc' => $description,
                ':act'  => $active,
                ':id'   => $id,
            ]);
            $msg = 'Hari libur berhasil diperbarui.';
        } else {
            $stmt = $pdo->prepare("
                INSERT INTO holidays (date, name, description, active)
                VALUES (:date, :name, :desc, :act)
                ON DUPLICATE KEY UPDATE name = :name2, description = :desc2, active = :act2
            ");
            $stmt->execute([
                ':date'  => $date,
                ':name'  => $name,
                ':desc'  => $description,
                ':act'   => $active,
                ':name2' => $name,
                ':desc2' => $description,
                ':act2'  => $active,
            ]);
            $id = (int) $pdo->lastInsertId();
            $msg = 'Hari libur berhasil disimpan.';
        }

        jsonResponse(['success' => true, 'message' => $msg, 'id' => $id]);
    }

    if ($method === 'DELETE') {
        $body = json_decode(file_get_contents('php://input'), true);
        $id = isset($body['id']) ? (int)$body['id'] : 0;

        if ($id <= 0) {
            jsonResponse(['success' => false, 'message' => 'ID hari libur tidak valid.'], 400);
        }

        $stmt = $pdo->prepare("DELETE FROM holidays WHERE id = :id");
        $stmt->execute([':id' => $id]);

        jsonResponse(['success' => true, 'message' => 'Hari libur berhasil dihapus.']);
    }

    jsonResponse(['success' => false, 'message' => 'Metode HTTP tidak didukung.'], 405);
} catch (\Throwable $e) {
    jsonResponse(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
}
