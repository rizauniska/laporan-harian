<?php
// absensi_rekap.php – Rekapitulasi Absensi & Hari Kerja dengan DataTables | AdminLTE 4
declare(strict_types=1);
?>
<!DOCTYPE html>
<html lang="id" data-bs-theme="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Rekap Absensi & Hari Kerja | Klinik Millennia</title>
  <meta name="description" content="Perhitungan Hari Kerja & Rekap Absensi Karyawan Klinik Millennia">

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.1/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0-rc4/dist/css/adminlte.min.css">
  <!-- DataTables Bootstrap 5 CSS -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
  <link rel="stylesheet" href="assets/style.css">

  <style>
    @media print {
      .no-print, .app-sidebar, .app-header, .app-footer, .filter-area, .btn, .breadcrumb, .dataTables_filter, .dataTables_length, .dataTables_info, .dataTables_paginate {
        display: none !important;
      }
      .app-wrapper { margin: 0 !important; padding: 0 !important; }
      .app-main { margin: 0 !important; padding: 0 !important; }
      .print-header { display: block !important; }
      .card { border: none !important; box-shadow: none !important; }
      .table { width: 100% !important; font-size: 11pt !important; }
      .table th, .table td { padding: 4px 6px !important; }
    }
    .print-header { display: none; }
  </style>
