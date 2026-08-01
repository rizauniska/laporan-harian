<?php
// index.php – Halaman Utama / Dashboard Riwayat Laporan dengan Tabulator JS
declare(strict_types=1);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Riwayat Laporan – Kasir Apotek &amp; Pendaftaran</title>
  
  <!-- Bootstrap 5 CSS & Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

  <!-- Tabulator JS CSS (Bootstrap 5 Theme) -->
  <link rel="stylesheet" href="https://unpkg.com/tabulator-tables@6.2.1/dist/css/tabulator_bootstrap5.min.css">

  <style>
    body { background-color: #f4f6f9; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
    .card-kpi { border: none; border-radius: 12px; transition: transform 180ms ease, box-shadow 180ms ease; }
    .card-kpi:hover { transform: translateY(-3px); box-shadow: 0 8px 24px rgba(0,0,0,0.08); }
    .kpi-icon { width: 48px; height: 48px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; }
    
    /* Custom tabulator tweak */
    #riwayatTable { border-radius: 8px; overflow: hidden; font-size: 0.88rem; }
    .tabulator .tabulator-header .tabulator-col { font-weight: 700; background-color: #f8f9fa; }
    .tabulator-row .tabulator-cell { vertical-align: middle; }
    .badge-date { font-weight: 600; font-size: 0.85rem; }
  </style>
</head>
<body>

<div id="app">
  <!-- NAVBAR -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-primary py-2 shadow-sm">
    <div class="container-fluid px-4">
      <span class="navbar-brand fw-bold me-auto">
        <i class="bi bi-speedometer2 me-2"></i> Dashboard Riwayat Laporan
      </span>
      <div class="d-flex align-items-center gap-2">
        <a href="laporan.php" class="btn btn-light btn-sm fw-semibold">
          <i class="bi bi-plus-circle-fill text-primary me-1"></i> Input / Edit Laporan
        </a>
      </div>
    </div>
  </nav>

  <!-- MAIN CONTAINER -->
  <div class="container-fluid px-4 py-4">

    <!-- KPI STAT CARDS -->
    <div class="row g-3 mb-4">
      <div class="col-md col-sm-6">
        <div class="card card-kpi bg-white p-3 shadow-sm h-100">
          <div class="d-flex align-items-center gap-3">
            <div class="kpi-icon bg-primary bg-opacity-10 text-primary">
              <i class="bi bi-person-badge"></i>
            </div>
            <div>
              <div class="text-muted small fw-semibold">JM dr. Zainuddin</div>
              <div class="fs-5 fw-bold text-primary" id="statJmZainuddin">Rp 0</div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md col-sm-6">
        <div class="card card-kpi bg-white p-3 shadow-sm h-100">
          <div class="d-flex align-items-center gap-3">
            <div class="kpi-icon bg-purple bg-opacity-10" style="background:rgba(108,99,255,.12);color:#6c63ff">
              <i class="bi bi-person-badge-fill"></i>
            </div>
            <div>
              <div class="text-muted small fw-semibold">JM dr. Ali (Program + non Program)</div>
              <div class="fs-5 fw-bold" style="color:#6c63ff" id="statJmAli">Rp 0</div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md col-sm-6">
        <div class="card card-kpi bg-white p-3 shadow-sm h-100">
          <div class="d-flex align-items-center gap-3">
            <div class="kpi-icon bg-success bg-opacity-10 text-success">
              <i class="bi bi-activity"></i>
            </div>
            <div>
              <div class="text-muted small fw-semibold">Total Pendapatan Fisioterapi</div>
              <div class="fs-5 fw-bold text-success" id="statFisio">Rp 0</div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md col-sm-6">
        <div class="card card-kpi bg-white p-3 shadow-sm h-100">
          <div class="d-flex align-items-center gap-3">
            <div class="kpi-icon bg-warning bg-opacity-10 text-warning">
              <i class="bi bi-droplet-half"></i>
            </div>
            <div>
              <div class="text-muted small fw-semibold">Total Laboratorium</div>
              <div class="fs-5 fw-bold text-warning" id="statLab">Rp 0</div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md col-sm-6">
        <div class="card card-kpi bg-white p-3 shadow-sm h-100">
          <div class="d-flex align-items-center gap-3">
            <div class="kpi-icon bg-info bg-opacity-10 text-info">
              <i class="bi bi-people-fill"></i>
            </div>
            <div>
              <div class="text-muted small fw-semibold">Pasien Fisioterapi Rp 90.000</div>
              <div class="fs-5 fw-bold text-info" id="statFisio90Count">0 Pasien</div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md col-sm-6">
        <div class="card card-kpi bg-white p-3 shadow-sm h-100">
          <div class="d-flex align-items-center gap-3">
            <div class="kpi-icon" style="background:rgba(23,162,184,.12);color:#117a8b">
              <i class="bi bi-p-circle-fill"></i>
            </div>
            <div>
              <div class="text-muted small fw-semibold">Total Parkir</div>
              <div class="fs-5 fw-bold" style="color:#117a8b" id="statParkir">Rp 0</div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- FILTER TANGGAL TOOLBAR CARD -->
    <div class="card border-0 shadow-sm rounded-3 mb-3">
      <div class="card-body p-3">
        <div class="row g-2 align-items-end">
          <!-- Filter Tanggal Mulai -->
          <div class="col-md-3 col-sm-6">
            <label class="form-label small fw-semibold text-muted mb-1" for="filterStartDate">
              <i class="bi bi-calendar-event me-1 text-primary"></i> Tanggal Mulai
            </label>
            <input type="date" class="form-control form-control-sm" id="filterStartDate">
          </div>

          <!-- Filter Tanggal Selesai -->
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
            <button class="btn btn-outline-secondary btn-sm fw-semibold" id="btnResetDateFilter" title="Reset Filter Tanggal">
              <i class="bi bi-arrow-counterclockwise"></i> Reset
            </button>
          </div>

          <!-- Quick Preset Dropdown -->
          <div class="col-md-3 col-sm-6 text-end">
            <div class="dropdown">
              <button class="btn btn-light btn-sm border dropdown-toggle w-100 text-start fw-semibold text-muted" type="button" data-bs-toggle="dropdown">
                <i class="bi bi-clock-history me-1 text-primary"></i> Pilih Periode Cepat
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

    <!-- TABLE CARD -->
    <div class="card border-0 shadow-sm rounded-3">
      <div class="card-header bg-white py-3 border-0 d-flex flex-wrap align-items-center justify-content-between gap-2">
        <h5 class="fw-bold mb-0 text-dark">
          <i class="bi bi-table text-primary me-2"></i> Daftar Riwayat Laporan Keuangan
        </h5>
        
        <!-- TOOLBAR: FILTER TEXT & EXPORT -->
        <div class="d-flex flex-wrap align-items-center gap-2">
          <div class="input-group input-group-sm" style="width: 200px;">
            <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
            <input type="text" class="form-control border-start-0" id="filterInput" placeholder="Cari tanggal...">
          </div>

          <div class="btn-group btn-group-sm">
            <button class="btn btn-outline-secondary" id="btnExportCsv" title="Export CSV">
              <i class="bi bi-filetype-csv me-1"></i> CSV
            </button>
            <button class="btn btn-outline-success" id="btnExportXlsx" title="Export Excel">
              <i class="bi bi-file-earmark-excel me-1"></i> Excel
            </button>
          </div>

          <button class="btn btn-primary btn-sm fw-semibold" id="btnRefresh" title="Refresh Data">
            <i class="bi bi-arrow-clockwise"></i> Refresh
          </button>
        </div>
      </div>

      <div class="card-body p-3">
        <!-- TABULATOR TABLE CONTAINER -->
        <div id="riwayatTable"></div>
      </div>
    </div>

  </div>
</div>

<!-- MODAL KONFIRMASI HAPUS -->
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

<!-- TOAST -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1100">
  <div id="appToast" class="toast align-items-center text-white bg-dark border-0" role="alert">
    <div class="d-flex">
      <div class="toast-body" id="toastMsg"></div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
    </div>
  </div>
</div>

<!-- JS Libraries: Bootstrap 5, SheetJS (for Excel Export), Tabulator JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.sheetjs.com/xlsx-0.20.1/package/dist/xlsx.full.min.js"></script>
<script src="https://unpkg.com/tabulator-tables@6.2.1/dist/js/tabulator.min.js"></script>

<script>
'use strict';

let table = null;
let deleteTargetId = null;

/** Format angka ke Rupiah */
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

/** Hitung & Update KPI Cards dari data tabel */
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

/** Custom filter function untuk Tabulator berdasarkan tanggal mulai & selesai */
function customDateFilter(data) {
  const startDate = document.getElementById('filterStartDate').value;
  const endDate   = document.getElementById('filterEndDate').value;
  const rowDate   = data.tanggal; // format: YYYY-MM-DD

  if (!rowDate) return false;
  if (startDate && rowDate < startDate) return false;
  if (endDate && rowDate > endDate) return false;
  return true;
}

/** Terapkan filter tanggal & update KPI cards */
function applyFilter() {
  if (!table) return;

  const startDate  = document.getElementById('filterStartDate').value;
  const endDate    = document.getElementById('filterEndDate').value;
  const textSearch = document.getElementById('filterInput').value.trim();

  // Reset filter terdahulu
  table.clearFilter();

  // Filter teks pencarian jika ada
  if (textSearch) {
    table.addFilter('tanggal', 'like', textSearch);
  }

  // Filter rentang tanggal jika diset
  if (startDate || endDate) {
    table.addFilter(customDateFilter);
  }

  // Update KPI berdasarkan baris data yang lolos filter
  setTimeout(() => {
    const filteredData = table.getData("active");
    updateKPI(filteredData);
  }, 60);
}

/** Handler Preset Tanggal Cepat */
function applyPreset(preset) {
  const now = new Date();
  const pad = n => String(n).padStart(2, '0');
  const fmtDate = d => `${d.getFullYear()}-${pad(d.getMonth() + 1)}-${pad(d.getDate())}`;

  let start = '';
  let end = '';

  if (preset === 'today') {
    start = end = fmtDate(now);
  } else if (preset === 'yesterday') {
    const y = new Date(now);
    y.setDate(y.getDate() - 1);
    start = end = fmtDate(y);
  } else if (preset === 'this_month') {
    const firstDay = new Date(now.getFullYear(), now.getMonth(), 1);
    start = fmtDate(firstDay);
    end = fmtDate(now);
  } else if (preset === 'last_month') {
    const firstDayLastMonth = new Date(now.getFullYear(), now.getMonth() - 1, 1);
    const lastDayLastMonth  = new Date(now.getFullYear(), now.getMonth(), 0);
    start = fmtDate(firstDayLastMonth);
    end   = fmtDate(lastDayLastMonth);
  } else if (preset === 'this_year') {
    const firstDayYear = new Date(now.getFullYear(), 0, 1);
    start = fmtDate(firstDayYear);
    end   = fmtDate(now);
  } else if (preset === 'all') {
    start = '';
    end   = '';
  }

  document.getElementById('filterStartDate').value = start;
  document.getElementById('filterEndDate').value   = end;
  applyFilter();
}

/** Inisialisasi Tabulator JS Table */
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
      {
        title: 'No',
        formatter: 'rownum',
        width: 60,
        headerHozAlign: 'center',
        hozAlign: 'center',
        headerSort: false
      },
      {
        title: 'Tanggal Laporan',
        field: 'tanggal',
        width: 190,
        headerHozAlign: 'left',
        formatter: function(cell) {
          const val = cell.getValue();
          return `<span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-2 py-1 badge-date">
                    <i class="bi bi-calendar3 me-1"></i> ${fmtTglIndo(val)}
                  </span>`;
        }
      },
      {
        title: 'Total Kas Awal',
        field: 'tot_kas_awal',
        headerHozAlign: 'right',
        hozAlign: 'right',
        formatter: cell => fmt(cell.getValue())
      },
      {
        title: 'Total Pemasukan',
        field: 'tot_pemasukan',
        headerHozAlign: 'right',
        hozAlign: 'right',
        formatter: cell => `<span class="fw-bold text-success">${fmt(cell.getValue())}</span>`
      },
      {
        title: 'Total Pengeluaran',
        field: 'tot_pengeluaran',
        headerHozAlign: 'right',
        hozAlign: 'right',
        formatter: cell => `<span class="fw-bold text-danger">${fmt(cell.getValue())}</span>`
      },
      {
        title: 'Saldo Apotek',
        field: 'a_saldo',
        headerHozAlign: 'right',
        hozAlign: 'right',
        formatter: cell => `<span class="fw-semibold text-secondary">${fmt(cell.getValue())}</span>`
      },
      {
        title: 'Saldo Pendaftaran',
        field: 'p_saldo',
        headerHozAlign: 'right',
        hozAlign: 'right',
        formatter: cell => `<span class="fw-semibold text-secondary">${fmt(cell.getValue())}</span>`
      },
      {
        title: 'Saldo Keseluruhan',
        field: 'grand_saldo',
        headerHozAlign: 'right',
        hozAlign: 'right',
        formatter: function(cell) {
          const val = cell.getValue();
          const colorClass = val < 0 ? 'text-danger' : 'text-primary';
          return `<span class="fw-bold fs-6 ${colorClass}">${fmt(val)}</span>`;
        }
      },
      {
        title: 'Aksi',
        field: 'id',
        width: 140,
        headerHozAlign: 'center',
        hozAlign: 'center',
        headerSort: false,
        formatter: function(cell) {
          const rowData = cell.getRow().getData();
          const tgl = rowData.tanggal;
          const id = rowData.id;
          return `
            <div class="btn-group btn-group-sm">
              <a href="laporan.php?tanggal=${tgl}" class="btn btn-outline-primary" title="Buka / Edit Laporan">
                <i class="bi bi-pencil-square"></i> Buka
              </a>
              <button type="button" class="btn btn-outline-danger btn-del" data-id="${id}" data-tgl="${tgl}" title="Hapus Laporan">
                <i class="bi bi-trash3"></i>
              </button>
            </div>
          `;
        }
      }
    ]
  });

  // Tabulator event: saat data selesai difilter, update KPI cards
  table.on("dataFiltered", function(filters, rows) {
    const activeData = rows.map(r => r.getData());
    updateKPI(activeData);
  });

  // Event listener hapus di dalam tabel
  document.getElementById('riwayatTable').addEventListener('click', function(e) {
    const btn = e.target.closest('.btn-del');
    if (btn) {
      deleteTargetId = btn.dataset.id;
      document.getElementById('delTanggalText').textContent = fmtTglIndo(btn.dataset.tgl);
      const modal = new bootstrap.Modal(document.getElementById('modalHapus'));
      modal.show();
    }
  });
}

