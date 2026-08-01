<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Models\Laporan;
use App\Models\KasirApotek;
use App\Models\KasirPendaftaran;

class LaporanController
{
    private Laporan $laporan;
    private KasirApotek $kasirApotek;
    private KasirPendaftaran $kasirPendaftaran;

    public function __construct()
    {
        $this->laporan = new Laporan();
        $this->kasirApotek = new KasirApotek();
        $this->kasirPendaftaran = new KasirPendaftaran();
    }

    public function load(string $tanggal): array
    {
        $laporan = $this->laporan->findByTanggal($tanggal);
        if (!$laporan) {
            return [
                'found' => false,
                'tanggal' => $tanggal,
                'apotek' => [
                    'kas_awal' => 1000000,
                    'penjualan_resep' => 0,
                    'penjualan_bebas' => 0,
                    'jm_dr_zainuddin' => 0,
                    'jm_dr_ali_program' => 0,
                    'jm_dr_ali_non_program' => 0,
                    'transfer' => 0
                ],
                'pengeluaran_apotek' => [],
                'pendaftaran' => [
                    'kas_awal' => 600000,
                    'fisio_120_pasien' => 0,
                    'fisio_90_pasien' => 0,
                    'admin_gigi_mata' => 0,
                    'admin_pasien_baru_count' => 0,
                    'parkir' => 0
                ],
                'lab_items' => [],
                'pengeluaran_pend' => []
            ];
        }

        $laporanId = (int)$laporan['id'];
        
        $apotek = $this->kasirApotek->findByLaporanId($laporanId);
        $pengeluaranApotek = $this->kasirApotek->getPengeluaran($laporanId);
        
        $pendaftaran = $this->kasirPendaftaran->findByLaporanId($laporanId);
        $labItems = $this->kasirPendaftaran->getLabItems($laporanId);
        $pengeluaranPend = $this->kasirPendaftaran->getPengeluaran($laporanId);

        return [
            'found' => true,
            'id' => $laporanId,
            'tanggal' => $laporan['tanggal'],
            'apotek' => [
                'kas_awal' => $apotek ? (float)$apotek['kas_awal'] : 1000000,
                'penjualan_resep' => $apotek ? (float)$apotek['penjualan_resep'] : 0,
                'penjualan_bebas' => $apotek ? (float)$apotek['penjualan_bebas'] : 0,
                'jm_dr_zainuddin' => $apotek ? (float)($apotek['jm_dr_zainuddin'] ?? $apotek['jm_dokter'] ?? 0) : 0,
                'jm_dr_ali_program' => $apotek ? (float)($apotek['jm_dr_ali_program'] ?? 0) : 0,
                'jm_dr_ali_non_program' => $apotek ? (float)($apotek['jm_dr_ali_non_program'] ?? 0) : 0,
                'transfer' => $apotek ? (float)$apotek['transfer'] : 0
            ],
            'pengeluaran_apotek' => array_map(function($item) {
                return ['keterangan' => $item['keterangan'], 'nominal' => (float)$item['nominal']];
            }, $pengeluaranApotek),
            'pendaftaran' => [
                'kas_awal' => $pendaftaran ? (float)$pendaftaran['kas_awal'] : 600000,
                'fisio_120_pasien' => $pendaftaran ? (int)$pendaftaran['fisio_120_pasien'] : 0,
                'fisio_90_pasien' => $pendaftaran ? (int)$pendaftaran['fisio_90_pasien'] : 0,
                'admin_gigi_mata' => $pendaftaran ? (float)$pendaftaran['admin_gigi_mata'] : 0,
                'admin_pasien_baru_count' => $pendaftaran ? (int)$pendaftaran['admin_pasien_baru_count'] : 0,
                'parkir' => $pendaftaran ? (float)$pendaftaran['parkir'] : 0
            ],
            'lab_items' => array_map(function($item) {
                return ['nominal' => (float)$item['nominal']];
            }, $labItems),
            'pengeluaran_pend' => array_map(function($item) {
                return ['keterangan' => $item['keterangan'], 'nominal' => (float)$item['nominal']];
            }, $pengeluaranPend)
        ];
    }

    public function save(array $data): array
    {
        $tanggal = $data['tanggal'] ?? '';
        if (!$tanggal) {
            return ['success' => false, 'message' => 'Tanggal is required'];
        }

        $laporan = $this->laporan->findOrCreate($tanggal);
        $laporanId = $laporan['id'];

        if (isset($data['apotek'])) {
            $this->kasirApotek->save($laporanId, $data['apotek']);
        }
        if (isset($data['pengeluaran_apotek'])) {
            $this->kasirApotek->savePengeluaran($laporanId, $data['pengeluaran_apotek']);
        }

        if (isset($data['pendaftaran'])) {
            $this->kasirPendaftaran->save($laporanId, $data['pendaftaran']);
        }
        if (isset($data['lab_items'])) {
            $this->kasirPendaftaran->saveLabItems($laporanId, $data['lab_items']);
        }
        if (isset($data['pengeluaran_pend'])) {
            $this->kasirPendaftaran->savePengeluaran($laporanId, $data['pengeluaran_pend']);
        }

        return ['success' => true, 'id' => $laporanId, 'message' => 'Data berhasil disimpan'];
    }