</head>
<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
<div class="app-wrapper">

  <!-- TOP NAVBAR -->
  <?php require_once __DIR__ . '/includes/navbar.php'; ?>

  <!-- SIDEBAR -->
  <?php require_once __DIR__ . '/includes/sidebar.php'; ?>

  <!-- MAIN CONTENT -->
  <main class="app-main">
    <div class="app-content-header no-print">
      <div class="container-fluid">
        <div class="row align-items-center">
          <div class="col-sm-6">
            <h3 class="mb-0 fw-bold"><i class="bi bi-calendar2-check text-primary me-2"></i>Rekap Absensi & Hari Kerja</h3>
          </div>
          <div class="col-sm-6 text-end">
            <ol class="breadcrumb float-sm-end mb-0">
              <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
              <li class="breadcrumb-item active">Rekap Absensi</li>
            </ol>
          </div>
        </div>
      </div>
    </div>

    <div class="app-content">
      <div class="container-fluid">

        <!-- HEADER CETAK (KHUSUS PRINT) -->
        <div class="print-header text-center mb-4 pb-2 border-bottom">
          <h4 class="fw-bold mb-1">KLINIK MILLENNIA</h4>
          <h5 class="fw-bold mb-1">REKAPITULASI HARI KERJA & ABSENSI KARYAWAN</h5>
          <p class="mb-0 text-muted" id="printPeriodText">Periode: -</p>
        </div>

        <!-- FILTER PERIODE -->
        <div class="card shadow-sm mb-4 filter-area no-print">
          <div class="card-body py-3">
            <div class="row g-2 align-items-center justify-content-between">
              <div class="col-md-6 col-sm-12 d-flex align-items-center gap-2">
                <label class="form-label fw-bold mb-0 text-secondary text-nowrap"><i class="bi bi-calendar3 me-1 text-primary"></i> Periode Kerja:</label>
                <select id="selectPeriod" class="form-select form-select-sm" style="max-width: 320px;">
                  <option value="">Memuat periode...</option>
                </select>
                <button class="btn btn-outline-primary btn-sm" id="btnReload" title="Hitung Ulang">
                  <i class="bi bi-arrow-clockwise"></i>
                </button>
              </div>
              <div class="col-md-6 col-sm-12 text-md-end text-start mt-2 mt-md-0 d-flex gap-2 justify-content-md-end">
                <button class="btn btn-success btn-sm fw-semibold" id="btnExportExcel">
                  <i class="bi bi-file-earmark-excel me-1"></i> Export Excel
                </button>
                <button class="btn btn-primary btn-sm fw-semibold" id="btnPrint">
                  <i class="bi bi-printer me-1"></i> Cetak / PDF
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- SUMMARY STATS CARDS -->
        <div class="row g-3 mb-4 no-print" id="statsContainer" style="display: none;">
          <div class="col-md-3 col-sm-6">
            <div class="card shadow-sm border-0" style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%); color:#fff;">
              <div class="card-body py-3">
                <small class="text-white-50 text-uppercase fw-semibold" style="font-size: .75rem;">Total Karyawan</small>
                <h3 class="mb-0 fw-bold" id="statKaryawan">0</h3>
              </div>
            </div>
          </div>
          <div class="col-md-3 col-sm-6">
            <div class="card shadow-sm border-0" style="background: linear-gradient(135deg, #16a34a 0%, #22c55e 100%); color:#fff;">
              <div class="card-body py-3">
                <small class="text-white-50 text-uppercase fw-semibold" style="font-size: .75rem;">Hari Kerja Normal</small>
                <h3 class="mb-0 fw-bold" id="statNormalDays">0</h3>
              </div>
            </div>
          </div>
          <div class="col-md-3 col-sm-6">
            <div class="card shadow-sm border-0" style="background: linear-gradient(135deg, #2563eb 0%, #3b82f6 100%); color:#fff;">
              <div class="card-body py-3">
                <small class="text-white-50 text-uppercase fw-semibold" style="font-size: .75rem;">Total Hari Masuk</small>
                <h3 class="mb-0 fw-bold" id="statTotalMasuk">0</h3>
              </div>
            </div>
          </div>
          <div class="col-md-3 col-sm-6">
            <div class="card shadow-sm border-0" style="background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%); color:#fff;">
              <div class="card-body py-3">
                <small class="text-white-50 text-uppercase fw-semibold" style="font-size: .75rem;">Total Pengurangan</small>
                <h3 class="mb-0 fw-bold" id="statTotalKurang">0</h3>
              </div>
            </div>
          </div>
        </div>

        <!-- MAIN TABLE CARD -->
        <div class="card shadow-sm">
          <div class="card-header bg-white py-2 d-flex justify-content-between align-items-center">
            <h6 class="card-title mb-0 fw-bold"><i class="bi bi-table text-primary me-2"></i>Tabel Rekapitulasi Kehadiran (DataTables)</h6>
            <span class="badge bg-primary" id="badgePeriodName">Periode: -</span>
          </div>
          <div class="card-body p-3 table-responsive">
            <table class="table table-striped table-bordered table-hover align-middle mb-0" id="tableRekap" style="width:100%">
              <thead class="table-light">
                <tr>
                  <th class="text-center" width="45">No</th>
                  <th>Nama Karyawan</th>
                  <th>Pekerjaan / Jabatan</th>
                  <th class="text-center" width="130">Jenis Jadwal</th>
                  <th class="text-center" width="110">Hari Kerja</th>
                  <th class="text-center text-danger" width="70">Sakit</th>
                  <th class="text-center text-warning" width="70">Izin</th>
                  <th class="text-center text-primary" width="70">Cuti</th>
                  <th class="text-center text-danger" width="100">Total Pengurangan</th>
                  <th class="text-center text-success" width="110">Total Masuk</th>
                  <th class="text-center no-print" width="90">Aksi</th>
                </tr>
              </thead>
              <tbody id="tbodyRekap"></tbody>
              <tfoot class="table-light fw-bold" id="tfootRekap">
                <tr>
                  <td colspan="4" class="text-end">TOTAL KESELURUHAN:</td>
                  <td class="text-center" id="sumKerja">0</td>
                  <td class="text-center text-danger" id="sumSakit">0</td>
                  <td class="text-center text-warning" id="sumIzin">0</td>
                  <td class="text-center text-primary" id="sumCuti">0</td>
                  <td class="text-center text-danger" id="sumKurang">0</td>
                  <td class="text-center text-success fs-6" id="sumMasuk">0</td>
                  <td class="no-print"></td>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>

      </div>
    </div>
  </main>

  <!-- FOOTER -->
  <?php require_once __DIR__ . '/includes/footer.php'; ?>
