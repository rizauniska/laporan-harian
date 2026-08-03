<?php
// fisio_90.php – Detail Pasien Fisioterapi Rp 90.000 dengan Tabulator JS | AdminLTE 4
declare(strict_types=1);
?>
<!DOCTYPE html>
<html lang="id" data-bs-theme="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pasien Fisioterapi 90k | Kasir Laporan Keuangan</title>
  <meta name="description" content="Laporan detail Pasien Fisioterapi Rp 90.000 kasir pendaftaran.">

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
            <h3 class="mb-0 fw-bold"><i class="fas fa-users me-2" style="color:#0f766e"></i>Pasien Fisioterapi Rp 90.000</h3>
          </div>
          <div class="col-sm-6 text-end">
            <ol class="breadcrumb float-sm-end mb-0">
              <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
              <li class="breadcrumb-item active">Pasien Fisio 90rb</li>
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
            <div class="card shadow-sm stat-card pasien-card p-3">
              <div class="d-flex align-items-center gap-3">
                <div class="stat-icon-wrapper stat-icon-teal">
                  <i class="fas fa-users"></i>
                </div>
                <div>
                  <div class="text-muted small fw-semibold">Total Pasien (Rp 90.000)</div>
                  <div class="fs-5 fw-bold" style="color:#0f766e" id="statPasien">0 Pasien</div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-4 col-sm-6">
            <div class="card shadow-sm stat-card total-card p-3">
              <div class="d-flex align-items-center gap-3">
                <div class="stat-icon-wrapper stat-icon-green">
                  <i class="bi bi-cash-stack"></i>
                </div>
                <div>
                  <div class="text-muted small fw-semibold">Total Nominal</div>
                  <div class="fs-5 fw-bold text-success" id="statTotal">Rp 0</div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-4 col-sm-6">
            <div class="card shadow-sm stat-card avg-card p-3">
              <div class="d-flex align-items-center gap-3">
                <div class="stat-icon-wrapper stat-icon-blue">
                  <i class="bi bi-person-check"></i>
                </div>
                <div>
                  <div class="text-muted small fw-semibold">Rata-rata Pasien / Hari</div>
                  <div class="fs-5 fw-bold text-primary" id="statAvg">0 Pasien</div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- TABEL LAPORAN -->
        <div class="card shadow-sm">
          <div class="card-header d-flex align-items-center justify-content-between py-3">
            <h5 class="card-title mb-0 fw-bold"><i class="bi bi-table text-primary me-2"></i>Rincian Pasien Fisioterapi Rp 90.000</h5>
            <span class="badge bg-secondary no-print" id="badgePeriode">Semua Data</span>
          </div>
          <div class="card-body p-3">
            <div class="print-header px-4 pt-3">
              <h2>Laporan Pasien Fisioterapi Rp 90.000</h2>
              <p id="printPeriode">Periode: Semua Data</p>
              <p>Dicetak: <?= date('d/m/Y H:i') ?></p>
            </div>

            <!-- Tabulator Table (Screen) -->
            <div id="fisio90Table" class="no-print"></div>

            <!-- Summary Screen -->
            <div class="mt-3 p-3 bg-light rounded border d-flex flex-wrap justify-content-between align-items-center no-print">
              <div>
                <span class="fw-bold text-dark me-3"><i class="bi bi-people me-1"></i>Total Pasien: <span class="fs-6" style="color:#0f766e" id="sumPasienText">0 Orang</span></span>
              </div>
              <div>
                <span class="fw-bold text-dark"><i class="bi bi-sigma me-1"></i>JUMLAH NOMINAL: <span class="text-success fs-5" id="sumTotalText">Rp 0</span></span>
              </div>
            </div>

            <!-- Print Table (PDF) -->
            <table class="table table-bordered align-middle mb-0 print-only-table" id="tabelPrint">
              <thead>
                <tr class="text-center">
                  <th style="width:60px">No</th>
                  <th class="text-start">Tanggal</th>
                  <th class="text-center" style="width:160px">Jumlah Pasien (90k)</th>
                  <th class="text-end" style="width:220px">Total Nominal</th>
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

