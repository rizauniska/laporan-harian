<?php
// api/absensi_karyawan.php – API for Employee Management
declare(strict_types=1);

require_once __DIR__ . '/../bootstrap.php';

use App\Config\Database;
use App\Services\ScheduleSettingService;

try {
    $pdo = Database::getInstance()->getConnection();
    $settingService = new ScheduleSettingService($pdo);
    $method = $_SERVER['REQUEST_METHOD'];

    if ($method === 'GET') {
        $type = $_GET['schedule_type'] ?? '';
        if ($type !== '') {
            $stmt = $pdo->prepare("SELECT * FROM employees WHERE schedule_type = :type ORDER BY position ASC, name ASC");
            $stmt->execute([':type' => $type]);
        } else {
            $stmt = $pdo->query("SELECT * FROM employees ORDER BY position ASC, name ASC");
        }
        $employees = $stmt->fetchAll();

        // Attach workdays for pharmacists
        foreach ($employees as &$emp) {
            if ($emp['schedule_type'] === 'APOTEKER') {
                $key = 'apoteker.employee_' . $emp['id'] . '.workdays';
                $emp['workdays'] = $settingService->getValue($key, [1,2,3,4,5,6]);
            }
        }

        jsonResponse(['success' => true, 'data' => $employees]);
    }

    if ($method === 'POST') {
        $body = json_decode(file_get_contents('php://input'), true);
        if (!$body) {
            jsonResponse(['success' => false, 'message' => 'Format JSON tidak valid.'], 400);
        }

        $id           = isset($body['id']) ? (int)$body['id'] : 0;
        $name         = trim((string)($body['name'] ?? ''));
        $position     = trim((string)($body['position'] ?? ''));
        $scheduleType = strtoupper(trim((string)($body['schedule_type'] ?? 'NORMAL')));
        $active       = isset($body['active']) ? (int)$body['active'] : 1;
        $startDate    = !empty($body['start_date']) ? $body['start_date'] : null;
        $endDate      = !empty($body['end_date']) ? $body['end_date'] : null;
        $workdays     = isset($body['workdays']) && is_array($body['workdays']) ? array_map('intval', $body['workdays']) : [];

        if ($name === '' || $position === '') {
            jsonResponse(['success' => false, 'message' => 'Nama dan Jabatan wajib diisi.'], 422);
        }

        if (!in_array($scheduleType, ['NORMAL', 'APOTEKER', 'SECURITY', 'CLEANING_SERVICE'], true)) {
            jsonResponse(['success' => false, 'message' => 'Jenis jadwal tidak valid.'], 422);
        }

        if ($id > 0) {
            // Update
            $stmt = $pdo->prepare("
                UPDATE employees 
                SET name = :name, position = :pos, schedule_type = :stype, active = :act, start_date = :sdate, end_date = :edate
                WHERE id = :id
            ");
            $stmt->execute([
                ':name'  => $name,
                ':pos'   => $position,
                ':stype' => $scheduleType,
                ':act'   => $active,
                ':sdate' => $startDate,
                ':edate' => $endDate,
                ':id'    => $id,
            ]);
            $empId = $id;
            $msg = 'Data karyawan berhasil diperbarui.';
        } else {
            // Insert
            $stmt = $pdo->prepare("
                INSERT INTO employees (name, position, schedule_type, active, start_date, end_date)
                VALUES (:name, :pos, :stype, :act, :sdate, :edate)
            ");
            $stmt->execute([
                ':name'  => $name,
                ':pos'   => $position,
                ':stype' => $scheduleType,
                ':act'   => $active,
                ':sdate' => $startDate,
                ':edate' => $endDate,
            ]);
            $empId = (int) $pdo->lastInsertId();
            $msg = 'Karyawan baru berhasil ditambahkan.';
        }

        // Save apoteker workdays
        if ($scheduleType === 'APOTEKER') {
            $key = 'apoteker.employee_' . $empId . '.workdays';
            $settingService->setValue($key, $workdays, 'Hari kerja ' . $name);
        }

        jsonResponse(['success' => true, 'message' => $msg, 'id' => $empId]);
    }

    if ($method === 'DELETE') {
        $body = json_decode(file_get_contents('php://input'), true);
        $id = isset($body['id']) ? (int)$body['id'] : 0;

        if ($id <= 0) {
            jsonResponse(['success' => false, 'message' => 'ID karyawan tidak valid.'], 400);
        }

        $stmt = $pdo->prepare("DELETE FROM employees WHERE id = :id");
        $stmt->execute([':id' => $id]);

        jsonResponse(['success' => true, 'message' => 'Karyawan berhasil dihapus.']);
    }

    jsonResponse(['success' => false, 'message' => 'Metode HTTP tidak didukung.'], 405);
} catch (\Throwable $e) {
    jsonResponse(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
}