</div>

<!-- SCRIPTS: jQuery & DataTables Bootstrap 5 -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0-rc4/dist/js/adminlte.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.sheetjs.com/xlsx-0.20.1/package/dist/xlsx.full.min.js"></script>

<script>
let dtRekap = null;
let currentData = null;

const dtIndonesian = {
  search: "Cari:",
  lengthMenu: "Tampilkan _MENU_ data",
  info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
  infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
  infoFiltered: "(disaring dari _MAX_ total data)",
  zeroRecords: "Tidak ada data yang cocok",
  paginate: {
    first: "Pertama",
    last: "Terakhir",
    next: "Berikutnya",
    previous: "Sebelumnya"
  }
};

async function loadRekap(periodId = 0) {
  try {
    const url = periodId > 0 ? `api/absensi_rekap.php?period_id=${periodId}` : 'api/absensi_rekap.php';
    const res = await fetch(url);
    const json = await res.json();

    if (!json.success) {
      alert(json.message);
      return;
    }

    currentData = json;
    renderPeriodsDropdown(json.periods, json.period.id);
    renderTable(json);
  } catch (err) {
    alert('Gagal memuat data: ' + err.message);
  }
}

function renderPeriodsDropdown(periods, activeId) {
  const select = document.getElementById('selectPeriod');
  select.innerHTML = '';
  periods.forEach(p => {
    const opt = document.createElement('option');
    opt.value = p.id;
    opt.textContent = `${p.name} (${p.start_date} s/d ${p.end_date})`;
    if (p.id == activeId) opt.selected = true;
    select.appendChild(opt);
  });
}

function renderTable(data) {
  const period = data.period;
  const summaries = data.summaries;

  const periodText = `${period.name} (${period.start_date} s/d ${period.end_date})`;
  document.getElementById('badgePeriodName').textContent = periodText;
  document.getElementById('printPeriodText').textContent = 'Periode: ' + periodText;

  let totKerja = 0, totSakit = 0, totIzin = 0, totCuti = 0, totKurang = 0, totMasuk = 0;
  let normalWorkingDays = 0;

  const tableData = summaries.map((s, idx) => {
    const emp = s.employee;
    totKerja += s.working_days;
    totSakit += s.sakit;
    totIzin += s.izin;
    totCuti += s.cuti;
    totKurang += s.total_deduction;
    totMasuk += s.total_masuk;

    if (emp.schedule_type === 'NORMAL' && normalWorkingDays === 0) {
      normalWorkingDays = s.working_days;
    }

    const badgeClass = {
      'NORMAL': 'bg-primary',
      'APOTEKER': 'text-white" style="background:#8b5cf6;"',
      'SECURITY': 'bg-warning text-dark',
      'CLEANING_SERVICE': 'bg-info text-dark',
    }[emp.schedule_type] || 'bg-secondary';

    let masukBadge = 'bg-success';
    if (s.total_deduction > 0 && s.total_deduction <= 2) masukBadge = 'bg-warning text-dark';
    else if (s.total_deduction > 2) masukBadge = 'bg-danger';

    return [
      idx + 1,
      `<strong>${emp.name}</strong>`,
      emp.position,
      `<span class="badge ${badgeClass}">${emp.schedule_type}</span>`,
      s.working_days,
      s.sakit > 0 ? `<span class="text-danger fw-bold">${s.sakit}</span>` : '-',
      s.izin > 0 ? `<span class="text-warning fw-bold">${s.izin}</span>` : '-',
      s.cuti > 0 ? `<span class="text-primary fw-bold">${s.cuti}</span>` : '-',
      s.total_deduction > 0 ? `<span class="text-danger fw-bold">${s.total_deduction}</span>` : '0',
      `<span class="badge ${masukBadge} px-2 py-1 fs-6">${s.total_masuk}</span>`,
      `<a href="absensi_detail.php?employee_id=${emp.id}&period_id=${period.id}" class="btn btn-xs btn-outline-primary py-1 px-2 no-print" title="Lihat Kalender Audit"><i class="bi bi-eye"></i> Detail</a>`
    ];
  });

  if (dtRekap) {
    dtRekap.destroy();
    $('#tbodyRekap').empty();
  }

  dtRekap = $('#tableRekap').DataTable({
    data: tableData,
    language: dtIndonesian,
    pageLength: 25,
    order: [[1, 'asc']],
    columnDefs: [
      { targets: 0, className: 'text-center', orderable: false, searchable: false },
      { targets: [3, 4, 5, 6, 7, 8, 9, 10], className: 'text-center' },
      { targets: [10], orderable: false }
    ],
    responsive: true
  });

  dtRekap.on('draw.dt', function () {
    const start = dtRekap.page.info().start;
    dtRekap.column(0, { page: 'current' }).nodes().each(function (cell, i) {
      cell.innerHTML = start + i + 1;
    });
  }).draw();

  document.getElementById('sumKerja').textContent = totKerja;
  document.getElementById('sumSakit').textContent = totSakit;
  document.getElementById('sumIzin').textContent = totIzin;
  document.getElementById('sumCuti').textContent = totCuti;
  document.getElementById('sumKurang').textContent = totKurang;
  document.getElementById('sumMasuk').textContent = totMasuk;

  // Stats cards
  document.getElementById('statKaryawan').textContent = summaries.length;
  document.getElementById('statNormalDays').textContent = (normalWorkingDays || 25) + ' Hari';
  document.getElementById('statTotalMasuk').textContent = totMasuk;
  document.getElementById('statTotalKurang').textContent = totKurang + ' Hari';
  document.getElementById('statsContainer').style.display = '';
}

