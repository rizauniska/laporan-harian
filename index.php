<?php
// index.php – Dashboard Riwayat Laporan dengan DataTables | AdminLTE 4
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
  <!-- DataTables Bootstrap 5 CSS -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
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

    <div class="app-content">
      <div class="container-fluid">

        <!-- KPI SMALL-BOX CARDS -->
        <div class="row g-3 mb-4">
          <!-- JM dr. Zainuddin -->
          <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6">
            <div class="small-box text-bg-info">
              <div class="inner">
                <h4 id="statJmZainuddin">Rp 0</h4>
                <p>JM dr. Zainuddin (Bersih)</p>
              </div>
              <div class="small-box-icon"><i class="fas fa-user-md"></i></div>
              <a href="jm_dr_zainuddin.php" class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-100-hover">
                Lihat Detail <i class="bi bi-arrow-right-circle ms-1"></i>
              </a>
            </div>
          </div>

          <!-- JM dr. Ali -->
          <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6">
            <div class="small-box bg-smallbox-ali">
              <div class="inner">
                <h4 id="statJmAli">Rp 0</h4>
                <p>JM dr. Ali (Prog + Non)</p>
              </div>
              <div class="small-box-icon"><i class="fas fa-stethoscope"></i></div>
              <a href="jm_dr_ali.php" class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-100-hover">
                Lihat Detail <i class="bi bi-arrow-right-circle ms-1"></i>
              </a>
            </div>
          </div>

          <!-- Fisioterapi -->
          <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6">
            <div class="small-box text-bg-success">
              <div class="inner">
                <h4 id="statFisio">Rp 0</h4>
                <p>Total Pendapatan Fisio</p>
              </div>
              <div class="small-box-icon"><i class="fas fa-heartbeat"></i></div>
              <a href="fisioterapi.php" class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-100-hover">
                Lihat Detail <i class="bi bi-arrow-right-circle ms-1"></i>
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
              <div class="small-box-icon"><i class="fas fa-flask"></i></div>
              <a href="laboratorium.php" class="small-box-footer link-dark link-underline-opacity-0 link-underline-opacity-100-hover">
                Lihat Detail <i class="bi bi-arrow-right-circle ms-1"></i>
              </a>
            </div>
          </div>

          <!-- Pasien Fisio 90rb -->
          <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6">
            <div class="small-box text-bg-primary">
              <div class="inner">
                <h4 id="statFisio90Count">0 Pasien</h4>
                <p>Pasien Fisio 90rb</p>
              </div>
              <div class="small-box-icon"><i class="fas fa-users"></i></div>
              <a href="fisio_90.php" class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-100-hover">
                Lihat Detail <i class="bi bi-arrow-right-circle ms-1"></i>
              </a>
            </div>
          </div>

          <!-- Parkir -->
          <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6">
            <div class="small-box text-bg-secondary">
              <div class="inner">
                <h4 id="statParkir">Rp 0</h4>
                <p>Pendapatan Parkir</p>
              </div>
              <div class="small-box-icon"><i class="fas fa-parking"></i></div>
              <a href="parkir.php" class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-100-hover">
                Lihat Detail <i class="bi bi-arrow-right-circle ms-1"></i>
              </a>
            </div>
          </div>
        </div>

        <!-- FILTER AREA -->
        <div class="card shadow-sm mb-4">
          <div class="card-header py-2">
            <h6 class="card-title mb-0 fw-bold"><i class="bi bi-funnel-fill text-primary me-2"></i>Filter Rentang Tanggal</h6>
            <div class="card-tools"><button type="button" class="btn btn-tool" data-lte-toggle="card-collapse"><i class="bi bi-dash-lg"></i></button></div>
          </div>
          <div class="card-body py-3">
            <div class="row g-2 align-items-end">
              <div class="col-md-3 col-sm-6">
                <label class="form-label small fw-semibold text-muted mb-1" for="filterStartDate"><i class="bi bi-calendar-event me-1 text-primary"></i> Tanggal Mulai</label>
                <input type="date" class="form-control form-control-sm" id="filterStartDate">
              </div>
              <div class="col-md-3 col-sm-6">
                <label class="form-label small fw-semibold text-muted mb-1" for="filterEndDate"><i class="bi bi-calendar-event me-1 text-primary"></i> Tanggal Selesai</label>
                <input type="date" class="form-control form-control-sm" id="filterEndDate">
              </div>
              <div class="col-md-3 col-sm-6 d-flex gap-2">
                <button class="btn btn-primary btn-sm fw-semibold w-100" id="btnApplyDateFilter"><i class="bi bi-funnel-fill me-1"></i> Terapkan</button>
                <button class="btn btn-outline-secondary btn-sm" id="btnResetDateFilter" title="Reset Filter"><i class="bi bi-arrow-counterclockwise"></i></button>
              </div>
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

        <!-- TABLE CARD -->
        <div class="card shadow-sm">
          <div class="card-header py-3 d-flex flex-wrap align-items-center justify-content-between gap-2">
            <h5 class="card-title mb-0 fw-bold"><i class="bi bi-table text-primary me-2"></i>Daftar Riwayat Laporan Keuangan (DataTables)</h5>
            <div class="card-tools d-flex align-items-center gap-2">
              <button class="btn btn-success btn-sm fw-semibold" id="btnExportXlsx" title="Export Excel">
                <i class="bi bi-file-earmark-excel me-1"></i> Export Excel
              </button>
              <button class="btn btn-primary btn-sm fw-semibold" id="btnRefresh">
                <i class="bi bi-arrow-clockwise"></i> Refresh
              </button>
            </div>
          </div>
          <div class="card-body p-3 table-responsive">
            <table class="table table-striped table-bordered table-hover align-middle mb-0" id="riwayatTable" style="width:100%">
              <thead class="table-light">
                <tr>
                  <th class="text-center" width="45">No</th>
                  <th width="180">Tanggal Laporan</th>
                  <th class="text-end" width="130">Total Kas Awal</th>
                  <th class="text-end" width="130">Total Pemasukan</th>
                  <th class="text-end" width="130">Total Pengeluaran</th>
                  <th class="text-end" width="130">Saldo Apotek</th>
                  <th class="text-end" width="130">Saldo Pendaftaran</th>
                  <th class="text-end" width="140">Saldo Keseluruhan</th>
                  <th class="text-center" width="110">Aksi</th>
                </tr>
              </thead>
              <tbody id="tbodyRiwayat"></tbody>
            </table>
          </div>
        </div>

      </div>
    </div>
  </main>

  <!-- FOOTER -->
  <?php require_once __DIR__ . '/includes/footer.php'; ?>