document.addEventListener('DOMContentLoaded', () => {
  initTabulator();

  // Tombol Terapkan Filter Tanggal
  document.getElementById('btnApplyDateFilter').addEventListener('click', function() {
    applyFilter();
  });

  // Tombol Reset Filter Tanggal
  document.getElementById('btnResetDateFilter').addEventListener('click', function() {
    document.getElementById('filterStartDate').value = '';
    document.getElementById('filterEndDate').value   = '';
    document.getElementById('filterInput').value     = '';
    table.clearFilter();
    updateKPI(table.getData());
    showToast('Filter berhasil di-reset', 'bg-secondary');
  });

  // Filter pencarian teks
  document.getElementById('filterInput').addEventListener('keyup', function(e) {
    applyFilter();
  });

  // Quick Preset Dropdown Items
  document.querySelectorAll('.preset-date').forEach(item => {
    item.addEventListener('click', function(e) {
      e.preventDefault();
      const preset = this.dataset.preset;
      applyPreset(preset);
    });
  });

  // Export CSV
  document.getElementById('btnExportCsv').addEventListener('click', function() {
    table.download('csv', `Riwayat_Laporan_${new Date().toISOString().split('T')[0]}.csv`);
  });

  // Export XLSX
  document.getElementById('btnExportXlsx').addEventListener('click', function() {
    table.download('xlsx', `Riwayat_Laporan_${new Date().toISOString().split('T')[0]}.xlsx`, { sheetName: 'Riwayat Laporan' });
  });

  // Refresh
  document.getElementById('btnRefresh').addEventListener('click', function() {
    table.setData('api/list.php?full=1');
    showToast('Data berhasil diperbarui', 'bg-info');
  });

  // Confirm delete
  document.getElementById('btnConfirmDelete').addEventListener('click', async function() {
    if (!deleteTargetId) return;

    try {
      const res = await fetch('api/delete.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id: deleteTargetId })
      });
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
      const modalEl = document.getElementById('modalHapus');
      const modal = bootstrap.Modal.getInstance(modalEl);
      if (modal) modal.hide();
    }
  });
});
</script>

</body>
</html>