function fmt(num) { const n = Math.round(Number(num) || 0); return 'Rp\u00A0' + n.toLocaleString('id-ID'); }
function fmtTglIndo(d) { try { return new Date(d + 'T00:00:00').toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }); } catch(_) { return d; } }
function fmtTglPrint(d) { try { return new Date(d + 'T00:00:00').toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: 'numeric' }); } catch(_) { return d; } }
function showToast(msg, bg = 'bg-dark') { const t = document.getElementById('appToast'); t.className = `toast align-items-center text-white ${bg} border-0`; document.getElementById('toastMsg').textContent = msg; new bootstrap.Toast(t).show(); }

function initTabulator() {
  tabulatorTable = new Tabulator('#fisio90Table', {
    data: [],
    layout: 'fitColumns',
    pagination: 'local',
    paginationSize: 15,
    columns: [
      { title: 'No', formatter: 'rownum', width: 65, hozAlign: 'center' },
      { title: 'Tanggal Laporan', field: 'tanggal', formatter: cell => `<span class="badge bg-primary bg-opacity-10 text-primary">${fmtTglIndo(cell.getValue())}</span>` },
      { title: 'Jumlah Pasien', field: 'fisio_90_pasien', hozAlign: 'center', formatter: cell => `${cell.getValue()} Orang` },
      { title: 'Total Nominal', field: 'total_nominal', hozAlign: 'right', formatter: cell => `<span class="fw-bold text-success">${fmt(cell.getValue())}</span>` }
    ]
  });
}

async function loadData(start = '', end = '') {
  const tbody = document.getElementById('tbodyPrint');
  const tfoot = document.getElementById('tfootPrint');
  tbody.innerHTML = ''; tfoot.innerHTML = '';

  try {
    let url = 'api/fisio_90.php';
    const params = [];
    if (start) params.push('start=' + encodeURIComponent(start));
    if (end)   params.push('end='   + encodeURIComponent(end));
    if (params.length) url += '?' + params.join('&');

    const res  = await fetch(url);
    const json = await res.json();
    if (!json.success) throw new Error(json.error || 'Error');

    const rows = json.data || [];
    const totPasien = json.total_pasien || 0;
    const total = json.total || 0;
    const count = json.count || 0;

    document.getElementById('statPasien').textContent = totPasien + ' Pasien';
    document.getElementById('statTotal').textContent  = fmt(total);
    document.getElementById('statAvg').textContent    = (count > 0 ? (totPasien / count).toFixed(1) : 0) + ' Pasien';
    document.getElementById('sumPasienText').textContent = totPasien + ' Orang';
    document.getElementById('sumTotalText').textContent  = fmt(total);

    let periodeText = (start && end) ? `${start} s/d ${end}` : (start ? `Mulai ${start}` : (end ? `Sampai ${end}` : 'Semua Data'));
    document.getElementById('badgePeriode').textContent = periodeText;
    document.getElementById('printPeriode').textContent = 'Periode: ' + periodeText;

    if (tabulatorTable) tabulatorTable.setData(rows);

    tbody.innerHTML = rows.map((r, idx) => `
      <tr>
        <td style="border:1px solid #000; padding:6px; text-align:center; color:#000;">${idx + 1}</td>
        <td style="border:1px solid #000; padding:6px; color:#000;">${fmtTglPrint(r.tanggal)}</td>
        <td style="border:1px solid #000; padding:6px; text-align:center; color:#000;">${r.fisio_90_pasien} Orang</td>
        <td style="border:1px solid #000; padding:6px; text-align:right; font-weight:bold; color:#000;">${fmt(r.total_nominal)}</td>
      </tr>
    `).join('');

    tfoot.innerHTML = `
      <tr style="border-top:2.5px solid #000;">
        <td colspan="2" style="border:1px solid #000; padding:6px; text-align:right; font-weight:bold; color:#000;">JUMLAH TOTAL (${count} hari)</td>
        <td style="border:1px solid #000; padding:6px; text-align:center; font-weight:bold; color:#000;">${totPasien} Orang</td>
        <td style="border:1px solid #000; padding:6px; text-align:right; font-weight:bold; color:#000;">${fmt(total)}</td>
      </tr>
    `;
  } catch (err) { showToast('❌ Gagal: ' + err.message, 'bg-danger'); }
}