</div>

<!-- MODAL HAPUS -->
<div class="modal fade" id="modalHapus" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-sm">
    <div class="modal-content border-0 shadow">
      <div class="modal-header bg-danger text-white py-2">
        <h6 class="modal-title fw-bold"><i class="bi bi-exclamation-triangle-fill me-2"></i>Hapus Laporan</h6>
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
<div class="position-fixed bottom-0 end-0 p-3" style="z-index:1100">
  <div id="appToast" class="toast align-items-center text-white bg-dark border-0" role="alert">
    <div class="d-flex">
      <div class="toast-body" id="toastMsg"></div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
    </div>
  </div>
</div>

<!-- JS Libraries -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0-rc4/dist/js/adminlte.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.sheetjs.com/xlsx-0.20.1/package/dist/xlsx.full.min.js"></script>

<script>
'use strict';
let dtRiwayat = null;
let allReports = [];
let deleteTargetId = null;

const dtIndonesian = {
  search: "Cari:",
  lengthMenu: "Tampilkan _MENU_ data",
  info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
  infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
  infoFiltered: "(disaring dari _MAX_ total data)",
  zeroRecords: "Tidak ada data yang cocok",
  paginate: { first: "Pertama", last: "Terakhir", next: "Berikutnya", previous: "Sebelumnya" }
};

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
  new bootstrap.Toast(toastEl).show();
}

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

async function loadData() {
  try {
    const res = await fetch('api/list.php?full=1');
    const json = await res.json();
    if (!json.success) return;

    allReports = json.data || [];
    filterAndRender();
  } catch (err) {
    showToast('Gagal memuat data: ' + err.message, 'bg-danger');
  }
}

