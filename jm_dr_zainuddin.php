<?php
// jm_dr_zainuddin.php – Detail JM dr. Zainuddin dengan Tabulator JS | AdminLTE 4
declare(strict_types=1);
?>
<!DOCTYPE html>
<html lang="id" data-bs-theme="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>JM dr. Zainuddin | Kasir Laporan Keuangan</title>
  <meta name="description" content="Laporan detail Jasa Medis dr. Zainuddin kasir apotek.">

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.1/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0-rc4/dist/css/adminlte.min.css">
  <link rel="stylesheet" href="https://unpkg.com/tabulator-tables@6.2.1/dist/css/tabulator_bootstrap5.min.css">
  <!-- Custom Main CSS -->
  <link rel="stylesheet" href="assets/style.css">
</head>
<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
<div class="app-wrapper">

  <!-- TOP NAVBAR -->
  <?php require_once __DIR__ . '/includes/navbar.php'; ?>

  <!-- SIDEBAR -->
  <?php require_once __DIR__ . '/includes/sidebar.php'; ?>

  <!-- MAIN CONTENT -->
  <main class="app-main">
    <div class="app-content-header">
      <div class="container-fluid">
        <div class="row">
          <div class="col-sm-6">
            <h3 class="mb-0 fw-bold"><i class="fas fa-user-md text-info me-2"></i>JM dr. Zainuddin</h3>
          </div>
          <div class="col-sm-6 text-end">
            <ol class="breadcrumb float-sm-end mb-0">
              <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
              <li class="breadcrumb-item active">JM dr. Zainuddin</li>
            </ol>
          </div>
        </div>
      </div>
    </div>

    <div class="app-content">
      <div class="container-fluid">

        <!-- FILTER -->
        <div class="card shadow-sm mb-4 filter-area no-print">
          <div class="card-header py-2">
            <h6 class="card-title mb-0 fw-bold"><i class="bi bi-funnel-fill text-primary me-2"></i>Filter Periode</h6>
            <div class="card-tools"><button type="button" class="btn btn-tool" data-lte-toggle="card-collapse"><i class="bi bi-dash-lg"></i></button></div>
          </div>
          <div class="card-body py-3">
            <div class="row g-2 align-items-end">
              <div class="col-md-3 col-sm-6">
                <label class="form-label small fw-semibold text-muted mb-1" for="filterStart"><i class="bi bi-calendar-event me-1 text-primary"></i> Tanggal Mulai</label>
                <input type="date" class="form-control form-control-sm" id="filterStart">
              </div>
              <div class="col-md-3 col-sm-6">
                <label class="form-label small fw-semibold text-muted mb-1" for="filterEnd"><i class="bi bi-calendar-event me-1 text-primary"></i> Tanggal Selesai</label>
                <input type="date" class="form-control form-control-sm" id="filterEnd">
              </div>
              <div class="col-md-3 col-sm-6 d-flex gap-2">
                <button class="btn btn-primary btn-sm fw-semibold w-100" id="btnFilter"><i class="bi bi-funnel-fill me-1"></i> Terapkan</button>
                <button class="btn btn-outline-secondary btn-sm" id="btnReset" title="Reset"><i class="bi bi-arrow-counterclockwise"></i></button>
              </div>
              <div class="col-md-3 col-sm-6">
                <div class="dropdown">
                  <button class="btn btn-light btn-sm border dropdown-toggle w-100 text-start fw-semibold text-muted" type="button" data-bs-toggle="dropdown">
                    <i class="bi bi-clock-history me-1 text-primary"></i> Periode Cepat
                  </button>
                  <ul class="dropdown-menu shadow-sm small">
                    <li><a class="dropdown-item preset" href="#" data-preset="all">Semua Data</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item preset" href="#" data-preset="this_month">Bulan Ini</a></li>
                    <li><a class="dropdown-item preset" href="#" data-preset="last_month">Bulan Lalu</a></li>
                    <li><a class="dropdown-item preset" href="#" data-preset="this_year">Tahun Ini</a></li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- STAT CARDS -->
        <div class="row g-3 mb-4 stat-cards no-print">
          <div class="col-md-4 col-sm-6">
            <div class="card shadow-sm stat-card total-card p-3">
              <div class="d-flex align-items-center gap-3">
                <div class="stat-icon-wrapper stat-icon-cyan">
                  <i class="fas fa-user-md"></i>
                </div>
                <div>
                  <div class="text-muted small fw-semibold">Total JM dr. Zainuddin</div>
                  <div class="fs-5 fw-bold text-info" id="statTotal">Rp 0</div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-4 col-sm-6">
            <div class="card shadow-sm stat-card count-card p-3">
              <div class="d-flex align-items-center gap-3">
                <div class="stat-icon-wrapper stat-icon-green">
                  <i class="bi bi-calendar-check"></i>
                </div>
                <div>
                  <div class="text-muted small fw-semibold">Jumlah Hari</div>
                  <div class="fs-5 fw-bold text-success" id="statCount">0 Hari</div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-4 col-sm-6">
            <div class="card shadow-sm stat-card stat-card-warning p-3">
              <div class="d-flex align-items-center gap-3">
                <div class="stat-icon-wrapper stat-icon-yellow">
                  <i class="bi bi-graph-up"></i>
                </div>
                <div>
                  <div class="text-muted small fw-semibold">Rata-rata / Hari</div>
                  <div class="fs-5 fw-bold text-warning" id="statAvg">Rp 0</div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- TABEL LAPORAN -->
        <div class="card shadow-sm">
          <div class="card-header d-flex align-items-center justify-content-between py-3">
            <h5 class="card-title mb-0 fw-bold"><i class="bi bi-table text-primary me-2"></i>Rincian JM dr. Zainuddin</h5>
            <span class="badge bg-secondary no-print" id="badgePeriode">Semua Data</span>
          </div>
          <div class="card-body p-3">
            <div class="print-header px-4 pt-3">
              <h2>Laporan JM dr. Zainuddin</h2>
              <p id="printPeriode">Periode: Semua Data</p>
              <p>Dicetak: <?= date('d/m/Y H:i') ?></p>
            </div>

            <!-- Tabulator Table (Screen) -->
            <div id="zainuddinTable" class="no-print"></div>

            <!-- Summary Screen -->
            <div class="mt-3 p-3 bg-light rounded border d-flex justify-content-between align-items-center no-print">
              <span class="fw-bold text-dark"><i class="bi bi-sigma me-1"></i>JUMLAH TOTAL: <span class="text-info fs-5" id="sumTotalText">Rp 0</span></span>
            </div>

            <!-- Print Table (PDF) -->
            <table class="table table-bordered align-middle mb-0 print-only-table" id="tabelPrint">
              <thead>
                <tr class="text-center">
                  <th style="width:60px">No</th>
                  <th class="text-start">Tanggal</th>
                  <th class="text-end" style="width:220px">JM dr. Zainuddin</th>
                </tr>
              </thead>
              <tbody id="tbodyPrint"></tbody>
              <tfoot id="tfootPrint"></tfoot>
            </table>
          </div>
        </div>

      </div>
    </div>
  </main>

  <!-- FOOTER -->
  <?php require_once __DIR__ . '/includes/footer.php'; ?>