function applyPreset(preset) {
  const now = new Date(), pad = n => String(n).padStart(2, '0'), fd = d => `${d.getFullYear()}-${pad(d.getMonth()+1)}-${pad(d.getDate())}`;
  let start = '', end = '';
  if (preset === 'this_month') { start = fd(new Date(now.getFullYear(), now.getMonth(), 1)); end = fd(now); }
  else if (preset === 'last_month') { start = fd(new Date(now.getFullYear(), now.getMonth()-1, 1)); end = fd(new Date(now.getFullYear(), now.getMonth(), 0)); }
  else if (preset === 'this_year') { start = fd(new Date(now.getFullYear(), 0, 1)); end = fd(now); }
  document.getElementById('filterStart').value = start; document.getElementById('filterEnd').value = end; loadData(start, end);
}

document.addEventListener('DOMContentLoaded', () => {
  initTabulator(); loadData();
  document.getElementById('btnFilter').addEventListener('click', () => loadData(document.getElementById('filterStart').value, document.getElementById('filterEnd').value));
  document.getElementById('btnReset').addEventListener('click', () => { document.getElementById('filterStart').value = ''; document.getElementById('filterEnd').value = ''; loadData(); });
  document.querySelectorAll('.preset').forEach(el => el.addEventListener('click', function(e) { e.preventDefault(); applyPreset(this.dataset.preset); }));
  document.getElementById('btnCetak').addEventListener('click', () => {
    const printView = document.getElementById('print-view');
    const periodeText = document.getElementById('printPeriode').textContent;
    const tbodyHTML = document.getElementById('tbodyPrint').innerHTML;
    const tfootHTML = document.getElementById('tfootPrint').innerHTML;
    printView.innerHTML = `
      <div style="text-align:center; border-bottom:2px solid #000; padding-bottom:8px; margin-bottom:14px;">
        <h1 style="font-size:15pt; font-weight:800; text-transform:uppercase; margin:0 0 4px 0; color:#000;">Laporan Pasien Fisioterapi Rp 90.000</h1>
        <p style="font-size:9pt; color:#333; margin:0;">${periodeText} &nbsp;|&nbsp; Dicetak: ${new Date().toLocaleString('id-ID')}</p>
      </div>
      <table style="width:100%; border-collapse:collapse; font-size:9.5pt; font-family:Arial, sans-serif; border:1.5px solid #000;">
        <thead>
          <tr style="border-bottom:2.5px solid #000; background:#f0f0f0;">
            <th style="border:1px solid #000; padding:6px; text-align:center; width:50px; color:#000;">No</th>
            <th style="border:1px solid #000; padding:6px; text-align:left; color:#000;">Tanggal Laporan</th>
            <th style="border:1px solid #000; padding:6px; text-align:center; color:#000;">Jumlah Pasien (Rp 90rb)</th>
            <th style="border:1px solid #000; padding:6px; text-align:right; width:220px; color:#000;">Total Pendapatan</th>
          </tr>
        </thead>
        <tbody>${tbodyHTML}</tbody><tfoot>${tfootHTML}</tfoot>
      </table>
    `;
    document.body.classList.add('printing-active'); window.print(); setTimeout(() => document.body.classList.remove('printing-active'), 1000);
  });
});
</script>
</body>
</html>