function filterAndRender() {
  const startDate = document.getElementById('filterStartDate').value;
  const endDate   = document.getElementById('filterEndDate').value;

  const filtered = allReports.filter(r => {
    if (startDate && r.tanggal < startDate) return false;
    if (endDate && r.tanggal > endDate) return false;
    return true;
  });

  updateKPI(filtered);

  const tableData = filtered.map((d, idx) => [
    idx + 1,
    `<span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-2 py-1"><i class="bi bi-calendar3 me-1"></i>${fmtTglIndo(d.tanggal)}</span>`,
    fmt(d.tot_kas_awal),
    `<span class="fw-bold text-success">${fmt(d.tot_pemasukan)}</span>`,
    `<span class="fw-bold text-danger">${fmt(d.tot_pengeluaran)}</span>`,
    `<span class="fw-semibold text-secondary">${fmt(d.a_saldo)}</span>`,
    `<span class="fw-semibold text-secondary">${fmt(d.p_saldo)}</span>`,
    `<span class="fw-bold fs-6 ${d.grand_saldo < 0 ? 'text-danger' : 'text-primary'}">${fmt(d.grand_saldo)}</span>`,
    `<div class="btn-group btn-group-sm"><a href="laporan.php?tanggal=${d.tanggal}" class="btn btn-outline-primary" title="Buka / Edit"><i class="bi bi-pencil-square"></i></a><button type="button" class="btn btn-outline-danger" onclick="confirmDelete(${d.id}, '${d.tanggal}')" title="Hapus"><i class="bi bi-trash3"></i></button></div>`
  ]);

  if (dtRiwayat) {
    dtRiwayat.destroy();
    $('#tbodyRiwayat').empty();
  }

  dtRiwayat = $('#riwayatTable').DataTable({
    data: tableData,
    language: dtIndonesian,
    pageLength: 10,
    order: [[1, 'desc']],
    columnDefs: [
      {
        targets: 0,
        className: 'text-center',
        orderable: false,
        searchable: false,
        render: function (data, type, row, meta) {
          return meta.row + meta.settings._iDisplayStart + 1;
        }
      },
      { targets: [8], className: 'text-center', orderable: false },
      { targets: [2, 3, 4, 5, 6, 7], className: 'text-end' }
    ],
    responsive: true
  });
}

function confirmDelete(id, tanggal) {
  deleteTargetId = id;
  document.getElementById('delTanggalText').textContent = fmtTglIndo(tanggal);
  new bootstrap.Modal(document.getElementById('modalHapus')).show();
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
  filterAndRender();
}

document.addEventListener('DOMContentLoaded', () => {
  loadData();

  document.getElementById('btnApplyDateFilter').addEventListener('click', filterAndRender);

  document.getElementById('btnResetDateFilter').addEventListener('click', function() {
    document.getElementById('filterStartDate').value = '';
    document.getElementById('filterEndDate').value   = '';
    filterAndRender();
    showToast('Filter berhasil di-reset', 'bg-secondary');
  });

  document.querySelectorAll('.preset-date').forEach(item => {
    item.addEventListener('click', function(e) {
      e.preventDefault();
      applyPreset(this.dataset.preset);
    });
  });

  document.getElementById('btnExportXlsx').addEventListener('click', function() {
    if (!allReports.length) return;
    const rows = [
      ['No', 'Tanggal', 'Total Kas Awal', 'Total Pemasukan', 'Total Pengeluaran', 'Saldo Apotek', 'Saldo Pendaftaran', 'Saldo Keseluruhan']
    ];
    allReports.forEach((d, idx) => {
      rows.push([
        idx + 1, d.tanggal, d.tot_kas_awal, d.tot_pemasukan, d.tot_pengeluaran, d.a_saldo, d.p_saldo, d.grand_saldo
      ]);
    });
    const wb = XLSX.utils.book_new();
    const ws = XLSX.utils.aoa_to_sheet(rows);
    XLSX.utils.book_append_sheet(wb, ws, 'Riwayat Laporan');
    XLSX.writeFile(wb, `Riwayat_Laporan_${new Date().toISOString().split('T')[0]}.xlsx`);
  });

  document.getElementById('btnRefresh').addEventListener('click', function() {
    loadData();
    showToast('Data berhasil diperbarui', 'bg-info');
  });

  document.getElementById('btnConfirmDelete').addEventListener('click', async function() {
    if (!deleteTargetId) return;
    try {
      const res  = await fetch('api/delete.php', { method: 'POST', headers: {'Content-Type':'application/json'}, body: JSON.stringify({id: deleteTargetId}) });
      const json = await res.json();
      if (json.success) {
        showToast('✅ Laporan berhasil dihapus', 'bg-success');
        loadData();
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
