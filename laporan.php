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

  <style>
    /* ---- Sidebar brand ---- */
    .brand-text {
      font-size: 1rem;
      font-weight: 700;
    }

    /* ---- Input formatting ---- */
    .text-end-input {
      text-align: right;
    }

    .readonly-calc {
      background: #e9f7ef;
      border-color: #86e0ab;
      color: #155724;
      font-weight: 600;
    }

    .transfer-badge {
      background: #fff3cd;
      border-color: #ffc107;
      color: #856404;
      font-weight: 600;
    }

    /* ---- Lab & Expense items ---- */
    .lab-num {
      min-width: 68px;
      text-align: center;
      background: #e7f3ff;
      color: #0a58ca;
      border-color: #9ec5fe;
      font-weight: 700;
      font-size: .8rem;
    }

    .exp-item,
    .lab-item {
      animation: fadeIn 180ms ease;
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(-4px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    /* ---- Saldo callout ---- */
    .saldo-callout {
      background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
      color: #fff;
      border-radius: 8px;
    }

    .saldo-callout .saldo-val {
      font-size: 1.5rem;
      font-weight: 800;
    }

    .saldo-callout .saldo-formula {
      font-size: .78rem;
      opacity: .85;
    }

    /* ---- Rekap table ---- */
    .rekap-table th {
      background: #0d6efd;
      color: #fff;
      white-space: nowrap;
      font-size: .82rem;
    }

    .rekap-table td {
      font-size: .85rem;
      vertical-align: middle;
    }

    .rekap-table .tr-section td {
      background: #e9ecef;
      font-weight: 700;
      font-size: .78rem;
      text-transform: uppercase;
      letter-spacing: .04em;
    }

    .rekap-table .tr-total td {
      font-weight: 700;
      border-top: 2px solid #adb5bd;
      background: #f8f9fa;
    }

    .rekap-table .tr-saldo td {
      font-weight: 800;
      background: #d1e7dd;
      color: #0a3622;
      font-size: .95rem;
      border-top: 2px solid #0f5132;
    }

    /* ---- Tom Select in navbar ---- */
    .ts-wrapper {
      min-height: 31px;
    }

    .ts-control {
      font-size: .875rem;
      min-height: 31px !important;
      padding: 3px 8px !important;
    }

    .ts-dropdown {
      font-size: .85rem;
      max-height: 320px;
    }

    /* ---- Status bar ---- */
    #statusBar {
      border-radius: 0;
      margin-bottom: 0;
    }

    /* ===================================================
       PRINT STYLES
       =================================================== */
    #print-view {
      display: none;
    }

    @media print {
      @page {
        margin: 15mm 15mm 15mm 15mm;
        size: A4 portrait;
      }

      .app-wrapper,
      .app-header,
      .app-sidebar,
      .app-footer,
      .toast {
        display: none !important;
      }

      #print-view {
        display: block !important;
        padding: 0;
        margin: 0;
      }

      body {
        background: #fff;
        color: #111;
        font-family: Arial, sans-serif;
        font-size: 9pt;
        margin: 0;
        padding: 0;
      }

      .pv-header {
        text-align: center;
        border-bottom: 2px solid #333;
        padding-bottom: 8px;
        margin-bottom: 12px;
      }

      .pv-header h1 {
        font-size: 13pt;
        font-weight: 800;
        text-transform: uppercase;
        margin-bottom: 3px;
      }

      .pv-meta {
        font-size: 8pt;
        color: #555;
      }

      .pv-columns {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
        margin-bottom: 12px;
      }

      .pv-section-title {
        background: #1a1a2e;
        color: #fff;
        font-weight: 700;
        font-size: 9pt;
        padding: 4px 7px;
        text-transform: uppercase;
      }

      .pv-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 8pt;
      }

      .pv-table td {
        padding: 2px 6px;
        border: 1px solid #ccc;
      }

      .pv-table td:last-child {
        text-align: right;
        width: 110px;
        white-space: nowrap;
      }

      .pv-table .tr-section td {
        background: #f0f0f0;
        font-weight: 700;
        font-size: 7.5pt;
        text-transform: uppercase;
      }

      .pv-table .tr-sub td:first-child {
        padding-left: 16px;
        color: #555;
      }

      .pv-table .tr-total td {
        font-weight: 700;
        border-top: 1.5px solid #888;
        background: #fafafa;
      }

      .pv-table .tr-saldo td {
        font-weight: 800;
        font-size: 9.5pt;
        background: #e6f9f5;
        border: 1.5px solid #0d9488;
        color: #065f46;
      }

      .txt-minus {
        color: #dc2626;
      }

      .pv-grand {
        margin-top: 10px;
      }

      .pv-grand table {
        width: 100%;
        border-collapse: collapse;
        font-size: 9pt;
      }

      .pv-grand td {
        padding: 4px 9px;
        border: 1.5px solid #0d9488;
      }

      .pv-grand td:last-child {
        text-align: right;
        white-space: nowrap;
      }

      .pv-grand .tr-grand-title td {
        background: #0d9488;
        color: #fff;
        font-weight: 800;
        font-size: 9pt;
        text-transform: uppercase;
      }

      .pv-grand .tr-grand-row td {
        background: #f0fdf9;
        font-weight: 600;
      }

      .pv-grand .tr-grand-total td {
        background: #ccfbf1;
        font-weight: 800;
        font-size: 12pt;
        color: #064e3b;
        border-top: 2px solid #0d9488;
      }

      .pv-footer {
        margin-top: 12px;
        padding-top: 6px;
        border-top: 1px solid #ccc;
        font-size: 7pt;
        color: #888;
        text-align: center;
      }
    }
  </style>
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
  <div class="app-wrapper">

    <!-- ============ TOP NAVBAR ============ -->
    <nav class="app-header navbar navbar-expand bg-white shadow-sm border-bottom">
      <div class="container-fluid">
        <!-- Sidebar Toggle -->
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
              <i class="bi bi-list fs-5"></i>
            </a>
          </li>
        </ul>
        <!-- Brand -->
        <a href="index.php" class="navbar-brand ms-2 d-none d-md-flex align-items-center gap-2">
          <i class="bi bi-clipboard2-data text-primary fs-5"></i>
          <span class="brand-text text-dark">Kasir Laporan Keuangan</span>
        </a>
        <!-- Toolbar: Riwayat dropdown + tanggal + aksi -->
        <ul class="navbar-nav ms-auto align-items-center gap-1">
          <!-- Tom Select Riwayat -->
          <li class="nav-item" style="min-width:200px">
            <select id="selectLaporan" class="form-select form-select-sm" title="Pilih laporan yang sudah ada">
              <option value="">— Riwayat Laporan —</option>
            </select>
          </li>
          <!-- Input Tanggal -->
          <li class="nav-item">
            <input type="date" class="form-control form-control-sm" id="inputTanggal"
              value="<?= htmlspecialchars($yesterday) ?>" style="width:145px">
          </li>
          <!-- Tombol Muat -->
          <li class="nav-item">
            <button class="btn btn-light btn-sm border fw-semibold" id="btnMuat">
              <i class="bi bi-folder2-open"></i> Muat
            </button>
          </li>
          <!-- Tombol Simpan -->
          <li class="nav-item">
            <button class="btn btn-success btn-sm fw-semibold" id="btnSave">
              <i class="bi bi-floppy"></i> Simpan
            </button>
          </li>
          <!-- Tombol Excel -->
          <li class="nav-item">
            <button class="btn btn-warning btn-sm text-dark fw-semibold" id="btnExcel">
              <i class="bi bi-file-earmark-excel"></i> Excel
            </button>
          </li>
          <!-- Tombol Cetak -->
          <li class="nav-item">
            <button class="btn btn-info btn-sm text-dark fw-semibold" id="btnCetak">
              <i class="bi bi-printer"></i> Cetak
            </button>
          </li>
          <!-- Tombol Hapus -->
          <li class="nav-item">
            <button class="btn btn-danger btn-sm" id="btnHapus" title="Hapus laporan ini">
              <i class="bi bi-trash3"></i>
            </button>
          </li>
        </ul>
      </div>
    </nav>

    <!-- ============ SIDEBAR ============ -->
    <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
      <div class="sidebar-brand">
        <a href="index.php" class="brand-link d-flex align-items-center gap-2 px-3 py-3">
          <i class="bi bi-clipboard2-data text-primary fs-4"></i>
          <span class="brand-text fw-bold fs-6">Kasir Laporan</span>
        </a>
      </div>
      <div class="sidebar-wrapper">
        <nav class="mt-2">
          <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu">
            <li class="nav-header">MENU UTAMA</li>
            <li class="nav-item">
              <a href="index.php" class="nav-link">
                <i class="nav-icon bi bi-speedometer2"></i>
                <p>Dashboard</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="laporan.php" class="nav-link active">
                <i class="nav-icon bi bi-file-earmark-medical"></i>
                <p>Input / Edit Laporan</p>
              </a>
            </li>
          </ul>
        </nav>
      </div>
    </aside>

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
            <div class="card card-secondary card-tabs shadow-sm">
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
                        <div class="saldo-formula">= Kas Awal + Cash − Pengeluaran Random</div>
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
                      <div class="card-header bg-white fw-bold text-dark py-2 small">
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

    <!-- ============ FOOTER ============ -->
    <footer class="app-footer">
      <div class="float-end d-none d-sm-inline text-muted small">
        Kasir Laporan Keuangan &copy; <?= date('Y') ?>
      </div>
      <span class="text-muted small">
        <strong>Kasir Apotek &amp; Pendaftaran</strong> – Sistem Laporan Keuangan Harian
      </span>
    </footer>

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