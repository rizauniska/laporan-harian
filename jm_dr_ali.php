<?php
// jm_dr_ali.php – Detail JM dr. Ali dengan Tabulator JS | AdminLTE 4
declare(strict_types=1);
?>
<!DOCTYPE html>
<html lang="id" data-bs-theme="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>JM dr. Ali | Kasir Laporan Keuangan</title>
  <meta name="description" content="Laporan detail Jasa Medis dr. Ali kasir apotek.">

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.1/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0-rc4/dist/css/adminlte.min.css">
  <link rel="stylesheet" href="https://unpkg.com/tabulator-tables@6.2.1/dist/css/tabulator_bootstrap5.min.css">

  <style>
    .brand-text { font-size: 1rem; font-weight: 700; }
    #aliTable { border-radius: 6px; overflow: hidden; font-size: .875rem; }
    .tabulator .tabulator-header .tabulator-col { font-weight: 700; background-color: #f8f9fa; }
    .tabulator-row .tabulator-cell { vertical-align: middle; }
    .badge-date { font-weight: 600; font-size: .85rem; }

    .stat-card { border-left: 4px solid; border-radius: 6px; }
    .stat-card.total-card { border-color: #6c63ff; }
    .stat-card.prog-card  { border-color: #0d6efd; }
    .stat-card.non-card   { border-color: #198754; }

    .print-only-table { display: none; }
    .print-header { display: none; }

    @media print {
      @page { margin: 15mm; size: A4 portrait; }
      .app-header, .app-sidebar, .app-footer, .no-print, .filter-area, .stat-cards, .card-tools, .breadcrumb, .app-content-header, .tabulator, #aliTable { display: none !important; }

      html, body { background: #fff !important; font-family: Arial, sans-serif !important; font-size: 9pt !important; margin: 0 !important; padding: 0 !important; width: 100% !important; }
      .app-wrapper, .app-main, .app-content { display: block !important; margin: 0 !important; padding: 0 !important; width: 100% !important; min-height: auto !important; }
      .container-fluid { padding: 0 !important; max-width: 100% !important; }
      .card { box-shadow: none !important; border: none !important; margin: 0 !important; }
      .card-header { background: #fff !important; border-bottom: 1.5px solid #333 !important; padding: 6px 0 !important; }
      .card-body { padding: 0 !important; }

      .print-header { display: block !important; text-align: center; border-bottom: 2px solid #333; padding-bottom: 8px; margin-bottom: 14px; }
      .print-header h2 { font-size: 14pt; font-weight: 800; text-transform: uppercase; margin: 0; }
      .print-header p  { font-size: 9pt; color: #555; margin: 2px 0 0; }

      .print-only-table { display: table !important; width: 100% !important; border-collapse: collapse !important; font-size: 9pt !important; }
      * { color: #000 !important; background: transparent !important; -webkit-print-color-adjust: economy; print-color-adjust: economy; }
      #tabelPrint th, #tabelPrint td { border: 1px solid #999 !important; padding: 5px 8px !important; background: transparent !important; color: #000 !important; }
      #tabelPrint thead tr { border-bottom: 2.5px solid #000 !important; }
      #tabelPrint th { font-weight: 800 !important; border-bottom: 2.5px solid #000 !important; text-transform: uppercase; letter-spacing: .04em; }
      #tabelPrint .tr-total td { border-top: 2.5px solid #000 !important; font-weight: 800 !important; font-size: 10pt !important; }
    }
  </style>
</head>
<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
<div class="app-wrapper">

  <!-- TOP NAVBAR -->
  <nav class="app-header navbar navbar-expand bg-white shadow-sm border-bottom">
    <div class="container-fluid">
      <ul class="navbar-nav">
        <li class="nav-item"><a class="nav-link" data-lte-toggle="sidebar" href="#" role="button"><i class="bi bi-list fs-5"></i></a></li>
      </ul>
      <a href="index.php" class="navbar-brand ms-2 d-none d-md-flex align-items-center gap-2">
        <i class="bi bi-clipboard2-data text-primary fs-5"></i>
        <span class="brand-text text-dark">Kasir Laporan Keuangan</span>
      </a>
      <ul class="navbar-nav ms-auto align-items-center gap-2">
        <li class="nav-item">
          <button class="btn btn-info btn-sm fw-semibold text-dark no-print" id="btnCetak">
            <i class="bi bi-printer me-1"></i> Cetak / PDF
          </button>
        </li>
        <li class="nav-item">
          <a href="index.php" class="btn btn-outline-secondary btn-sm no-print">
            <i class="bi bi-arrow-left me-1"></i> Kembali
          </a>
        </li>
      </ul>
    </div>
  </nav>

  <!-- SIDEBAR -->
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
          <li class="nav-item"><a href="index.php" class="nav-link"><i class="nav-icon bi bi-speedometer2"></i><p>Dashboard</p></a></li>
          <li class="nav-item"><a href="laporan.php" class="nav-link"><i class="nav-icon bi bi-file-earmark-medical"></i><p>Input / Edit Laporan</p></a></li>
          <li class="nav-header">LAPORAN DETAIL</li>
          <li class="nav-item"><a href="jm_dr_zainuddin.php" class="nav-link"><i class="nav-icon fas fa-user-md"></i><p>JM dr. Zainuddin</p></a></li>
          <li class="nav-item"><a href="jm_dr_ali.php" class="nav-link active"><i class="nav-icon fas fa-stethoscope"></i><p>JM dr. Ali</p></a></li>
          <li class="nav-item"><a href="fisioterapi.php" class="nav-link"><i class="nav-icon fas fa-heartbeat"></i><p>Total Fisioterapi</p></a></li>
          <li class="nav-item"><a href="laboratorium.php" class="nav-link"><i class="nav-icon fas fa-flask"></i><p>Total Laboratorium</p></a></li>
          <li class="nav-item"><a href="fisio_90.php" class="nav-link"><i class="nav-icon fas fa-users"></i><p>Pasien Fisio 90rb</p></a></li>
          <li class="nav-item"><a href="parkir.php" class="nav-link"><i class="nav-icon fas fa-parking"></i><p>Pendapatan Parkir</p></a></li>
        </ul>
      </nav>
    </div>
  </aside>

  <!-- MAIN CONTENT -->
  <main class="app-main">
    <div class="app-content-header">
      <div class="container-fluid">
        <div class="row">
          <div class="col-sm-6">
            <h3 class="mb-0 fw-bold"><i class="fas fa-stethoscope me-2" style="color:#6c63ff"></i>JM dr. Ali (Program & non Program)</h3>
          </div>
          <div class="col-sm-6 text-end">
            <ol class="breadcrumb float-sm-end mb-0">
              <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
              <li class="breadcrumb-item active">JM dr. Ali</li>
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
                <div style="width:48px;height:48px;border-radius:10px;background:rgba(108,99,255,.12);display:flex;align-items:center;justify-content:center;font-size:1.4rem;color:#6c63ff">
                  <i class="fas fa-stethoscope"></i>
                </div>
                <div>
                  <div class="text-muted small fw-semibold">Total JM dr. Ali</div>
                  <div class="fs-5 fw-bold" style="color:#6c63ff" id="statTotal">Rp 0</div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-4 col-sm-6">
            <div class="card shadow-sm stat-card prog-card p-3">
              <div class="d-flex align-items-center gap-3">
                <div style="width:48px;height:48px;border-radius:10px;background:rgba(13,110,253,.12);display:flex;align-items:center;justify-content:center;font-size:1.4rem;color:#0d6efd">
                  <i class="bi bi-journal-check"></i>
                </div>
                <div>
                  <div class="text-muted small fw-semibold">JM Program</div>
                  <div class="fs-5 fw-bold text-primary" id="statProgram">Rp 0</div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-4 col-sm-6">
            <div class="card shadow-sm stat-card non-card p-3">
              <div class="d-flex align-items-center gap-3">
                <div style="width:48px;height:48px;border-radius:10px;background:rgba(25,135,84,.12);display:flex;align-items:center;justify-content:center;font-size:1.4rem;color:#198754">
                  <i class="bi bi-journal-x"></i>
                </div>
                <div>
                  <div class="text-muted small fw-semibold">JM non Program</div>
                  <div class="fs-5 fw-bold text-success" id="statNonProg">Rp 0</div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- TABEL LAPORAN -->
        <div class="card shadow-sm">
          <div class="card-header d-flex align-items-center justify-content-between py-3">
            <h5 class="card-title mb-0 fw-bold"><i class="bi bi-table text-primary me-2"></i>Rincian JM dr. Ali</h5>
            <span class="badge bg-secondary no-print" id="badgePeriode">Semua Data</span>
          </div>
          <div class="card-body p-3">
            <div class="print-header px-4 pt-3">
              <h2>Laporan JM dr. Ali</h2>
              <p id="printPeriode">Periode: Semua Data</p>
              <p>Dicetak: <?= date('d/m/Y H:i') ?></p>
            </div>

            <!-- Tabulator Table (Screen) -->
            <div id="aliTable" class="no-print"></div>

            <!-- Summary Screen -->
            <div class="mt-3 p-3 bg-light rounded border d-flex flex-wrap justify-content-between align-items-center no-print">
              <div>
                <span class="fw-bold text-dark me-3"><i class="bi bi-journal-check me-1"></i>Program: <span class="text-primary fs-6" id="sumProgText">Rp 0</span></span>
                <span class="fw-bold text-dark me-3"><i class="bi bi-journal-x me-1"></i>Non Program: <span class="text-success fs-6" id="sumNonText">Rp 0</span></span>
              </div>
              <div class="mt-2 mt-sm-0">
                <span class="fw-bold text-dark"><i class="bi bi-sigma me-1"></i>TOTAL: <span class="fs-5" style="color:#6c63ff" id="sumTotalText">Rp 0</span></span>
              </div>
            </div>

            <!-- Print Table (PDF) -->
            <table class="table table-bordered align-middle mb-0 print-only-table" id="tabelPrint">
              <thead>
                <tr class="text-center">
                  <th style="width:50px">No</th>
                  <th class="text-start">Tanggal</th>
                  <th class="text-end">JM Program</th>
                  <th class="text-end">JM non Program</th>
                  <th class="text-end">Total JM dr. Ali</th>
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

  <footer class="app-footer no-print">
    <div class="float-end d-none d-sm-inline text-muted small">Kasir Laporan Keuangan &copy; <?= date('Y') ?></div>
    <span class="text-muted small"><strong>Kasir Apotek &amp; Pendaftaran</strong> – Sistem Laporan Keuangan Harian</span>
  </footer>
</div>

<div class="position-fixed bottom-0 end-0 p-3" style="z-index:1100">
  <div id="appToast" class="toast align-items-center text-white bg-dark border-0" role="alert">
    <div class="d-flex"><div class="toast-body" id="toastMsg"></div><button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button></div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0-rc4/dist/js/adminlte.min.js"></script>
<script src="https://unpkg.com/tabulator-tables@6.2.1/dist/js/tabulator.min.js"></script>

<script>
'use strict';
let tabulatorTable = null;

function fmt(num) { return 'Rp\u00A0' + Math.round(Number(num) || 0).toLocaleString('id-ID'); }
function fmtTglIndo(d) { try { return new Date(d + 'T00:00:00').toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }); } catch(_) { return d; } }
function fmtTglPrint(d) { try { return new Date(d + 'T00:00:00').toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: 'numeric' }); } catch(_) { return d; } }
function showToast(msg, bg = 'bg-dark') { const t = document.getElementById('appToast'); t.className = `toast align-items-center text-white ${bg} border-0`; document.getElementById('toastMsg').textContent = msg; new bootstrap.Toast(t).show(); }

function initTabulator() {
  tabulatorTable = new Tabulator('#aliTable', {
    data: [],
    layout: 'fitColumns',
    responsiveLayout: 'collapse',
    pagination: 'local',
    paginationSize: 15,
    paginationSizeSelector: [10, 15, 30, 50, 100],
    movableColumns: true,
    placeholder: 'Tidak ada data JM dr. Ali',
    columns: [
      { title: 'No', formatter: 'rownum', width: 65, headerHozAlign: 'center', hozAlign: 'center', headerSort: false },
      {
        title: 'Tanggal Laporan', field: 'tanggal', headerHozAlign: 'left',
        formatter: cell => `<span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-2 py-1 badge-date"><i class="bi bi-calendar3 me-1"></i>${fmtTglIndo(cell.getValue())}</span>`
      },
      {
        title: 'JM Program', field: 'jm_dr_ali_program', headerHozAlign: 'right', hozAlign: 'right',
        formatter: cell => `<span class="fw-bold text-primary">${fmt(cell.getValue())}</span>`
      },
      {
        title: 'JM non Program', field: 'jm_dr_ali_non_program', headerHozAlign: 'right', hozAlign: 'right',
        formatter: cell => `<span class="fw-bold text-success">${fmt(cell.getValue())}</span>`
      },
      {
        title: 'Total JM dr. Ali', field: 'total_ali', headerHozAlign: 'right', hozAlign: 'right',
        formatter: cell => `<span class="fw-bold" style="color:#6c63ff">${fmt(cell.getValue())}</span>`
      }
    ]
  });
}

async function loadData(start = '', end = '') {
  const tbody = document.getElementById('tbodyPrint');
  const tfoot = document.getElementById('tfootPrint');
  tbody.innerHTML = ''; tfoot.innerHTML = '';

  try {
    let url = 'api/jm_dr_ali.php';
    const params = [];
    if (start) params.push('start=' + encodeURIComponent(start));
    if (end)   params.push('end='   + encodeURIComponent(end));
    if (params.length) url += '?' + params.join('&');

    const res  = await fetch(url);
    const json = await res.json();
    if (!json.success) throw new Error(json.error || 'Error');

    const rows    = json.data || [];
    const totProg = json.total_program  || 0;
    const totNon  = json.total_non_prog || 0;
    const total   = json.total || 0;
    const count   = json.count || 0;

    document.getElementById('statTotal').textContent   = fmt(total);
    document.getElementById('statProgram').textContent = fmt(totProg);
    document.getElementById('statNonProg').textContent = fmt(totNon);
    document.getElementById('sumProgText').textContent  = fmt(totProg);
    document.getElementById('sumNonText').textContent   = fmt(totNon);
    document.getElementById('sumTotalText').textContent = fmt(total);

    let periodeText = 'Semua Data';
    if (start && end)   periodeText = start + ' s/d ' + end;
    else if (start)     periodeText = 'Mulai ' + start;
    else if (end)       periodeText = 'Sampai ' + end;

    document.getElementById('badgePeriode').textContent = periodeText;
    document.getElementById('printPeriode').textContent = 'Periode: ' + periodeText;

    if (tabulatorTable) tabulatorTable.setData(rows);

    if (rows.length === 0) {
      tbody.innerHTML = `<tr><td colspan="5" class="text-center text-muted py-4">Tidak ada data</td></tr>`;
      return;
    }

    tbody.innerHTML = rows.map((r, idx) => `
      <tr>
        <td class="text-center fw-semibold text-muted">${idx + 1}</td>
        <td>${fmtTglPrint(r.tanggal)}</td>
        <td class="text-end fw-bold text-primary">${fmt(r.jm_dr_ali_program)}</td>
        <td class="text-end fw-bold text-success">${fmt(r.jm_dr_ali_non_program)}</td>
        <td class="text-end fw-bold" style="color:#6c63ff">${fmt(r.total_ali)}</td>
      </tr>
    `).join('');

    tfoot.innerHTML = `
      <tr class="tr-total">
        <td colspan="2" class="text-end fw-bold ps-3">JUMLAH TOTAL (${count} hari)</td>
        <td class="text-end fw-bold">${fmt(totProg)}</td>
        <td class="text-end fw-bold">${fmt(totNon)}</td>
        <td class="text-end fw-bold fs-5">${fmt(total)}</td>
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
  document.getElementById('btnCetak').addEventListener('click', () => { window.print(); });
});
</script>
</body>
</html>
