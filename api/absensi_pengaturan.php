<?php
// api/absensi_pengaturan.php – API for Schedule Settings (Security Rotation & CS Sequence)
declare(strict_types=1);

require_once __DIR__ . '/../bootstrap.php';

use App\Config\Database;
use App\Services\ScheduleSettingService;

try {
    $pdo = Database::getInstance()->getConnection();
    $settingService = new ScheduleSettingService($pdo);
    $method = $_SERVER['REQUEST_METHOD'];

    if ($method === 'GET') {
        $secConfig = $settingService->getValue('security.rotation', [
            'reference_date' => '2026-07-27',
            'pattern' => []
        ]);

        $csConfig = $settingService->getValue('cleaning_service.sequence', [
            'reference_date' => '2026-07-27',
            'sequence' => []
        ]);

        $securityEmps = $pdo->query("SELECT id, name FROM employees WHERE schedule_type = 'SECURITY' AND active = 1")->fetchAll();
        $csEmps       = $pdo->query("SELECT id, name FROM employees WHERE schedule_type = 'CLEANING_SERVICE' AND active = 1")->fetchAll();

        jsonResponse([
            'success'         => true,
            'security_config' => $secConfig,
            'cs_config'       => $csConfig,
            'security_emps'   => $securityEmps,
            'cs_emps'         => $csEmps,
        ]);
    }

    if ($method === 'POST') {
        $body = json_decode(file_get_contents('php://input'), true);
        if (!$body) {
            jsonResponse(['success' => false, 'message' => 'Format JSON tidak valid.'], 400);
        }

        $type = $body['type'] ?? '';

        if ($type === 'security') {
            $refDate = trim((string)($body['reference_date'] ?? '2026-07-27'));
            $pattern = $body['pattern'] ?? [];

            if ($refDate === '' || empty($pattern)) {
                jsonResponse(['success' => false, 'message' => 'Tanggal referensi dan pola rotasi wajib diisi.'], 422);
            }

            $sanitizedPattern = [];
            foreach ($pattern as $p) {
                $sanitizedPattern[] = [
                    'pagi'  => (int) ($p['pagi'] ?? 0),
                    'malam' => (int) ($p['malam'] ?? 0),
                    'libur' => (int) ($p['libur'] ?? 0),
                ];
            }

            $settingService->setValue('security.rotation', [
                'reference_date' => $refDate,
                'pattern'        => $sanitizedPattern,
            ], 'Konfigurasi rotasi Security');

            jsonResponse(['success' => true, 'message' => 'Konfigurasi Security berhasil disimpan.']);
        }

        if ($type === 'cleaning_service') {
            $refDate  = trim((string)($body['reference_date'] ?? '2026-07-27'));
            $sequence = $body['sequence'] ?? [];

            if ($refDate === '' || empty($sequence)) {
                jsonResponse(['success' => false, 'message' => 'Tanggal referensi dan giliran CS wajib diisi.'], 422);
            }

            $sanitizedSeq = [];
            foreach ($sequence as $s) {
                $sanitizedSeq[] = [
                    'employee_id' => (int) ($s['employee_id'] ?? 0),
                    'days'        => max(1, (int) ($s['days'] ?? 2)),
                ];
            }

            $settingService->setValue('cleaning_service.sequence', [
                'reference_date' => $refDate,
                'sequence'       => $sanitizedSeq,
            ], 'Konfigurasi giliran Cleaning Service');

            jsonResponse(['success' => true, 'message' => 'Konfigurasi Cleaning Service berhasil disimpan.']);
        }

        jsonResponse(['success' => false, 'message' => 'Tipe konfigurasi tidak dikenali.'], 422);
    }

    jsonResponse(['success' => false, 'message' => 'Metode HTTP tidak didukung.'], 405);
} catch (\Throwable $e) {
    jsonResponse(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
}
