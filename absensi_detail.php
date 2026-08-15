<?php
// absensi_detail.php – Detail Kalender Audit Kehadiran per Karyawan dengan DataTables | AdminLTE 4
declare(strict_types=1);
?>
<!DOCTYPE html>
<html lang="id" data-bs-theme="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Detail Absensi Karyawan | Klinik Millennia</title>

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.1/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0-rc4/dist/css/adminlte.min.css">
  <!-- DataTables Bootstrap 5 CSS -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
  <link rel="stylesheet" href="assets/style.css">

  <style>
    @media print {
      .no-print, .app-sidebar, .app-header, .app-footer, .btn, .breadcrumb, .dataTables_filter, .dataTables_length, .dataTables_info, .dataTables_paginate {
        display: none !important;
      }
      .app-wrapper { margin: 0 !important; padding: 0 !important; }
      .app-main { margin: 0 !important; padding: 0 !important; }
      .print-header { display: block !important; }
      .card { border: none !important; box-shadow: none !important; }
      .table { width: 100% !important; font-size: 10pt !important; }
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
            <h3 class="mb-0 fw-bold"><i class="bi bi-calendar-range text-primary me-2"></i>Detail Audit Kalender Kerja</h3>
          </div>
          <div class="col-sm-6 text-end">
            <ol class="breadcrumb float-sm-end mb-0">
              <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="absensi_rekap.php">Rekap Absensi</a></li>
              <li class="breadcrumb-item active">Detail Karyawan</li>
            </ol>
          </div>
        </div>
      </div>
    </div>

    <div class="app-content">
      <div class="container-fluid">

        <!-- TOMBOL KEMBALI & EMPLOYEE INFO -->
        <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2 no-print">
          <div class="d-flex align-items-center gap-3">
            <a href="absensi_rekap.php" class="btn btn-outline-secondary btn-sm" id="btnBack">
              <i class="bi bi-arrow-left me-1"></i> Kembali ke Rekap
            </a>
            <div>
              <h4 class="mb-0 fw-bold" id="empName">Memuat...</h4>
              <span class="text-muted small" id="empPosition">-</span>
              <span class="badge bg-primary ms-2" id="empBadge">-</span>
            </div>
          </div>
          <div class="d-flex gap-2">
            <button class="btn btn-primary btn-sm fw-semibold" onclick="window.print()">
              <i class="bi bi-printer me-1"></i> Cetak Kalender
            </button>
          </div>
        </div>

        <!-- PRINT HEADER -->
        <div class="print-header text-center mb-4 pb-2 border-bottom">
          <h4 class="fw-bold mb-1">KLINIK MILLENNIA</h4>
          <h5 class="fw-bold mb-1">DETAIL KALENDER KERJA & ABSENSI KARYAWAN</h5>
          <p class="mb-0" id="printEmpDetail">-</p>
        </div>

        <!-- SUMMARY CARDS -->
        <div class="row g-2 mb-4" id="cardsSummary">
          <div class="col-md-2 col-6">
            <div class="card shadow-sm border-0 text-center py-2" style="background:#e8f4fd;">
              <small class="text-muted fw-semibold" style="font-size: .75rem;">Hari Kerja</small>
              <h4 class="mb-0 fw-bold text-primary" id="cardKerja">0</h4>
            </div>
          </div>
          <div class="col-md-2 col-6">
            <div class="card shadow-sm border-0 text-center py-2" style="background:#fee2e2;">
              <small class="text-muted fw-semibold" style="font-size: .75rem;">Sakit</small>
              <h4 class="mb-0 fw-bold text-danger" id="cardSakit">0</h4>
            </div>
          </div>
          <div class="col-md-2 col-6">
            <div class="card shadow-sm border-0 text-center py-2" style="background:#fef3c7;">
              <small class="text-muted fw-semibold" style="font-size: .75rem;">Izin</small>
              <h4 class="mb-0 fw-bold text-warning text-dark" id="cardIzin">0</h4>
            </div>
          </div>
          <div class="col-md-2 col-6">
            <div class="card shadow-sm border-0 text-center py-2" style="background:#e0e7ff;">
              <small class="text-muted fw-semibold" style="font-size: .75rem;">Cuti</small>
              <h4 class="mb-0 fw-bold text-primary" id="cardCuti">0</h4>
            </div>
          </div>
          <div class="col-md-2 col-6">
            <div class="card shadow-sm border-0 text-center py-2" style="background:#f1f5f9;">
              <small class="text-muted fw-semibold" style="font-size: .75rem;">Total Pengurangan</small>
              <h4 class="mb-0 fw-bold text-danger" id="cardKurang">0</h4>
            </div>
          </div>
          <div class="col-md-2 col-6">
            <div class="card shadow-sm border-0 text-center py-2" style="background:#dcfce7;">
              <small class="text-muted fw-semibold" style="font-size: .75rem;">Total Masuk</small>
              <h4 class="mb-0 fw-bold text-success" id="cardMasuk">0</h4>
            </div>
          </div>
        </div>

        <!-- TABLE CALENDAR AUDIT -->
        <div class="card shadow-sm">
          <div class="card-header bg-white py-2 d-flex justify-content-between align-items-center">
            <h6 class="card-title mb-0 fw-bold"><i class="bi bi-calendar-check text-primary me-2"></i>Rincian Jadwal & Hitungan per Tanggal (DataTables)</h6>
            <span class="badge bg-secondary" id="badgePeriodDetail">Periode: -</span>
          </div>
          <div class="card-body p-3 table-responsive">
            <table class="table table-striped table-bordered table-hover align-middle mb-0" id="tableDetail" style="width:100%">
              <thead class="table-light">
                <tr>
                  <th class="text-center" width="45">No</th>
                  <th width="120">Tanggal</th>
                  <th width="100">Hari</th>
                  <th class="text-center" width="130">Status Jadwal</th>
                  <th>Alasan / Penjelasan Sistem</th>
                  <th class="text-center" width="140">Keterangan Absensi</th>
                  <th class="text-center" width="90">Hitung</th>
                </tr>
              </thead>
              <tbody id="tbodyDetail"></tbody>
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

<script>
let dtDetail = null;
const urlParams = new URLSearchParams(window.location.search);
const employeeId = parseInt(urlParams.get('employee_id') || '0');
const periodId   = parseInt(urlParams.get('period_id') || '0');

if (employeeId <= 0) {
  alert('Karyawan belum dipilih!');
  window.location.href = 'absensi_rekap.php';
}

const dayNames = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

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

async function loadDetail() {
  try {
    const url = `api/absensi_detail.php?employee_id=${employeeId}&period_id=${periodId}`;
    const res = await fetch(url);
    const json = await res.json();

    if (!json.success) {
      alert(json.message);
      return;
    }

    renderDetail(json);
  } catch (err) {
    alert('Gagal memuat rincian: ' + err.message);
  }
}

function renderDetail(data) {
  const emp = data.employee;
  const period = data.period;
  const summary = data.summary;
  const schedule = summary.daily_schedule;
  const secMap = data.security_map || {};
  const csMap = data.cs_map || {};

  document.getElementById('empName').textContent = emp.name;
  document.getElementById('empPosition').textContent = emp.position;
  document.getElementById('empBadge').textContent = emp.schedule_type;
  document.getElementById('badgePeriodDetail').textContent = `${period.name} (${period.start_date} s/d ${period.end_date})`;
  document.getElementById('printEmpDetail').textContent = `Nama: ${emp.name} | Jabatan: ${emp.position} | Jenis: ${emp.schedule_type} | Periode: ${period.start_date} s/d ${period.end_date}`;

  document.getElementById('btnBack').href = `absensi_rekap.php?period_id=${period.id}`;

  // Summary cards
  document.getElementById('cardKerja').textContent = summary.working_days;
  document.getElementById('cardSakit').textContent = summary.sakit;
  document.getElementById('cardIzin').textContent = summary.izin;
  document.getElementById('cardCuti').textContent = summary.cuti;
  document.getElementById('cardKurang').textContent = summary.total_deduction;
  document.getElementById('cardMasuk').textContent = summary.total_masuk;

  const tableData = schedule.map((day, idx) => {
    const d = new Date(day.date + 'T00:00:00');
    const dayName = dayNames[d.getDay()];
    const isWorkDay = day.should_work;
    const hasNote = Boolean(day.note);

    // Schedule status badge
    let statusBadge = '';
    if (emp.schedule_type === 'NORMAL' || emp.schedule_type === 'APOTEKER') {
      statusBadge = isWorkDay 
        ? '<span class="badge bg-success">Kerja</span>' 
        : '<span class="badge bg-secondary">Libur</span>';
    } else if (emp.schedule_type === 'SECURITY') {
      const shift = (day.shift || '').toLowerCase();
      if (shift === 'pagi') statusBadge = '<span class="badge bg-info">Shift Pagi</span>';
      else if (shift === 'malam') statusBadge = '<span class="badge bg-primary">Shift Malam</span>';
      else statusBadge = '<span class="badge bg-secondary">Libur Rotasi</span>';
    } else if (emp.schedule_type === 'CLEANING_SERVICE') {
      if (day.is_my_turn) {
        statusBadge = `<span class="badge bg-success">${emp.name}</span>`;
      } else {
        const assignedName = csMap[day.assigned_to] || 'Libur';
        statusBadge = `<span class="badge bg-secondary">${assignedName}</span>`;
      }
    }

    // Note badge
    let noteBadge = '-';
    if (hasNote) {
      const noteType = day.note.toLowerCase();
      const badgeClass = {
        'sakit': 'bg-danger',
        'izin': 'bg-warning text-dark',
        'cuti': 'bg-primary'
      }[noteType] || 'bg-dark';
      const notesText = day.notes ? `<br><small class="text-muted">(${day.notes})</small>` : '';
      noteBadge = `<span class="badge ${badgeClass}">${noteType.toUpperCase()}</span>${notesText}`;
    }

    // Counted badge
    const countedBadge = (day.counted === 1)
      ? '<span class="text-success fw-bold"><i class="bi bi-check-circle-fill me-1"></i>1</span>'
      : '<span class="text-muted fw-semibold">0</span>';

    return [
      idx + 1,
      `<strong>${day.date}</strong>`,
      dayName,
      statusBadge,
      `<small class="${isWorkDay ? 'fw-semibold text-dark' : 'text-muted'}">${day.reason}</small>`,
      noteBadge,
      countedBadge
    ];
  });

  if (dtDetail) {
    dtDetail.destroy();
    $('#tbodyDetail').empty();
  }

  dtDetail = $('#tableDetail').DataTable({
    data: tableData,
    language: dtIndonesian,
    pageLength: 35,
    order: [[0, 'asc']],
    columnDefs: [
      { targets: [0, 2, 3, 5, 6], className: 'text-center' },
      { targets: [0, 3, 5, 6], orderable: false }
    ],
    responsive: true
  });
}

document.addEventListener('DOMContentLoaded', () => {
  loadDetail();
});
</script>
</body>
</html>
