<?php
declare(strict_types=1);

namespace App\Services;

use App\Config\Database;
use PDO;

class ScheduleSettingService
{
    private PDO $pdo;

    public function __construct(?PDO $pdo = null)
    {
        $this->pdo = $pdo ?? Database::getInstance()->getConnection();
    }

    public function getValue(string $key, mixed $default = null): mixed
    {
        $stmt = $this->pdo->prepare("SELECT setting_value FROM schedule_settings WHERE setting_key = :key LIMIT 1");
        $stmt->execute([':key' => $key]);
        $val = $stmt->fetchColumn();

        if ($val === false || $val === null) {
            return $default;
        }

        $decoded = json_decode((string) $val, true);
        return $decoded !== null ? $decoded : $default;
    }

    public function setValue(string $key, mixed $value, string $description = ''): void
    {
        $json = json_encode($value, JSON_UNESCAPED_UNICODE);
        $stmt = $this->pdo->prepare("
            INSERT INTO schedule_settings (setting_key, setting_value, description)
            VALUES (:key, :val, :desc)
            ON DUPLICATE KEY UPDATE setting_value = :val2, description = :desc2
        ");
        $stmt->execute([
            ':key'   => $key,
            ':val'   => $json,
            ':desc'  => $description,
            ':val2'  => $json,
            ':desc2' => $description,
        ]);
    }
}
