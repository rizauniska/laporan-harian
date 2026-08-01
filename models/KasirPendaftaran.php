<?php
declare(strict_types=1);

namespace App\Models;

use App\Config\Database;
use PDO;

class KasirPendaftaran
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function findByLaporanId(int $laporanId): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM kasir_pendaftaran WHERE laporan_id = ?');
        $stmt->execute([$laporanId]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function save(int $laporanId, array $data): void
    {
        $existing = $this->findByLaporanId($laporanId);
        
        $kasAwal = $data['kas_awal'] ?? 0;
        $fisio120 = $data['fisio_120_pasien'] ?? 0;
        $fisio90 = $data['fisio_90_pasien'] ?? 0;
        $adminGigiMata = $data['admin_gigi_mata'] ?? 0;
        $adminPasienBaru = $data['admin_pasien_baru_count'] ?? 0;
        $parkir = $data['parkir'] ?? 0;

        if ($existing) {
            $stmt = $this->db->prepare('UPDATE kasir_pendaftaran SET kas_awal = ?, fisio_120_pasien = ?, fisio_90_pasien = ?, admin_gigi_mata = ?, admin_pasien_baru_count = ?, parkir = ? WHERE laporan_id = ?');
            $stmt->execute([$kasAwal, $fisio120, $fisio90, $adminGigiMata, $adminPasienBaru, $parkir, $laporanId]);
        } else {
            $stmt = $this->db->prepare('INSERT INTO kasir_pendaftaran (laporan_id, kas_awal, fisio_120_pasien, fisio_90_pasien, admin_gigi_mata, admin_pasien_baru_count, parkir) VALUES (?, ?, ?, ?, ?, ?, ?)');
            $stmt->execute([$laporanId, $kasAwal, $fisio120, $fisio90, $adminGigiMata, $adminPasienBaru, $parkir]);
        }
    }

    public function getLabItems(int $laporanId): array
    {
        $stmt = $this->db->prepare('SELECT nominal FROM lab_items WHERE laporan_id = ? ORDER BY urutan');
        $stmt->execute([$laporanId]);
        return $stmt->fetchAll();
    }

    public function saveLabItems(int $laporanId, array $items): void
    {
        $stmt = $this->db->prepare('DELETE FROM lab_items WHERE laporan_id = ?');
        $stmt->execute([$laporanId]);

        if (empty($items)) {
            return;
        }

        $stmt = $this->db->prepare('INSERT INTO lab_items (laporan_id, nominal, urutan) VALUES (?, ?, ?)');
        foreach ($items as $index => $item) {
            $nominal = $item['nominal'] ?? 0;
            $stmt->execute([$laporanId, $nominal, $index]);
        }
    }

    public function getPengeluaran(int $laporanId): array
    {
        $stmt = $this->db->prepare('SELECT keterangan, nominal FROM pengeluaran_pend WHERE laporan_id = ? ORDER BY urutan');
        $stmt->execute([$laporanId]);
        return $stmt->fetchAll();
    }

    public function savePengeluaran(int $laporanId, array $items): void
    {
        $stmt = $this->db->prepare('DELETE FROM pengeluaran_pend WHERE laporan_id = ?');
        $stmt->execute([$laporanId]);

        if (empty($items)) {
            return;
        }

        $stmt = $this->db->prepare('INSERT INTO pengeluaran_pend (laporan_id, keterangan, nominal, urutan) VALUES (?, ?, ?, ?)');
        foreach ($items as $index => $item) {
            $keterangan = $item['keterangan'] ?? '';
            $nominal = $item['nominal'] ?? 0;
            $stmt->execute([$laporanId, $keterangan, $nominal, $index]);
        }
    }
}