</div>

<div class="position-fixed bottom-0 end-0 p-3" style="z-index:1100">
  <div id="appToast" class="toast align-items-center text-white bg-dark border-0" role="alert">
    <div class="d-flex"><div class="toast-body" id="toastMsg"></div><button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button></div>
  </div>
</div>
<!-- PRINT VIEW (Hanya aktif saat cetak PDF) -->
<div id="print-view"></div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0-rc4/dist/js/adminlte.min.js"></script>
<script src="https://unpkg.com/tabulator-tables@6.2.1/dist/js/tabulator.min.js"></script>

<script>
'use strict';

let tabulatorTable = null;

function fmt(num) {
  const n = Math.round(Number(num) || 0);
  return 'Rp\u00A0' + n.toLocaleString('id-ID');
}
function fmtTglIndo(dateStr) {
  if (!dateStr) return '-';
  try {
    return new Date(dateStr + 'T00:00:00').toLocaleDateString('id-ID', {
      weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'
    });
  } catch (_) { return dateStr; }
}
function fmtTglPrint(dateStr) {
  if (!dateStr) return '-';
  try {
    return new Date(dateStr + 'T00:00:00').toLocaleDateString('id-ID', {
      year: 'numeric', month: 'long', day: 'numeric'
    });
  } catch (_) { return dateStr; }
}
function showToast(msg, bgClass = 'bg-dark') {
  const toastEl = document.getElementById('appToast');
  toastEl.className = `toast align-items-center text-white ${bgClass} border-0`;
  document.getElementById('toastMsg').textContent = msg;
  new bootstrap.Toast(toastEl).show();
}

