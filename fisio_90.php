<?php
// fisio_90.php – Detail Pasien Fisio 90rb dengan DataTables | AdminLTE 4
declare(strict_types=1);
?>
<!DOCTYPE html>
<html lang="id" data-bs-theme="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pasien Fisio 90rb | Kasir Laporan Keuangan</title>
  <meta name="description" content="Laporan detail Pasien Fisioterapi 90.000 kasir pendaftaran.">

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.1/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0-rc4/dist/css/adminlte.min.css">
  <!-- DataTables Bootstrap 5 CSS -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
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
            <h3 class="mb-0 fw-bold"><i class="fas fa-users text-warning me-2"></i>Pasien Fisio 90rb</h3>
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
            <div class="card shadow-sm stat-card total-card p-3">
              <div class="d-flex align-items-center gap-3">
                <div class="stat-icon-wrapper stat-icon-yellow"><i class="fas fa-users"></i></div>
                <div>
                  <div class="text-muted small fw-semibold">Total Pasien Fisio 90rb</div>
                  <div class="fs-5 fw-bold text-warning" id="statTotalPasien">0 Pasien</div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-4 col-sm-6">
            <div class="card shadow-sm stat-card total-card p-3">
              <div class="d-flex align-items-center gap-3">
                <div class="stat-icon-wrapper stat-icon-green"><i class="bi bi-cash-stack"></i></div>
                <div>
                  <div class="text-muted small fw-semibold">Total Pendapatan (x 90.000)</div>
                  <div class="fs-5 fw-bold text-success" id="statTotalNominal">Rp 0</div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-4 col-sm-6">
            <div class="card shadow-sm stat-card count-card p-3">
              <div class="d-flex align-items-center gap-3">
                <div class="stat-icon-wrapper stat-icon-blue"><i class="bi bi-calendar-check"></i></div>
                <div>
                  <div class="text-muted small fw-semibold">Jumlah Hari Ada Pasien</div>
                  <div class="fs-5 fw-bold text-primary" id="statCount">0 Hari</div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- TABLE CARD -->
        <div class="card shadow-sm">
          <div class="card-header d-flex align-items-center justify-content-between py-3">
            <h5 class="card-title mb-0 fw-bold"><i class="bi bi-table text-primary me-2"></i>Rincian Pasien Fisio 90rb (DataTables)</h5>
            <div class="d-flex align-items-center gap-2 no-print">
              <span class="badge bg-secondary" id="badgePeriode">Semua Data</span>
              <button class="btn btn-primary btn-sm fw-semibold" id="btnCetak"><i class="bi bi-printer me-1"></i> Cetak PDF</button>
            </div>
          </div>
          <div class="card-body p-3">
            <div class="table-responsive no-print">
              <table class="table table-striped table-bordered table-hover align-middle mb-0" id="fisio90Table" style="width:100%">
                <thead class="table-light">
                  <tr>
                    <th class="text-center" width="50">No</th>
                    <th>Tanggal Laporan</th>
                    <th class="text-center" width="160">Jumlah Pasien</th>
                    <th class="text-end" width="220">Total Nominal (Rp)</th>
                  </tr>
                </thead>
                <tbody id="tbodyScreen"></tbody>
                <tfoot class="table-light fw-bold">
                  <tr>
                    <td colspan="2" class="text-end">JUMLAH TOTAL:</td>
                    <td class="text-center text-warning fs-6" id="sumPasien">0 Pasien</td>
                    <td class="text-end text-success fs-6" id="sumNominal">Rp 0</td>
                  </tr>
                </tfoot>
              </table>
            </div>

            <!-- Print table -->
            <table class="table table-bordered align-middle mb-0 print-only-table" id="tabelPrint" style="display:none;">
              <thead>
                <tr class="text-center">
                  <th style="width:50px">No</th>
                  <th class="text-start">Tanggal</th>
                  <th style="width:140px">Jumlah Pasien</th>
                  <th class="text-end" style="width:200px">Total Nominal</th>
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

<div id="print-view"></div>

<!-- Toast -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1100">
  <div id="appToast" class="toast align-items-center text-white bg-dark border-0" role="alert">
    <div class="d-flex"><div class="toast-body" id="toastMsg"></div><button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button></div>
  </div>
</div>

<!-- SCRIPTS -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0-rc4/dist/js/adminlte.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

<script>
'use strict';
let dtTable = null;

const dtIndonesian = {
  search: "Cari:",
  lengthMenu: "Tampilkan _MENU_ data",
  info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
  infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
  infoFiltered: "(disaring dari _MAX_ total data)",
  zeroRecords: "Tidak ada data yang cocok",
  paginate: { first: "Pertama", last: "Terakhir", next: "Berikutnya", previous: "Sebelumnya" }
};

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

