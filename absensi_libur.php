<?php
// absensi_libur.php – Master Hari Libur Nasional dengan DataTables | AdminLTE 4
declare(strict_types=1);
?>
<!DOCTYPE html>
<html lang="id" data-bs-theme="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Hari Libur Nasional | Klinik Millennia</title>

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
        <div class="row align-items-center">
          <div class="col-sm-6">
            <h3 class="mb-0 fw-bold"><i class="bi bi-calendar-event text-primary me-2"></i>Hari Libur Nasional Resmi</h3>
          </div>
          <div class="col-sm-6 text-end">
            <ol class="breadcrumb float-sm-end mb-0">
              <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="absensi_rekap.php">Absensi</a></li>
              <li class="breadcrumb-item active">Hari Libur</li>
            </ol>
          </div>
        </div>
      </div>
    </div>

    <div class="app-content">
      <div class="container-fluid">

        <div class="row g-4">
          <!-- FORM TAMBAH -->
          <div class="col-md-4 col-sm-12">
            <div class="card shadow-sm">
              <div class="card-header bg-white py-2">
                <h6 class="card-title mb-0 fw-bold"><i class="bi bi-plus-circle text-primary me-2"></i>Tambah Hari Libur</h6>
              </div>
              <div class="card-body p-3">
                <form id="formHoliday">
                  <input type="hidden" id="holId" value="0">
                  <div class="mb-3">
                    <label class="form-label fw-bold small mb-1" for="holDate">Tanggal</label>
                    <input type="date" class="form-control form-control-sm" id="holDate" required>
                  </div>
                  <div class="mb-3">
                    <label class="form-label fw-bold small mb-1" for="holName">Nama Hari Libur</label>
                    <input type="text" class="form-control form-control-sm" id="holName" required placeholder="Contoh: HUT Kemerdekaan RI">
                  </div>
                  <div class="mb-3">
                    <label class="form-label fw-bold small mb-1" for="holDesc">Keterangan (Opsional)</label>
                    <textarea class="form-control form-control-sm" id="holDesc" rows="2" placeholder="Catatan..."></textarea>
                  </div>
                  <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="holActive" checked>
                    <label class="form-check-label small fw-semibold" for="holActive">Status Aktif</label>
                  </div>
                  <button type="submit" class="btn btn-primary btn-sm w-100 fw-semibold">
                    <i class="bi bi-save me-1"></i> Simpan Hari Libur
                  </button>
                </form>
              </div>
            </div>
          </div>

          <!-- DAFTAR HARI LIBUR -->
          <div class="col-md-8 col-sm-12">
            <div class="card shadow-sm">
              <div class="card-header bg-white py-2">
                <h6 class="card-title mb-0 fw-bold"><i class="bi bi-list-check text-primary me-2"></i>Daftar Hari Libur Nasional (DataTables)</h6>
              </div>
              <div class="card-body p-3 table-responsive">
                <table class="table table-striped table-bordered table-hover align-middle mb-0" id="tableHolidays" style="width:100%">
                  <thead class="table-light">
                    <tr>
                      <th class="text-center" width="45">No</th>
                      <th width="120">Tanggal</th>
                      <th width="100">Hari</th>
                      <th>Nama Libur</th>
                      <th>Keterangan</th>
                      <th class="text-center" width="80">Status</th>
                      <th class="text-center" width="70">Aksi</th>
                    </tr>
                  </thead>
                  <tbody id="tbodyHolidays"></tbody>
                </table>
              </div>
            </div>
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
let dtHolidays = null;
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

async function loadHolidays() {
  try {
    const res = await fetch('api/absensi_libur.php');
    const json = await res.json();

    if (!json.success) {
      alert(json.message);
      return;
    }

    renderHolidaysTable(json.data);
  } catch (err) {
    alert('Gagal memuat data: ' + err.message);
  }
}

function renderHolidaysTable(data) {
  const tableData = data.map((h, idx) => {
    const d = new Date(h.date + 'T00:00:00');
    const dayName = dayNames[d.getDay()];
    const statusBadge = h.active == 1 
      ? '<span class="badge bg-success">Aktif</span>' 
      : '<span class="badge bg-secondary">Nonaktif</span>';

    return [
      idx + 1,
      `<strong>${h.date}</strong>`,
      dayName,
      h.name,
      `<small class="text-muted">${h.description || '-'}</small>`,
      statusBadge,
      `<button class="btn btn-xs btn-outline-danger py-0 px-2" onclick="deleteHoliday(${h.id}, '${h.name}')" title="Hapus"><i class="bi bi-trash"></i></button>`
    ];
  });

  if (dtHolidays) {
    dtHolidays.destroy();
    $('#tbodyHolidays').empty();
  }

  dtHolidays = $('#tableHolidays').DataTable({
    data: tableData,
    language: dtIndonesian,
    pageLength: 20,
    order: [[1, 'asc']],
    columnDefs: [
      { targets: 0, className: 'text-center', orderable: false, searchable: false },
      { targets: [2, 5, 6], className: 'text-center' },
      { targets: [6], orderable: false }
    ],
    responsive: true
  });

  dtHolidays.on('draw.dt', function () {
    const start = dtHolidays.page.info().start;
    dtHolidays.column(0, { page: 'current' }).nodes().each(function (cell, i) {
      cell.innerHTML = start + i + 1;
    });
  }).draw();
}

// Save holiday form
document.getElementById('formHoliday').addEventListener('submit', async (e) => {
  e.preventDefault();
  const payload = {
    id: parseInt(document.getElementById('holId').value) || 0,
    date: document.getElementById('holDate').value,
    name: document.getElementById('holName').value.trim(),
    description: document.getElementById('holDesc').value.trim(),
    active: document.getElementById('holActive').checked ? 1 : 0,
  };

  try {
    const res = await fetch('api/absensi_libur.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(payload)
    });
    const json = await res.json();

    if (json.success) {
      document.getElementById('formHoliday').reset();
      document.getElementById('holId').value = '0';
      document.getElementById('holActive').checked = true;
      loadHolidays();
    } else {
      alert('Gagal: ' + json.message);
    }
  } catch (err) {
    alert('Error: ' + err.message);
  }
});

// Delete holiday
async function deleteHoliday(id, name) {
  if (!confirm(`Hapus hari libur "${name}"?`)) return;
  try {
    const res = await fetch('api/absensi_libur.php', {
      method: 'DELETE',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ id: id })
    });
    const json = await res.json();
    if (json.success) {
      loadHolidays();
    } else {
      alert('Gagal: ' + json.message);
    }
  } catch (err) {
    alert('Error: ' + err.message);
  }
}

document.addEventListener('DOMContentLoaded', () => {
  loadHolidays();
});
</script>
</body>
</html>