function initTabulator() {
  tabulatorTable = new Tabulator('#zainuddinTable', {
    data: [],
    layout: 'fitColumns',
    responsiveLayout: 'collapse',
    pagination: 'local',
    paginationSize: 15,
    paginationSizeSelector: [10, 15, 30, 50, 100],
    movableColumns: true,
    placeholder: 'Tidak ada data',
    columns: [
      { title: 'No', formatter: 'rownum', width: 65, headerHozAlign: 'center', hozAlign: 'center', headerSort: false },
      {
        title: 'Tanggal Laporan',
        field: 'tanggal',
        headerHozAlign: 'left',
        formatter: cell => `<span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-2 py-1 badge-date"><i class="bi bi-calendar3 me-1"></i>${fmtTglIndo(cell.getValue())}</span>`
      },
      {
        title: 'JM dr. Zainuddin',
        field: 'jm_dr_zainuddin',
        headerHozAlign: 'right',
        hozAlign: 'right',
        formatter: cell => `<span class="fw-bold text-info">${fmt(cell.getValue())}</span>`
      }
    ]
  });
}

async function loadData(start = '', end = '') {
  const tbody = document.getElementById('tbodyPrint');
  const tfoot = document.getElementById('tfootPrint');
  tbody.innerHTML = '';
  tfoot.innerHTML = '';

  try {
    let url = 'api/jm_dr_zainuddin.php';
    const params = [];
    if (start) params.push('start=' + encodeURIComponent(start));
    if (end)   params.push('end='   + encodeURIComponent(end));
    if (params.length) url += '?' + params.join('&');

    const res  = await fetch(url);
    const json = await res.json();

    if (!json.success) throw new Error(json.error || 'Error');

    const rows  = json.data  || [];
    const total = json.total || 0;
    const count = json.count || 0;
    const avg   = count > 0 ? total / count : 0;

    document.getElementById('statTotal').textContent   = fmt(total);
    document.getElementById('statCount').textContent   = count + ' Hari';
    document.getElementById('statAvg').textContent     = fmt(avg);

    let periodeText = 'Semua Data';
    if (start && end)   periodeText = start + ' s/d ' + end;
    else if (start)     periodeText = 'Mulai ' + start;
    else if (end)       periodeText = 'Sampai ' + end;

    document.getElementById('badgePeriode').textContent = periodeText;
    document.getElementById('printPeriode').textContent = 'Periode: ' + periodeText;

    if (tabulatorTable) tabulatorTable.setData(rows);

    if (rows.length === 0) {
      tbody.innerHTML = `<tr><td colspan="3" class="text-center text-muted py-4">Tidak ada data</td></tr>`;
      return;
    }

    tbody.innerHTML = rows.map((r, idx) => `
      <tr>
        <td style="border:1px solid #000; padding:6px; text-align:center; color:#000;">${idx + 1}</td>
        <td style="border:1px solid #000; padding:6px; color:#000;">${fmtTglPrint(r.tanggal)}</td>
        <td style="border:1px solid #000; padding:6px; text-align:right; font-weight:bold; color:#000;">${fmt(r.jm_dr_zainuddin)}</td>
      </tr>
    `).join('');

    tfoot.innerHTML = `
      <tr style="border-top:2.5px solid #000;">
        <td colspan="2" style="border:1px solid #000; padding:6px; text-align:right; font-weight:bold; color:#000;">JUMLAH TOTAL (${count} hari)</td>
        <td style="border:1px solid #000; padding:6px; text-align:right; font-weight:bold; color:#000;">${fmt(total)}</td>
      </tr>
    `;
  } catch (err) {
    showToast('❌ Gagal memuat data: ' + err.message, 'bg-danger');
  }
}

