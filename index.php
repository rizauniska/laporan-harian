<?php
// index.php – Dashboard Riwayat Laporan | AdminLTE 4.1.0
declare(strict_types=1);
?>
<!DOCTYPE html>
<html lang="id" data-bs-theme="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard | Kasir Laporan Keuangan</title>
  <meta name="description" content="Dashboard riwayat laporan keuangan harian kasir apotek dan pendaftaran.">

  <!-- Bootstrap 5 + Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <!-- Font Awesome 6 -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.1/css/all.min.css">
  <!-- AdminLTE 4 -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0-rc4/dist/css/adminlte.min.css">
  <!-- Tabulator (Bootstrap 5 theme) -->
  <link rel="stylesheet" href="https://unpkg.com/tabulator-tables@6.2.1/dist/css/tabulator_bootstrap5.min.css">

  <style>
    /* ---- Sidebar brand ---- */
    .brand-text { font-size: 1rem; font-weight: 700; letter-spacing: .02em; }

    /* ---- Small-box KPI tweaks ---- */
    .small-box { border-radius: 10px; overflow: hidden; }
    .small-box .inner h4 { font-size: 1.2rem; font-weight: 700; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .small-box .inner p  { font-size: .8rem; font-weight: 600; opacity: .9; margin-bottom: 0; }
    .small-box-icon { font-size: 3rem; opacity: .25; }

    /* ---- Tabulator tweaks ---- */
    #riwayatTable { border-radius: 6px; overflow: hidden; font-size: .875rem; }
    .tabulator .tabulator-header .tabulator-col { font-weight: 700; }
    .tabulator-row .tabulator-cell { vertical-align: middle; }
    .badge-date { font-weight: 600; font-size: .82rem; }

    /* ---- Filter card ---- */
    .filter-card .card-header { background: #fff; border-bottom: 2px solid #dee2e6; }
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
      <!-- Right nav -->
      <ul class="navbar-nav ms-auto align-items-center gap-2">
        <li class="nav-item">
          <a href="laporan.php" class="btn btn-primary btn-sm fw-semibold">
            <i class="bi bi-plus-circle-fill me-1"></i> Input / Edit Laporan
          </a>
        </li>
      </ul>
    </div>
  </nav>

  <!-- ============ SIDEBAR ============ -->
  <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
    <!-- Sidebar Brand -->
    <div class="sidebar-brand">
      <a href="index.php" class="brand-link d-flex align-items-center gap-2 px-3 py-3">
        <i class="bi bi-clipboard2-data text-primary fs-4"></i>
        <span class="brand-text fw-bold fs-6">Kasir Laporan</span>
      </a>
    </div>
    <!-- Sidebar Menu -->
    <div class="sidebar-wrapper">
      <nav class="mt-2">
        <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu">
          <li class="nav-header">MENU UTAMA</li>
          <li class="nav-item">
            <a href="index.php" class="nav-link active">
              <i class="nav-icon bi bi-speedometer2"></i>
              <p>Dashboard</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="laporan.php" class="nav-link">
              <i class="nav-icon bi bi-file-earmark-medical"></i>
              <p>Input / Edit Laporan</p>
            </a>
          </li>
          <li class="nav-header">LAPORAN DETAIL</li>
          <li class="nav-item">
            <a href="parkir.php" class="nav-link">
              <i class="nav-icon fas fa-parking"></i>
              <p>Pendapatan Parkir</p>
            </a>
          </li>
        </ul>
      </nav>
    </div>
  </aside>

  <!-- ============ MAIN CONTENT ============ -->
  <main class="app-main">

    <!-- Content Header -->
    <div class="app-content-header">
      <div class="container-fluid">
        <div class="row">
          <div class="col-sm-6">
            <h3 class="mb-0 fw-bold">
              <i class="bi bi-speedometer2 text-primary me-2"></i>Dashboard Riwayat Laporan
            </h3>
          </div>
          <div class="col-sm-6 text-end">
            <ol class="breadcrumb float-sm-end mb-0">
              <li class="breadcrumb-item active">Dashboard</li>
            </ol>
          </div>
        </div>
      </div>
    </div>

    <!-- Content Body -->
    <div class="app-content">
      <div class="container-fluid">

        <!-- ===== KPI SMALL-BOX CARDS ===== -->
        <div class="row g-3 mb-4">

          <!-- JM dr. Zainuddin -->
          <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6">
            <div class="small-box text-bg-info">
              <div class="inner">
                <h4 id="statJmZainuddin">Rp 0</h4>
                <p>JM dr. Zainuddin</p>
              </div>
              <div class="small-box-icon">
                <i class="fas fa-user-md"></i>
              </div>
              <a href="laporan.php" class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-100-hover">
                Detail <i class="bi bi-arrow-right-circle ms-1"></i>
              </a>
            </div>
          </div>

          <!-- JM dr. Ali -->
          <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6">
            <div class="small-box text-bg-purple" style="background:#6c63ff!important">
              <div class="inner">
                <h4 id="statJmAli">Rp 0</h4>
                <p>JM dr. Ali (Program + non Program)</p>
              </div>
              <div class="small-box-icon">
                <i class="fas fa-stethoscope"></i>
              </div>
              <a href="laporan.php" class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-100-hover">
                Detail <i class="bi bi-arrow-right-circle ms-1"></i>
              </a>
            </div>
          </div>

          <!-- Fisioterapi -->
          <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6">
            <div class="small-box text-bg-success">
              <div class="inner">
                <h4 id="statFisio">Rp 0</h4>
                <p>Total Pendapatan Fisioterapi</p>
              </div>
              <div class="small-box-icon">
                <i class="fas fa-heartbeat"></i>
              </div>
              <a href="laporan.php" class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-100-hover">
                Detail <i class="bi bi-arrow-right-circle ms-1"></i>
              </a>
            </div>
          </div>

          <!-- Laboratorium -->
          <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6">
            <div class="small-box text-bg-warning">
              <div class="inner">
                <h4 id="statLab">Rp 0</h4>
                <p>Total Laboratorium</p>
              </div>
              <div class="small-box-icon">
                <i class="fas fa-flask"></i>
              </div>
              <a href="laporan.php" class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-100-hover">
                Detail <i class="bi bi-arrow-right-circle ms-1"></i>
              </a>
            </div>
          </div>

          <!-- Pasien Fisio 90rb -->
          <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6">
            <div class="small-box" style="background:#0f766e!important;color:#fff">
              <div class="inner">
                <h4 id="statFisio90Count">0 Pasien</h4>
                <p>Pasien Fisioterapi Rp 90.000</p>
              </div>
              <div class="small-box-icon">
                <i class="fas fa-users"></i>
              </div>
              <a href="laporan.php" class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-100-hover">
                Detail <i class="bi bi-arrow-right-circle ms-1"></i>
              </a>
            </div>
          </div>

          <!-- Parkir -->
          <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6">
            <div class="small-box text-bg-secondary">
              <div class="inner">
                <h4 id="statParkir">Rp 0</h4>
                <p>Total Parkir</p>
              </div>
              <div class="small-box-icon">
                <i class="fas fa-parking"></i>
              </div>
              <a href="parkir.php" class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-100-hover">
                Lihat Detail <i class="bi bi-arrow-right-circle ms-1"></i>
              </a>
            </div>
          </div>

        </div><!-- /row KPI -->

        <!-- ===== FILTER TANGGAL ===== -->
        <div class="card mb-4 shadow-sm filter-card">
          <div class="card-header py-2">
            <h5 class="card-title mb-0 fw-bold">
              <i class="bi bi-funnel-fill text-primary me-2"></i>Filter Periode Laporan
            </h5>
            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-lte-toggle="card-collapse">
                <i class="bi bi-dash-lg"></i>
              </button>
            </div>
          </div>
          <div class="card-body py-3">
            <div class="row g-2 align-items-end">
              <!-- Tanggal Mulai -->
              <div class="col-md-3 col-sm-6">
                <label class="form-label small fw-semibold text-muted mb-1" for="filterStartDate">
                  <i class="bi bi-calendar-event me-1 text-primary"></i> Tanggal Mulai
                </label>
                <input type="date" class="form-control form-control-sm" id="filterStartDate">
              </div>
              <!-- Tanggal Selesai -->
              <div class="col-md-3 col-sm-6">
                <label class="form-label small fw-semibold text-muted mb-1" for="filterEndDate">
                  <i class="bi bi-calendar-event me-1 text-primary"></i> Tanggal Selesai
                </label>
                <input type="date" class="form-control form-control-sm" id="filterEndDate">
              </div>
              <!-- Tombol Filter & Reset -->
              <div class="col-md-3 col-sm-6 d-flex gap-2">
                <button class="btn btn-primary btn-sm fw-semibold w-100" id="btnApplyDateFilter">
                  <i class="bi bi-funnel-fill me-1"></i> Terapkan Filter
                </button>
                <button class="btn btn-outline-secondary btn-sm" id="btnResetDateFilter" title="Reset Filter">
                  <i class="bi bi-arrow-counterclockwise"></i>
                </button>
              </div>
              <!-- Quick Preset -->
              <div class="col-md-3 col-sm-6">
                <div class="dropdown">
                  <button class="btn btn-light btn-sm border dropdown-toggle w-100 text-start fw-semibold text-muted" type="button" data-bs-toggle="dropdown">
                    <i class="bi bi-clock-history me-1 text-primary"></i> Periode Cepat
                  </button>
                  <ul class="dropdown-menu dropdown-menu-end shadow-sm small">
                    <li><a class="dropdown-item preset-date" href="#" data-preset="all">Semua Laporan</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item preset-date" href="#" data-preset="today">Hari Ini</a></li>
                    <li><a class="dropdown-item preset-date" href="#" data-preset="yesterday">Kemarin</a></li>
                    <li><a class="dropdown-item preset-date" href="#" data-preset="this_month">Bulan Ini</a></li>
                    <li><a class="dropdown-item preset-date" href="#" data-preset="last_month">Bulan Lalu</a></li>
                    <li><a class="dropdown-item preset-date" href="#" data-preset="this_year">Tahun Ini</a></li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- ===== TABLE CARD ===== -->
        <div class="card shadow-sm">
          <div class="card-header py-3 d-flex flex-wrap align-items-center justify-content-between gap-2">
            <h5 class="card-title mb-0 fw-bold">
              <i class="bi bi-table text-primary me-2"></i>Daftar Riwayat Laporan Keuangan
            </h5>
            <div class="card-tools d-flex align-items-center gap-2">
              <!-- Filter Teks -->
              <div class="input-group input-group-sm" style="width:200px">
                <span class="input-group-text bg-light"><i class="bi bi-search text-muted"></i></span>
                <input type="text" class="form-control" id="filterInput" placeholder="Cari tanggal...">
              </div>
              <!-- Export -->
              <div class="btn-group btn-group-sm">
                <button class="btn btn-outline-secondary" id="btnExportCsv" title="Export CSV">
                  <i class="bi bi-filetype-csv me-1"></i>CSV
                </button>
                <button class="btn btn-outline-success" id="btnExportXlsx" title="Export Excel">
                  <i class="bi bi-file-earmark-excel me-1"></i>Excel
                </button>
              </div>
              <button class="btn btn-primary btn-sm fw-semibold" id="btnRefresh">
                <i class="bi bi-arrow-clockwise"></i> Refresh
              </button>
            </div>
          </div>
          <div class="card-body p-3">
            <div id="riwayatTable"></div>
          </div>
        </div>

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

<!-- ============ MODAL HAPUS ============ -->
<div class="modal fade" id="modalHapus" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-sm">
    <div class="modal-content border-0 shadow">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title fs-6"><i class="bi bi-exclamation-triangle-fill me-2"></i>Hapus Laporan</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body text-center py-4">
        <p class="mb-1 fw-semibold text-dark">Hapus laporan tanggal <span id="delTanggalText" class="text-danger fw-bold"></span>?</p>
        <p class="text-muted small mb-0">Tindakan ini tidak dapat dibatalkan.</p>
      </div>
      <div class="modal-footer justify-content-center border-0 pt-0 pb-3">
        <button type="button" class="btn btn-light btn-sm px-3" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-danger btn-sm px-3" id="btnConfirmDelete">
          <i class="bi bi-trash3 me-1"></i> Hapus
        </button>
      </div>
    </div>
  </div>
</div>

<!-- ============ TOAST ============ -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index:1100">
  <div id="appToast" class="toast align-items-center text-white bg-dark border-0" role="alert">
    <div class="d-flex">
      <div class="toast-body" id="toastMsg"></div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
    </div>
  </div>
</div>

<!-- JS Libraries -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0-rc4/dist/js/adminlte.min.js"></script>
<script src="https://cdn.sheetjs.com/xlsx-0.20.1/package/dist/xlsx.full.min.js"></script>
<script src="https://unpkg.com/tabulator-tables@6.2.1/dist/js/tabulator.min.js"></script>

<script>
'use strict';

let table = null;
let deleteTargetId = null;

function formatNum(num) {
  const n = Math.round(Number(num) || 0);
  return n.toLocaleString('id-ID');
}
function fmt(num) {
  return 'Rp\u00A0' + formatNum(num);
}
function fmtTglIndo(dateStr) {
  if (!dateStr) return '-';
  try {
    return new Date(dateStr + 'T00:00:00').toLocaleDateString('id-ID', {
      weekday: 'short', year: 'numeric', month: 'short', day: 'numeric'
    });
  } catch (_) { return dateStr; }
}

function showToast(msg, bgClass = 'bg-dark') {
  const toastEl = document.getElementById('appToast');
  const msgEl = document.getElementById('toastMsg');
  toastEl.className = `toast align-items-center text-white ${bgClass} border-0`;
  msgEl.textContent = msg;
  const bsToast = new bootstrap.Toast(toastEl);
  bsToast.show();
}

/** Hitung & Update KPI Small-Box dari data tabel */
function updateKPI(data) {
  let totJmZainuddin = 0;
  let totJmAli       = 0;
  let totFisio       = 0;
  let totLab         = 0;
  let totFisio90Cnt  = 0;
  let totParkir      = 0;

  data.forEach(item => {
    totJmZainuddin += Number(item.jm_dr_zainuddin)       || 0;
    totJmAli       += (Number(item.jm_dr_ali_program)    || 0) + (Number(item.jm_dr_ali_non_program) || 0);
    totFisio       += (Number(item.p_fisio_90)           || 0) + (Number(item.p_fisio_120)           || 0);
    totLab         += Number(item.p_total_lab)           || 0;
    totFisio90Cnt  += Number(item.p_fisio_90_count)      || 0;
    totParkir      += Number(item.p_parkir)              || 0;
  });

  document.getElementById('statJmZainuddin').textContent  = fmt(totJmZainuddin);
  document.getElementById('statJmAli').textContent        = fmt(totJmAli);
  document.getElementById('statFisio').textContent        = fmt(totFisio);
  document.getElementById('statLab').textContent          = fmt(totLab);
  document.getElementById('statFisio90Count').textContent = totFisio90Cnt + ' Pasien';
  document.getElementById('statParkir').textContent       = fmt(totParkir);
}

function customDateFilter(data) {
  const startDate = document.getElementById('filterStartDate').value;
  const endDate   = document.getElementById('filterEndDate').value;
  const rowDate   = data.tanggal;
  if (!rowDate) return false;
  if (startDate && rowDate < startDate) return false;
  if (endDate   && rowDate > endDate)   return false;
  return true;
}

function applyFilter() {
  if (!table) return;
  const startDate  = document.getElementById('filterStartDate').value;
  const endDate    = document.getElementById('filterEndDate').value;
  const textSearch = document.getElementById('filterInput').value.trim();
  table.clearFilter();
  if (textSearch) table.addFilter('tanggal', 'like', textSearch);
  if (startDate || endDate) table.addFilter(customDateFilter);
  setTimeout(() => { updateKPI(table.getData("active")); }, 60);
}

function applyPreset(preset) {
  const now = new Date();
  const pad = n => String(n).padStart(2, '0');
  const fmtDate = d => `${d.getFullYear()}-${pad(d.getMonth()+1)}-${pad(d.getDate())}`;
  let start = '', end = '';
  if (preset === 'today') {
    start = end = fmtDate(now);
  } else if (preset === 'yesterday') {
    const y = new Date(now); y.setDate(y.getDate()-1); start = end = fmtDate(y);
  } else if (preset === 'this_month') {
    start = fmtDate(new Date(now.getFullYear(), now.getMonth(), 1)); end = fmtDate(now);
  } else if (preset === 'last_month') {
    start = fmtDate(new Date(now.getFullYear(), now.getMonth()-1, 1));
    end   = fmtDate(new Date(now.getFullYear(), now.getMonth(), 0));
  } else if (preset === 'this_year') {
    start = fmtDate(new Date(now.getFullYear(), 0, 1)); end = fmtDate(now);
  }
  document.getElementById('filterStartDate').value = start;
  document.getElementById('filterEndDate').value   = end;
  applyFilter();
}

function initTabulator() {
  table = new Tabulator('#riwayatTable', {
    ajaxURL: 'api/list.php?full=1',
    ajaxResponse: function(url, params, response) {
      if (response && response.success && Array.isArray(response.data)) {
        updateKPI(response.data);
        return response.data;
      }
      return [];
    },
    layout: 'fitColumns',
    responsiveLayout: 'collapse',
    pagination: 'local',
    paginationSize: 10,
    paginationSizeSelector: [5, 10, 25, 50, 100],
    movableColumns: true,
    placeholder: 'Belum ada data laporan tersimpan',
    columns: [
      { title: 'No', formatter: 'rownum', width: 55, headerHozAlign: 'center', hozAlign: 'center', headerSort: false },
      {
        title: 'Tanggal Laporan', field: 'tanggal', width: 200, headerHozAlign: 'left',
        formatter: function(cell) {
          const val = cell.getValue();
          return `<span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-2 py-1 badge-date">
                    <i class="bi bi-calendar3 me-1"></i>${fmtTglIndo(val)}
                  </span>`;
        }
      },
      { title: 'Total Kas Awal',    field: 'tot_kas_awal',    headerHozAlign: 'right', hozAlign: 'right', formatter: cell => fmt(cell.getValue()) },
      { title: 'Total Pemasukan',   field: 'tot_pemasukan',   headerHozAlign: 'right', hozAlign: 'right', formatter: cell => `<span class="fw-bold text-success">${fmt(cell.getValue())}</span>` },
      { title: 'Total Pengeluaran', field: 'tot_pengeluaran', headerHozAlign: 'right', hozAlign: 'right', formatter: cell => `<span class="fw-bold text-danger">${fmt(cell.getValue())}</span>` },
      { title: 'Saldo Apotek',      field: 'a_saldo',         headerHozAlign: 'right', hozAlign: 'right', formatter: cell => `<span class="fw-semibold text-secondary">${fmt(cell.getValue())}</span>` },
      { title: 'Saldo Pendaftaran', field: 'p_saldo',         headerHozAlign: 'right', hozAlign: 'right', formatter: cell => `<span class="fw-semibold text-secondary">${fmt(cell.getValue())}</span>` },
      {
        title: 'Saldo Keseluruhan', field: 'grand_saldo', headerHozAlign: 'right', hozAlign: 'right',
        formatter: function(cell) {
          const val = cell.getValue();
          return `<span class="fw-bold fs-6 ${val < 0 ? 'text-danger' : 'text-primary'}">${fmt(val)}</span>`;
        }
      },
      {
        title: 'Aksi', field: 'id', width: 140, headerHozAlign: 'center', hozAlign: 'center', headerSort: false,
        formatter: function(cell) {
          const d = cell.getRow().getData();
          return `<div class="btn-group btn-group-sm">
            <a href="laporan.php?tanggal=${d.tanggal}" class="btn btn-outline-primary" title="Buka / Edit"><i class="bi bi-pencil-square"></i> Buka</a>
            <button type="button" class="btn btn-outline-danger btn-del" data-id="${d.id}" data-tgl="${d.tanggal}" title="Hapus"><i class="bi bi-trash3"></i></button>
          </div>`;
        }
      }
    ]
  });

  table.on("dataFiltered", function(filters, rows) {
    updateKPI(rows.map(r => r.getData()));
  });

  document.getElementById('riwayatTable').addEventListener('click', function(e) {
    const btn = e.target.closest('.btn-del');
    if (btn) {
      deleteTargetId = btn.dataset.id;
      document.getElementById('delTanggalText').textContent = fmtTglIndo(btn.dataset.tgl);
      new bootstrap.Modal(document.getElementById('modalHapus')).show();
    }
  });
}

document.addEventListener('DOMContentLoaded', () => {
  initTabulator();

  document.getElementById('btnApplyDateFilter').addEventListener('click', applyFilter);

  document.getElementById('btnResetDateFilter').addEventListener('click', function() {
    document.getElementById('filterStartDate').value = '';
    document.getElementById('filterEndDate').value   = '';
    document.getElementById('filterInput').value     = '';
    table.clearFilter();
    updateKPI(table.getData());
    showToast('Filter berhasil di-reset', 'bg-secondary');
  });

  document.getElementById('filterInput').addEventListener('keyup', applyFilter);

  document.querySelectorAll('.preset-date').forEach(item => {
    item.addEventListener('click', function(e) {
      e.preventDefault();
      applyPreset(this.dataset.preset);
    });
  });

  document.getElementById('btnExportCsv').addEventListener('click', function() {
    table.download('csv', `Riwayat_Laporan_${new Date().toISOString().split('T')[0]}.csv`);
  });

  document.getElementById('btnExportXlsx').addEventListener('click', function() {
    table.download('xlsx', `Riwayat_Laporan_${new Date().toISOString().split('T')[0]}.xlsx`, { sheetName: 'Riwayat Laporan' });
  });

  document.getElementById('btnRefresh').addEventListener('click', function() {
    table.setData('api/list.php?full=1');
    showToast('Data berhasil diperbarui', 'bg-info');
  });

  document.getElementById('btnConfirmDelete').addEventListener('click', async function() {
    if (!deleteTargetId) return;
    try {
      const res  = await fetch('api/delete.php', { method: 'POST', headers: {'Content-Type':'application/json'}, body: JSON.stringify({id: deleteTargetId}) });
      const json = await res.json();
      if (json.success) {
        showToast('✅ Laporan berhasil dihapus', 'bg-success');
        table.setData('api/list.php?full=1');
      } else {
        showToast('❌ Gagal menghapus: ' + (json.error || 'Error'), 'bg-danger');
      }
    } catch (err) {
      showToast('❌ Gagal terhubung ke server', 'bg-danger');
    } finally {
      const modal = bootstrap.Modal.getInstance(document.getElementById('modalHapus'));
      if (modal) modal.hide();
    }
  });
});
</script>

</body>
</html>
