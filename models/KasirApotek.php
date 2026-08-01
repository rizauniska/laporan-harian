<?php
declare(strict_types=1);

namespace App\Models;

use App\Config\Database;
use PDO;

class KasirApotek
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
        $this->ensureSchema();
    }

    private function ensureSchema(): void
    {
        $cols = [
            'jm_dr_zainuddin'       => 'DECIMAL(15,2) NOT NULL DEFAULT 0',
            'jm_dr_ali_program'     => 'DECIMAL(15,2) NOT NULL DEFAULT 0',
            'jm_dr_ali_non_program' => 'DECIMAL(15,2) NOT NULL DEFAULT 0'
        ];
        foreach ($cols as $col => $type) {
            try {
                $this->db->exec("ALTER TABLE kasir_apotek ADD COLUMN {$col} {$type}");
            } catch (\PDOException $e) {
                // Column exists
            }
        }
    }

    public function findByLaporanId(int $laporanId): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM kasir_apotek WHERE laporan_id = ?');
        $stmt->execute([$laporanId]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function save(int $laporanId, array $data): void
    {
        $existing = $this->findByLaporanId($laporanId);
        
        $kasAwal            = $data['kas_awal'] ?? 0;
        $penjualanResep     = $data['penjualan_resep'] ?? 0;
        $penjualanBebas     = $data['penjualan_bebas'] ?? 0;
        $jmDrZainuddin      = $data['jm_dr_zainuddin'] ?? 0;
        $jmDrAliProgram     = $data['jm_dr_ali_program'] ?? 0;
        $jmDrAliNonProgram  = $data['jm_dr_ali_non_program'] ?? 0;
        $transfer           = $data['transfer'] ?? 0;

        if ($existing) {
            $stmt = $this->db->prepare('UPDATE kasir_apotek SET kas_awal = ?, penjualan_resep = ?, penjualan_bebas = ?, jm_dr_zainuddin = ?, jm_dr_ali_program = ?, jm_dr_ali_non_program = ?, transfer = ? WHERE laporan_id = ?');
            $stmt->execute([$kasAwal, $penjualanResep, $penjualanBebas, $jmDrZainuddin, $jmDrAliProgram, $jmDrAliNonProgram, $transfer, $laporanId]);
        } else {
            $stmt = $this->db->prepare('INSERT INTO kasir_apotek (laporan_id, kas_awal, penjualan_resep, penjualan_bebas, jm_dr_zainuddin, jm_dr_ali_program, jm_dr_ali_non_program, transfer) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
            $stmt->execute([$laporanId, $kasAwal, $penjualanResep, $penjualanBebas, $jmDrZainuddin, $jmDrAliProgram, $jmDrAliNonProgram, $transfer]);
        }
    }

    public function getPengeluaran(int $laporanId): array
    {
        $stmt = $this->db->prepare('SELECT keterangan, nominal FROM pengeluaran_apotek WHERE laporan_id = ? ORDER BY urutan');
        $stmt->execute([$laporanId]);
        return $stmt->fetchAll();
    }

    public function savePengeluaran(int $laporanId, array $items): void
    {
        $stmt = $this->db->prepare('DELETE FROM pengeluaran_apotek WHERE laporan_id = ?');
        $stmt->execute([$laporanId]);

        if (empty($items)) {
            return;
        }

        $stmt = $this->db->prepare('INSERT INTO pengeluaran_apotek (laporan_id, keterangan, nominal, urutan) VALUES (?, ?, ?, ?)');
        foreach ($items as $index => $item) {
            $keterangan = $item['keterangan'] ?? '';
            $nominal = $item['nominal'] ?? 0;
            $stmt->execute([$laporanId, $keterangan, $nominal, $index]);
        }
    }
}