function applyPreset(preset) {
  const now = new Date(), pad = n => String(n).padStart(2, '0'), fd = d => `${d.getFullYear()}-${pad(d.getMonth()+1)}-${pad(d.getDate())}`;
  let start = '', end = '';
  if (preset === 'this_month') { start = fd(new Date(now.getFullYear(), now.getMonth(), 1)); end = fd(now); }
  else if (preset === 'last_month') { start = fd(new Date(now.getFullYear(), now.getMonth()-1, 1)); end = fd(new Date(now.getFullYear(), now.getMonth(), 0)); }
  else if (preset === 'this_year') { start = fd(new Date(now.getFullYear(), 0, 1)); end = fd(now); }

  document.getElementById('filterStart').value = start;
  document.getElementById('filterEnd').value   = end;
  loadData(start, end);
}

document.addEventListener('DOMContentLoaded', () => {
  initTabulator();
  loadData();

  document.getElementById('btnFilter').addEventListener('click', () => {
    loadData(document.getElementById('filterStart').value, document.getElementById('filterEnd').value);
  });
  document.getElementById('btnReset').addEventListener('click', () => {
    document.getElementById('filterStart').value = '';
    document.getElementById('filterEnd').value   = '';
    loadData();
    showToast('Filter berhasil di-reset', 'bg-secondary');
  });
  document.querySelectorAll('.preset').forEach(el => {
    el.addEventListener('click', function(e) { e.preventDefault(); applyPreset(this.dataset.preset); });
  });
  document.getElementById('btnCetak').addEventListener('click', () => {
    const printView = document.getElementById('print-view');
    const periodeText = document.getElementById('printPeriode').textContent;
    const tbodyHTML = document.getElementById('tbodyPrint').innerHTML;
    const tfootHTML = document.getElementById('tfootPrint').innerHTML;

    printView.innerHTML = `
      <div style="text-align:center; border-bottom:2px solid #000; padding-bottom:8px; margin-bottom:14px;">
        <h1 style="font-size:15pt; font-weight:800; text-transform:uppercase; margin:0 0 4px 0; color:#000;">Laporan Jasa Medis dr. Zainuddin</h1>
        <p style="font-size:9pt; color:#333; margin:0;">${periodeText} &nbsp;|&nbsp; Dicetak: ${new Date().toLocaleString('id-ID')}</p>
      </div>
      <table style="width:100%; border-collapse:collapse; font-size:9.5pt; font-family:Arial, sans-serif; border:1.5px solid #000;">
        <thead>
          <tr style="border-bottom:2.5px solid #000; background:#f0f0f0;">
            <th style="border:1px solid #000; padding:6px; text-align:center; width:50px; color:#000;">No</th>
            <th style="border:1px solid #000; padding:6px; text-align:left; color:#000;">Tanggal Laporan</th>
            <th style="border:1px solid #000; padding:6px; text-align:right; width:220px; color:#000;">JM dr. Zainuddin</th>
          </tr>
        </thead>
        <tbody>
          ${tbodyHTML}
        </tbody>
        <tfoot>
          ${tfootHTML}
        </tfoot>
      </table>
      <div style="margin-top:16px; padding-top:6px; border-top:1px dashed #666; font-size:8pt; color:#555; text-align:center;">
        Dokumen ini digenerate otomatis oleh Sistem Laporan Keuangan Kasir PHP/MySQL
      </div>
    `;

    document.body.classList.add('printing-active');
    window.print();
    setTimeout(() => {
      document.body.classList.remove('printing-active');
    }, 1000);
  });
});
</script>
</body>
</html>
