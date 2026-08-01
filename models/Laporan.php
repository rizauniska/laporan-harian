<?php
declare(strict_types=1);

namespace App\Models;

use App\Config\Database;
use PDO;

class Laporan
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function findByTanggal(string $tanggal): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM laporan WHERE tanggal = ?');
        $stmt->execute([$tanggal]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function findOrCreate(string $tanggal): array
    {
        $existing = $this->findByTanggal($tanggal);
        if ($existing) {
            return $existing;
        }

        $stmt = $this->db->prepare('INSERT INTO laporan (tanggal) VALUES (?)');
        $stmt->execute([$tanggal]);
        $id = (int)$this->db->lastInsertId();

        return ['id' => $id, 'tanggal' => $tanggal];
    }

    public function getAll(): array
    {
        $stmt = $this->db->query('SELECT id, tanggal FROM laporan ORDER BY tanggal DESC');
        return $stmt->fetchAll();
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM laporan WHERE id = ?');
        return $stmt->execute([$id]);
    }
}