async function loadData(start = '', end = '') {
  const tbodyPrint = document.getElementById('tbodyPrint');
  const tfootPrint = document.getElementById('tfootPrint');
  tbodyPrint.innerHTML = '';
  tfootPrint.innerHTML = '';

  try {
    let url = 'api/fisio_90.php';
    const params = [];
    if (start) params.push('start=' + encodeURIComponent(start));
    if (end)   params.push('end='   + encodeURIComponent(end));
    if (params.length) url += '?' + params.join('&');

    const res  = await fetch(url);
    const json = await res.json();

    if (!json.success) throw new Error(json.error || 'Error');

    const rows       = json.data || [];
    const totalP90   = Number(json.total_pasien) || 0;
    const totalNom   = Number(json.total_nominal !== undefined ? json.total_nominal : (json.total !== undefined ? json.total : 0)) || 0;
    const count      = Number(json.count) || 0;

    document.getElementById('statTotalPasien').textContent  = totalP90 + ' Pasien';
    document.getElementById('statTotalNominal').textContent = fmt(totalNom);
    document.getElementById('statCount').textContent        = count + ' Hari';

    document.getElementById('sumPasien').textContent  = totalP90 + ' Pasien';
    document.getElementById('sumNominal').textContent = fmt(totalNom);

    let periodeText = 'Semua Data';
    if (start && end)   periodeText = start + ' s/d ' + end;
    else if (start)     periodeText = 'Mulai ' + start;
    else if (end)       periodeText = 'Sampai ' + end;

    document.getElementById('badgePeriode').textContent = periodeText;

    const tableData = rows.map((r, idx) => {
      const rowNom = Number(r.total_nominal !== undefined ? r.total_nominal : (r.fisio_90_total !== undefined ? r.fisio_90_total : (r.fisio_90_pasien * 90000))) || 0;
      return [
        idx + 1,
        `<span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-2 py-1"><i class="bi bi-calendar3 me-1"></i>${fmtTglIndo(r.tanggal)}</span>`,
        `<span class="badge bg-warning text-dark fs-6">${r.fisio_90_pasien} Pasien</span>`,
        `<span class="fw-bold text-success">${fmt(rowNom)}</span>`
      ];
    });

    if (dtTable) {
      dtTable.destroy();
      $('#tbodyScreen').empty();
    }

    dtTable = $('#fisio90Table').DataTable({
      data: tableData,
      language: dtIndonesian,
      pageLength: 15,
      order: [[1, 'desc']],
      columnDefs: [
        { targets: [0, 2], className: 'text-center', orderable: false },
        { targets: [3], className: 'text-end' }
      ],
      responsive: true
    });

    // Print markup
    tbodyPrint.innerHTML = rows.map((r, idx) => {
      const rowNom = Number(r.total_nominal !== undefined ? r.total_nominal : (r.fisio_90_total !== undefined ? r.fisio_90_total : (r.fisio_90_pasien * 90000))) || 0;
      return `
        <tr>
          <td style="border:1px solid #000; padding:6px; text-align:center; color:#000;">${idx + 1}</td>
          <td style="border:1px solid #000; padding:6px; color:#000;">${fmtTglPrint(r.tanggal)}</td>
          <td style="border:1px solid #000; padding:6px; text-align:center; color:#000;">${r.fisio_90_pasien} Pasien</td>
          <td style="border:1px solid #000; padding:6px; text-align:right; font-weight:bold; color:#000;">${fmt(rowNom)}</td>
        </tr>
      `;
    }).join('');

    tfootPrint.innerHTML = `
      <tr style="border-top:2.5px solid #000;">
        <td colspan="2" style="border:1px solid #000; padding:6px; text-align:right; font-weight:bold; color:#000;">JUMLAH TOTAL (${count} hari)</td>
        <td style="border:1px solid #000; padding:6px; text-align:center; font-weight:bold; color:#000;">${totalP90} Pasien</td>
        <td style="border:1px solid #000; padding:6px; text-align:right; font-weight:bold; color:#000;">${fmt(totalNom)}</td>
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
    const periodeText = document.getElementById('badgePeriode').textContent;
    const tbodyHTML = document.getElementById('tbodyPrint').innerHTML;
    const tfootHTML = document.getElementById('tfootPrint').innerHTML;

    printView.innerHTML = `
      <div style="text-align:center; border-bottom:2px solid #000; padding-bottom:8px; margin-bottom:14px;">
        <h1 style="font-size:15pt; font-weight:800; text-transform:uppercase; margin:0 0 4px 0; color:#000;">Laporan Pasien Fisioterapi (90rb)</h1>
        <p style="font-size:9pt; color:#333; margin:0;">Periode: ${periodeText} &nbsp;|&nbsp; Dicetak: ${new Date().toLocaleString('id-ID')}</p>
      </div>
      <table style="width:100%; border-collapse:collapse; font-size:9.5pt; font-family:Arial, sans-serif; border:1.5px solid #000;">
        <thead>
          <tr style="border-bottom:2.5px solid #000; background:#f0f0f0;">
            <th style="border:1px solid #000; padding:6px; text-align:center; width:50px; color:#000;">No</th>
            <th style="border:1px solid #000; padding:6px; text-align:left; color:#000;">Tanggal Laporan</th>
            <th style="border:1px solid #000; padding:6px; text-align:center; width:140px; color:#000;">Jumlah Pasien</th>
            <th style="border:1px solid #000; padding:6px; text-align:right; width:200px; color:#000;">Total Nominal</th>
          </tr>
        </thead>
        <tbody>${tbodyHTML}</tbody>
        <tfoot>${tfootHTML}</tfoot>
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