document.getElementById('selectPeriod').addEventListener('change', (e) => {
  if (e.target.value) {
    loadRekap(parseInt(e.target.value));
  }
});

document.getElementById('btnReload').addEventListener('click', () => {
  const periodId = document.getElementById('selectPeriod').value;
  loadRekap(periodId ? parseInt(periodId) : 0);
});

document.getElementById('btnPrint').addEventListener('click', () => {
  window.print();
});

document.getElementById('btnExportExcel').addEventListener('click', () => {
  if (!currentData) return;
  const period = currentData.period;
  const rows = [
    ['KLINIK MILLENNIA'],
    ['REKAPITULASI HARI KERJA & ABSENSI KARYAWAN'],
    [`Periode: ${period.name} (${period.start_date} s/d ${period.end_date})`],
    [],
    ['No', 'Nama Karyawan', 'Pekerjaan / Jabatan', 'Jenis Jadwal', 'Hari Kerja Seharusnya', 'Sakit', 'Izin', 'Cuti', 'Total Pengurangan', 'Total Masuk']
  ];

  currentData.summaries.forEach((s, idx) => {
    rows.push([
      idx + 1,
      s.employee.name,
      s.employee.position,
      s.employee.schedule_type,
      s.working_days,
      s.sakit,
      s.izin,
      s.cuti,
      s.total_deduction,
      s.total_masuk
    ]);
  });

  const wb = XLSX.utils.book_new();
  const ws = XLSX.utils.aoa_to_sheet(rows);
  ws['!cols'] = [
    { wch: 5 }, { wch: 25 }, { wch: 18 }, { wch: 18 }, { wch: 22 },
    { wch: 8 }, { wch: 8 }, { wch: 8 }, { wch: 18 }, { wch: 14 }
  ];
  XLSX.utils.book_append_sheet(wb, ws, 'Rekap Absensi');
  XLSX.writeFile(wb, `Rekap_Absensi_${period.start_date}_sd_${period.end_date}.xlsx`);
});

document.addEventListener('DOMContentLoaded', () => {
  loadRekap();
});
</script>
</body>
</html>
