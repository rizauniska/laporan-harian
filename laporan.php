<?php
// laporan.php – Form Input & Rekap Laporan Keuangan | AdminLTE 4.1.0
declare(strict_types=1);
$yesterday = date('Y-m-d', strtotime('-1 day'));
?>
<!DOCTYPE html>
<html lang="id" data-bs-theme="light">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Input Laporan Harian | Kasir Laporan Keuangan</title>
  <meta name="description" content="Form input laporan keuangan harian kasir apotek dan pendaftaran.">

  <!-- Bootstrap 5 + Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <!-- Font Awesome 6 -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.1/css/all.min.css">
  <!-- AdminLTE 4 -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0-rc4/dist/css/adminlte.min.css">
  <!-- Tom Select (Bootstrap 5 theme) -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.bootstrap5.min.css">
  <!-- Custom Main CSS -->
  <link rel="stylesheet" href="assets/style.css">
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
  <div class="app-wrapper">

    <!-- TOP NAVBAR -->
    <?php require_once __DIR__ . '/includes/navbar.php'; ?>

    <!-- SIDEBAR -->
    <?php require_once __DIR__ . '/includes/sidebar.php'; ?>

    <!-- ============ MAIN CONTENT ============ -->
    <main class="app-main">

      <!-- Status bar -->
      <div id="statusBar" class="alert alert-info d-none py-2 px-3 mb-0 rounded-0 border-0 border-bottom">&nbsp;</div>

      <!-- Content Header -->
      <div class="app-content-header">
        <div class="container-fluid">
          <div class="row align-items-center">
            <div class="col">
              <h3 class="mb-0 fw-bold">
                <i class="bi bi-file-earmark-medical text-primary me-2"></i>Input / Edit Laporan Harian
              </h3>
            </div>
            <div class="col-auto">
              <span class="badge bg-warning text-dark" id="statusSimpan">Belum disimpan</span>
              <span class="ms-2 text-muted fw-semibold" id="labelTanggal">—</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Content Body -->
      <div class="app-content">
        <div class="container-fluid">

          <!-- Welcome Screen -->
          <div id="welcomeScreen" class="text-center py-5">
            <i class="bi bi-clipboard2-data display-1 text-muted"></i>
            <h4 class="mt-3 text-muted">Pilih tanggal lalu klik <strong>Muat</strong> untuk memulai</h4>
            <p class="text-muted small">Atau pilih laporan dari dropdown riwayat di navbar atas</p>
          </div>

          <!-- Main Content (hidden until data loaded) -->
          <div id="mainContent" class="d-none">

            <!-- ===== CARD TABS ===== -->
            <div class="card card-tabs shadow-sm">
              <div class="card-header p-0 pt-1 border-bottom-0">
                <ul class="nav nav-tabs" id="mainTab" role="tablist">
                  <li class="nav-item">
                    <a class="nav-link active fw-semibold" data-bs-toggle="tab" href="#tabApotek" role="tab">
                      <i class="bi bi-capsule me-1"></i> Kasir Apotek
                    </a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link fw-semibold" data-bs-toggle="tab" href="#tabPendaftaran" role="tab">
                      <i class="bi bi-hospital me-1"></i> Kasir Pendaftaran
                    </a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link fw-semibold" data-bs-toggle="tab" href="#tabRekap" role="tab" id="btnTabRekap">
                      <i class="bi bi-table me-1"></i> Rekap
                    </a>
                  </li>
                </ul>
              </div>
              <div class="card-body">
                <div class="tab-content">

                  <!-- ==================== TAB APOTEK ==================== -->
                  <div class="tab-pane fade show active" id="tabApotek" role="tabpanel">
                    <div class="row g-3">

                      <!-- Kolom Kiri: Pemasukan -->
                      <div class="col-lg-6">
                        <div class="card h-100 shadow-none border">
                          <div class="card-header d-flex align-items-center gap-2 bg-success-subtle">
                            <i class="bi bi-arrow-down-circle-fill text-success"></i>
                            <span class="fw-semibold">Pemasukan Kasir Apotek</span>
                          </div>
                          <div class="card-body">

                            <!-- Kas Awal -->
                            <div class="mb-3">
                              <label class="form-label small fw-semibold" for="a-kasAwal">Kas Awal</label>
                              <div class="input-group input-group-sm">
                                <span class="input-group-text">Rp</span>
                                <input type="text" class="form-control text-end-input" id="a-kasAwal"
                                  placeholder="0" autocomplete="off" oninput="fmtCur(this); markDirty()">
                              </div>
                            </div>

                            <!-- Penjualan -->
                            <div class="row g-2 mb-3">
                              <div class="col-6">
                                <label class="form-label small fw-semibold" for="a-resep">Penjualan Resep</label>
                                <div class="input-group input-group-sm">
                                  <span class="input-group-text">Rp</span>
                                  <input type="text" class="form-control text-end-input" id="a-resep"
                                    placeholder="0" autocomplete="off" oninput="fmtCur(this); markDirty()">
                                </div>
                              </div>
                              <div class="col-6">
                                <label class="form-label small fw-semibold" for="a-bebas">Penjualan Bebas</label>
                                <div class="input-group input-group-sm">
                                  <span class="input-group-text">Rp</span>
                                  <input type="text" class="form-control text-end-input" id="a-bebas"
                                    placeholder="0" autocomplete="off" oninput="fmtCur(this); markDirty()">
                                </div>
                              </div>
                            </div>

                            <!-- Rincian JM Dokter -->
                            <div class="p-2 border rounded bg-light mb-3">
                              <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="small fw-bold text-primary">
                                  <i class="bi bi-person-badge-fill me-1"></i> Rincian JM Dokter
                                  <span class="badge bg-info text-dark ms-1" style="font-size:.65rem">ter-include</span>
                                </span>
                                <span class="badge bg-secondary opacity-75" id="badgeJumatNotice" style="font-size:.65rem">JM dr. Ali hanya di Hari Jum'at</span>
                              </div>

                              <!-- JM dr. Zainuddin (Setiap Hari) -->
                              <div class="mb-2">
                                <label class="form-label small fw-semibold text-muted mb-1" for="a-jmDrZainuddin">JM dr. Zainuddin</label>
                                <div class="input-group input-group-sm">
                                  <span class="input-group-text bg-white">Rp</span>
                                  <input type="text" class="form-control text-end-input fw-semibold" id="a-jmDrZainuddin"
                                    placeholder="0" autocomplete="off" oninput="fmtCur(this); markDirty()">
                                </div>
                                <!-- Live preview JM Bersih (hanya muncul jika ada JM Ali Program) -->
                                <div id="a-jmZainuddinBersihWrapper" class="d-none mt-1 px-2 py-1 rounded d-flex justify-content-between align-items-center" style="background:#e8f4fd; border:1px solid #bee3f8;">
                                  <span class="small text-info-emphasis"><i class="bi bi-arrow-return-right me-1"></i>JM Bersih (setelah dikurangi Ali Program)</span>
                                  <span class="small fw-bold text-info" id="a-jmZainuddinBersih">Rp 0</span>
                                </div>
                              </div>

                              <!-- Khusus Jum'at: JM dr. Ali -->
                              <div id="containerJmDrAli" class="d-none">
                                <div class="alert alert-info py-1 px-2 mb-2 small fw-semibold border-0 text-info-emphasis bg-info-subtle">
                                  <i class="bi bi-calendar-check me-1"></i> Hari Jum'at: Silakan isi JM dr. Ali
                                </div>
                                <div class="row g-2 mb-1">
                                  <div class="col-6">
                                    <label class="form-label small fw-semibold text-muted mb-1" for="a-jmDrAliProgram">JM dr. Ali (Program)</label>
                                    <div class="input-group input-group-sm">
                                      <span class="input-group-text bg-white">Rp</span>
                                      <input type="text" class="form-control text-end-input fw-semibold text-primary" id="a-jmDrAliProgram"
                                        placeholder="0" autocomplete="off" oninput="fmtCur(this); markDirty()">
                                    </div>
                                  </div>
                                  <div class="col-6">
                                    <label class="form-label small fw-semibold text-muted mb-1" for="a-jmDrAliNonProgram">JM dr. Ali (non Program)</label>
                                    <div class="input-group input-group-sm">
                                      <span class="input-group-text bg-white">Rp</span>
                                      <input type="text" class="form-control text-end-input fw-semibold text-primary" id="a-jmDrAliNonProgram"
                                        placeholder="0" autocomplete="off" oninput="fmtCur(this); markDirty()">
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>

                            <!-- Total Penjualan -->
                            <div class="d-flex justify-content-between align-items-center py-2 border-top border-bottom mb-3">
                              <span class="fw-semibold small">Total Penjualan</span>
                              <span class="fw-bold text-success" id="a-totalPenjualan">Rp 0</span>
                            </div>

                            <!-- Sumber Pembayaran -->
                            <p class="text-muted small mb-2 fw-semibold">Sumber Pembayaran</p>
                            <div class="row g-2">
                              <div class="col-6">
                                <label class="form-label small" for="a-transfer">
                                  Transfer <span class="badge bg-warning text-dark ms-1" style="font-size:.65rem">→ kurangi Cash</span>
                                </label>
                                <div class="input-group input-group-sm">
                                  <span class="input-group-text">Rp</span>
                                  <input type="text" class="form-control text-end-input transfer-badge" id="a-transfer"
                                    placeholder="0" autocomplete="off" oninput="fmtCur(this); markDirty()">
                                  <button class="btn btn-outline-primary btn-sm" type="button" id="btnOpenTransferModal"
                                    title="Isi rincian transferan satu per satu">
                                    <i class="bi bi-list-ul"></i> Rinci
                                  </button>
                                </div>
                              </div>
                              <div class="col-6">
                                <label class="form-label small" for="a-cash">
                                  Cash <span class="badge bg-success ms-1" style="font-size:.65rem">otomatis</span>
                                </label>
                                <div class="input-group input-group-sm">
                                  <span class="input-group-text">Rp</span>
                                  <input type="text" class="form-control text-end-input readonly-calc" id="a-cash"
                                    placeholder="0" readonly tabindex="-1">
                                </div>
                              </div>
                            </div>

                          </div>
                        </div>
                      </div>

                      <!-- Kolom Kanan: Pengeluaran -->
                      <div class="col-lg-6">
                        <div class="card h-100 shadow-none border">
                          <div class="card-header d-flex align-items-center gap-2 bg-danger-subtle">
                            <i class="bi bi-arrow-up-circle-fill text-danger"></i>
                            <span class="fw-semibold">Pengeluaran Kasir Apotek</span>
                          </div>
                          <div class="card-body">
                            <!-- Transfer otomatis -->
                            <div class="d-flex justify-content-between align-items-center p-2 rounded mb-3" style="background:#fff3cd;border:1px solid #ffc107">
                              <span class="small fw-semibold text-warning-emphasis">
                                <i class="bi bi-arrow-left-right me-1"></i> Nominal Transfer (otomatis)
                              </span>
                              <span class="fw-bold text-warning-emphasis" id="a-expTransfer">Rp 0</span>
                            </div>
                            <!-- Pengeluaran Random -->
                            <div class="d-flex justify-content-between align-items-center mb-2">
                              <span class="small fw-semibold text-muted">Pengeluaran Lain-lain</span>
                              <span class="badge bg-secondary" id="a-expCount">0 item</span>
                            </div>
                            <div id="a-expList" class="mb-2"></div>
                            <button class="btn btn-outline-secondary btn-sm w-100 mb-3" id="btnAddApotekExp">
                              <i class="bi bi-plus-circle me-1"></i> Tambah Pengeluaran
                            </button>
                            <!-- Total Pengeluaran -->
                            <div class="d-flex justify-content-between align-items-center py-2 border-top">
                              <span class="fw-semibold small">Total Pengeluaran</span>
                              <span class="fw-bold text-danger" id="a-totalExp">Rp 0</span>
                            </div>
                          </div>
                        </div>
                      </div>

                    </div><!-- /row -->

                    <!-- Saldo Apotek -->
                    <div class="saldo-callout p-3 mt-3 d-flex justify-content-between align-items-center rounded">
                      <div>
                        <div class="small opacity-75">Saldo Akhir Kasir Apotek</div>
                        <div class="saldo-formula">= Kas Awal + Cash + JM dr. Ali Non-Program &minus; Pengeluaran</div>
                      </div>
                      <div class="saldo-val" id="a-saldo">Rp 0</div>
                    </div>
                  </div><!-- /tabApotek -->

                  <!-- ==================== TAB PENDAFTARAN ==================== -->
                  <div class="tab-pane fade" id="tabPendaftaran" role="tabpanel">
                    <div class="row g-3">

                      <!-- Kolom Kiri: Pemasukan -->
                      <div class="col-lg-6">
                        <div class="card h-100 shadow-none border">
                          <div class="card-header d-flex align-items-center gap-2 bg-success-subtle">
                            <i class="bi bi-arrow-down-circle-fill text-success"></i>
                            <span class="fw-semibold">Pemasukan Kasir Pendaftaran</span>
                          </div>
                          <div class="card-body">

                            <!-- Kas Awal -->
                            <div class="mb-3">
                              <label class="form-label small fw-semibold" for="p-kasAwal">Kas Awal</label>
                              <div class="input-group input-group-sm">
                                <span class="input-group-text">Rp</span>
                                <input type="text" class="form-control text-end-input" id="p-kasAwal"
                                  placeholder="0" autocomplete="off" oninput="fmtCur(this); markDirty()">
                              </div>
                            </div>

                            <!-- Fisioterapi -->
                            <p class="small fw-semibold text-muted mb-2">Fisioterapi</p>
                            <div class="row g-2 mb-2">
                              <div class="col-6">
                                <label class="form-label small" for="p-fisio120">Rp 120.000 / pasien</label>
                                <input type="number" class="form-control form-control-sm text-end-input" id="p-fisio120"
                                  placeholder="0" min="0" oninput="markDirty()">
                                <div class="text-success small fw-semibold mt-1" id="p-fisio120-total">Rp 0</div>
                              </div>
                              <div class="col-6">
                                <label class="form-label small" for="p-fisio90">Rp 90.000 / pasien</label>
                                <input type="number" class="form-control form-control-sm text-end-input" id="p-fisio90"
                                  placeholder="0" min="0" oninput="markDirty()">
                                <div class="text-success small fw-semibold mt-1" id="p-fisio90-total">Rp 0</div>
                              </div>
                            </div>
                            <!-- Subtotal Fisioterapi -->
                            <div class="d-flex justify-content-between align-items-center py-1 border-bottom mb-3">
                              <span class="small text-muted">Subtotal Fisioterapi</span>
                              <span class="fw-bold text-success" id="p-subtotalFisio">Rp 0</span>
                            </div>

                            <!-- Laboratorium -->
                            <div class="d-flex justify-content-between align-items-center mb-2">
                              <p class="small fw-semibold text-muted mb-0">Laboratorium</p>
                              <span class="badge bg-primary" id="p-labCount">0 item</span>
                            </div>
                            <div id="p-labList" class="mb-2"></div>
                            <button class="btn btn-outline-primary btn-sm w-100 mb-1" id="btnAddLab">
                              <i class="bi bi-plus-circle me-1"></i> Tambah Item Lab
                            </button>
                            <div class="d-flex justify-content-between align-items-center py-1 border-bottom mb-3">
                              <span class="small text-muted">Subtotal Laboratorium</span>
                              <span class="fw-semibold text-success" id="p-totalLab">Rp 0</span>
                            </div>

                            <!-- Administrasi & Lain-lain -->
                            <p class="small fw-semibold text-muted mb-2">Administrasi &amp; Lain-lain</p>
                            <div class="mb-2">
                              <label class="form-label small" for="p-adminGM">Admin Poli Gigi &amp; Mata</label>
                              <div class="input-group input-group-sm">
                                <span class="input-group-text">Rp</span>
                                <input type="text" class="form-control text-end-input" id="p-adminGM"
                                  placeholder="0" autocomplete="off" oninput="fmtCur(this); markDirty()">
                              </div>
                            </div>
                            <div class="mb-2">
                              <label class="form-label small" for="p-adminPB">
                                Admin Pasien Baru
                                <span class="badge bg-info text-dark ms-1" style="font-size:.65rem">Rp 15.000 / pasien</span>
                              </label>
                              <input type="number" class="form-control form-control-sm text-end-input" id="p-adminPB"
                                placeholder="0" min="0" oninput="markDirty()">
                              <div class="text-success small fw-semibold mt-1" id="p-adminPB-total">Rp 0</div>
                            </div>
                            <div class="mb-3">
                              <label class="form-label small" for="p-parkir">Parkir</label>
                              <div class="input-group input-group-sm">
                                <span class="input-group-text">Rp</span>
                                <input type="text" class="form-control text-end-input" id="p-parkir"
                                  placeholder="0" autocomplete="off" oninput="fmtCur(this); markDirty()">
                              </div>
                            </div>

                            <!-- Total Pemasukan -->
                            <div class="d-flex justify-content-between align-items-center py-2 border-top">
                              <span class="fw-semibold small">Total Pemasukan Cash</span>
                              <span class="fw-bold text-success" id="p-totalPemasukan">Rp 0</span>
                            </div>

                          </div>
                        </div>
                      </div>

                      <!-- Kolom Kanan: Pengeluaran -->
                      <div class="col-lg-6">
                        <div class="card h-100 shadow-none border">
                          <div class="card-header d-flex align-items-center gap-2 bg-danger-subtle">
                            <i class="bi bi-arrow-up-circle-fill text-danger"></i>
                            <span class="fw-semibold">Pengeluaran Kasir Pendaftaran</span>
                          </div>
                          <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                              <span class="small fw-semibold text-muted">Pengeluaran Lain-lain</span>
                              <span class="badge bg-secondary" id="p-expCount">0 item</span>
                            </div>
                            <div id="p-expList" class="mb-2"></div>
                            <button class="btn btn-outline-secondary btn-sm w-100 mb-3" id="btnAddPendExp">
                              <i class="bi bi-plus-circle me-1"></i> Tambah Pengeluaran
                            </button>
                            <div class="d-flex justify-content-between align-items-center py-2 border-top">
                              <span class="fw-semibold small">Total Pengeluaran</span>
                              <span class="fw-bold text-danger" id="p-totalExp">Rp 0</span>
                            </div>
                          </div>
                        </div>
                      </div>

                    </div><!-- /row -->

                    <!-- Saldo Pendaftaran -->
                    <div class="saldo-callout p-3 mt-3 d-flex justify-content-between align-items-center rounded">
                      <div>
                        <div class="small opacity-75">Saldo Akhir Kasir Pendaftaran</div>
                        <div class="saldo-formula">= Kas Awal + Pemasukan Cash − Pengeluaran Random</div>
                      </div>
                      <div class="saldo-val" id="p-saldo">Rp 0</div>
                    </div>
                  </div><!-- /tabPendaftaran -->

                  <!-- ==================== TAB REKAP ==================== -->
                  <div class="tab-pane fade" id="tabRekap" role="tabpanel">
                    <div class="row g-3">

                      <!-- Rekap Apotek -->
                      <div class="col-lg-6">
                        <div class="card shadow-none border">
                          <div class="card-header bg-primary text-white py-2">
                            <h6 class="mb-0 fw-bold"><i class="bi bi-capsule me-1"></i> Kasir Apotek</h6>
                          </div>
                          <div class="card-body p-0">
                            <table class="table table-bordered table-sm rekap-table mb-0">
                              <thead>
                                <tr>
                                  <th colspan="2">Detail Laporan</th>
                                </tr>
                              </thead>
                              <tbody id="r-apotek-body">
                                <tr>
                                  <td colspan="2" class="text-center text-muted py-4">Muat laporan terlebih dahulu</td>
                                </tr>
                              </tbody>
                            </table>
                          </div>
                        </div>
                      </div>

                      <!-- Rekap Pendaftaran -->
                      <div class="col-lg-6">
                        <div class="card shadow-none border">
                          <div class="card-header bg-primary text-white py-2">
                            <h6 class="mb-0 fw-bold"><i class="bi bi-hospital me-1"></i> Kasir Pendaftaran</h6>
                          </div>
                          <div class="card-body p-0">
                            <table class="table table-bordered table-sm rekap-table mb-0">
                              <thead>
                                <tr>
                                  <th colspan="2">Detail Laporan</th>
                                </tr>
                              </thead>
                              <tbody id="r-pend-body">
                                <tr>
                                  <td colspan="2" class="text-center text-muted py-4">Muat laporan terlebih dahulu</td>
                                </tr>
                              </tbody>
                            </table>
                          </div>
                        </div>
                      </div>

                    </div>

                    <!-- Ringkasan Gabungan -->
                    <div class="card mt-3 shadow-none border">
                      <div class="card-header bg-light fw-bold text-dark py-2 small">
                        <i class="bi bi-calculator me-1"></i> Total Gabungan Kedua Kasir
                      </div>
                      <div class="card-body p-0">
                        <table class="table table-bordered table-sm text-center mb-0 align-middle">
                          <thead class="table-light">
                            <tr class="small text-muted">
                              <th>Total Kas Awal</th>
                              <th>Total Pemasukan</th>
                              <th>Total Pengeluaran</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr>
                              <td class="fw-bold py-2" id="r-total-kas-awal">Rp 0</td>
                              <td class="fw-bold text-success py-2" id="r-total-pemasukan">Rp 0</td>
                              <td class="fw-bold text-danger py-2" id="r-total-pengeluaran">Rp 0</td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>

                    <!-- Grand Total -->
                    <div class="saldo-callout p-4 mt-3 text-center rounded">
                      <div class="small opacity-75 mb-1">Total Saldo Keseluruhan</div>
                      <div class="saldo-val" id="r-grand-total">Rp 0</div>
                      <div class="small opacity-75 mt-1">Kasir Apotek + Kasir Pendaftaran</div>
                    </div>

                  </div><!-- /tabRekap -->

                </div><!-- /tab-content -->
              </div><!-- /card-body -->
            </div><!-- /card card-tabs -->

          </div><!-- /mainContent -->
        </div><!-- /container-fluid -->
      </div><!-- /app-content -->
    </main>

    <!-- FOOTER -->
    <?php require_once __DIR__ . '/includes/footer.php'; ?>

  </div><!-- /app-wrapper -->

  <!-- ============ PRINT VIEW ============ -->
  <div id="print-view"></div>

  <!-- ============ MODAL HAPUS ============ -->
  <div class="modal fade" id="modalHapus" tabindex="-1">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header bg-danger text-white">
          <h5 class="modal-title"><i class="bi bi-trash3 me-2"></i>Hapus Laporan</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body text-center">
          <p class="mb-1 fw-semibold">Yakin ingin menghapus laporan ini?</p>
          <p class="text-muted small">Semua data akan hilang permanen.</p>
        </div>
        <div class="modal-footer justify-content-center">
          <button class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
          <button class="btn btn-danger btn-sm" id="btnConfirmHapus">
            <i class="bi bi-trash3 me-1"></i> Hapus
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- ============ MODAL RINCIAN TRANSFER ============ -->
  <div class="modal fade" id="modalTransfer" tabindex="-1" aria-labelledby="modalTransferLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
      <div class="modal-content">

        <div class="modal-header bg-primary text-white py-2">
          <h5 class="modal-title fw-bold" id="modalTransferLabel">
            <i class="bi bi-arrow-left-right me-2"></i>Rincian Transfer Apotek
          </h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body p-3">
          <p class="small text-muted mb-3">
            <i class="bi bi-info-circle me-1"></i>Isi nominal transferan satu per satu. Total otomatis dijumlahkan ke field <strong>Transfer</strong>.
          </p>

          <!-- Daftar Item Transfer -->
          <div id="transferItemList" class="mb-3"></div>

          <!-- Tombol Tambah -->
          <button class="btn btn-outline-secondary btn-sm w-100" id="btnAddTransferItem" type="button">
            <i class="bi bi-plus-circle me-1"></i> Tambah Baris Transfer
          </button>

          <!-- Total -->
          <div class="mt-3 p-2 rounded d-flex justify-content-between align-items-center fw-bold"
               style="background:#e8f4fd; border:1px solid #bee3f8;">
            <span class="text-primary"><i class="bi bi-sigma me-1"></i>Total Transfer</span>
            <span class="fs-5 text-primary" id="transferModalTotal">Rp 0</span>
          </div>
        </div>

        <div class="modal-footer py-2">
          <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
            <i class="bi bi-x-lg me-1"></i>Batal
          </button>
          <button type="button" class="btn btn-primary fw-semibold" id="btnTransferDone">
            <i class="bi bi-check2-circle me-1"></i>Selesai — Terapkan ke Form
          </button>
        </div>

      </div>
    </div>
  </div>

  <!-- ============ TOAST ============ -->
  <div class="position-fixed bottom-0 end-0 p-3" style="z-index:1100">
    <div id="appToast" class="toast align-items-center" role="alert">
      <div class="d-flex">
        <div class="toast-body" id="toastMsg"></div>
        <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast"></button>
      </div>
    </div>
  </div>

  <!-- JS Libraries -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0-rc4/dist/js/adminlte.min.js"></script>
  <script src="https://cdn.sheetjs.com/xlsx-0.20.1/package/dist/xlsx.full.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
  <script src="assets/app.js"></script>
</body>

</html>