    public function getList(): array
    {
        return $this->laporan->getAll();
    }

    public function getDashboardList(): array
    {
        $all = $this->laporan->getAll();
        $result = [];

        foreach ($all as $item) {
            $laporanId = (int)$item['id'];
            $tanggal   = $item['tanggal'];

            $apotek            = $this->kasirApotek->findByLaporanId($laporanId);
            $pengeluaranApotek = $this->kasirApotek->getPengeluaran($laporanId);

            $pendaftaran     = $this->kasirPendaftaran->findByLaporanId($laporanId);
            $labItems        = $this->kasirPendaftaran->getLabItems($laporanId);
            $pengeluaranPend = $this->kasirPendaftaran->getPengeluaran($laporanId);

            // Apotek
            $aKasAwal           = $apotek ? (float)$apotek['kas_awal'] : 1000000;
            $aResep             = $apotek ? (float)$apotek['penjualan_resep'] : 0;
            $aBebas             = $apotek ? (float)$apotek['penjualan_bebas'] : 0;
            $aJmDrZainuddin     = $apotek ? (float)($apotek['jm_dr_zainuddin'] ?? 0) : 0;
            $aJmDrAliProgram    = $apotek ? (float)($apotek['jm_dr_ali_program'] ?? 0) : 0;
            $aJmDrAliNonProgram = $apotek ? (float)($apotek['jm_dr_ali_non_program'] ?? 0) : 0;
            $aTransfer          = $apotek ? (float)$apotek['transfer'] : 0;
            $aTotalPenj         = $aResep + $aBebas;
            $aCash              = max(0, $aTotalPenj - $aTransfer);

            $aExpRandom = 0;
            foreach ($pengeluaranApotek as $pa) {
                $aExpRandom += (float)$pa['nominal'];
            }
            $aTotalExp = $aTransfer + $aExpRandom;
            $aSaldo    = $aKasAwal + $aCash - $aExpRandom;

            // Pendaftaran
            $pKasAwal       = $pendaftaran ? (float)$pendaftaran['kas_awal'] : 600000;
            $pFisio120Count = $pendaftaran ? (int)$pendaftaran['fisio_120_pasien'] : 0;
            $pFisio90Count  = $pendaftaran ? (int)$pendaftaran['fisio_90_pasien'] : 0;
            $pAdminPBCount  = $pendaftaran ? (int)$pendaftaran['admin_pasien_baru_count'] : 0;
            $pAdminGM       = $pendaftaran ? (float)$pendaftaran['admin_gigi_mata'] : 0;
            $pParkir        = $pendaftaran ? (float)$pendaftaran['parkir'] : 0;

            $pFisio120 = $pFisio120Count * 120000;
            $pFisio90  = $pFisio90Count * 90000;
            $pAdminPB  = $pAdminPBCount * 15000;

            $pTotalLab = 0;
            foreach ($labItems as $lab) {
                $pTotalLab += (float)$lab['nominal'];
            }

            $pExpRandom = 0;
            foreach ($pengeluaranPend as $pp) {
                $pExpRandom += (float)$pp['nominal'];
            }

            $pTotalPemasukan = $pFisio120 + $pFisio90 + $pTotalLab + $pAdminGM + $pAdminPB + $pParkir;
            $pSaldo          = $pKasAwal + $pTotalPemasukan - $pExpRandom;

            // Summary Gabungan
            $totKasAwal   = $aKasAwal + $pKasAwal;
            $totPemasukan = $aTotalPenj + $pTotalPemasukan;
            $totExp       = $aTotalExp + $pExpRandom;
            $grandSaldo   = $aSaldo + $pSaldo;

            $result[] = [
                'id'                    => $laporanId,
                'tanggal'               => $tanggal,
                'a_kas_awal'            => $aKasAwal,
                'a_pemasukan'           => $aTotalPenj,
                'a_pengeluaran'         => $aTotalExp,
                'a_saldo'               => $aSaldo,
                'jm_dr_zainuddin'       => $aJmDrZainuddin,
                'jm_dr_ali_program'     => $aJmDrAliProgram,
                'jm_dr_ali_non_program' => $aJmDrAliNonProgram,
                'p_kas_awal'            => $pKasAwal,
                'p_pemasukan'           => $pTotalPemasukan,
                'p_pengeluaran'         => $pExpRandom,
                'p_saldo'               => $pSaldo,
                'p_fisio_90'            => $pFisio90,
                'p_fisio_120'           => $pFisio120,
                'p_fisio_90_count'      => $pFisio90Count,
                'p_total_lab'           => $pTotalLab,
                'p_parkir'              => $pParkir,
                'tot_kas_awal'          => $totKasAwal,
                'tot_pemasukan'         => $totPemasukan,
                'tot_pengeluaran'       => $totExp,
                'grand_saldo'           => $grandSaldo
            ];
        }

        return $result;
    }

    public function delete(int $id): array
    {
        $success = $this->laporan->delete($id);
        return ['success' => $success];
    }
}
