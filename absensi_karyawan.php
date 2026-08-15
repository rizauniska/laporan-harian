<?php
// absensi_karyawan.php – Master Data Karyawan dengan DataTables | AdminLTE 4
declare(strict_types=1);
?>
<!DOCTYPE html>
<html lang="id" data-bs-theme="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Data Karyawan | Klinik Millennia</title>

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
            <h3 class="mb-0 fw-bold"><i class="bi bi-people text-primary me-2"></i>Master Data Karyawan</h3>
          </div>
          <div class="col-sm-6 text-end">
            <ol class="breadcrumb float-sm-end mb-0">
              <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="absensi_rekap.php">Absensi</a></li>
              <li class="breadcrumb-item active">Karyawan</li>
            </ol>
          </div>
        </div>
      </div>
    </div>

    <div class="app-content">
      <div class="container-fluid">

        <!-- FILTER & ACTION BAR -->
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
          <div class="btn-group btn-group-sm" role="group" id="filterTypeGroup">
            <button class="btn btn-primary" data-type="">Semua</button>
            <button class="btn btn-outline-primary" data-type="NORMAL">Normal</button>
            <button class="btn btn-outline-primary" data-type="APOTEKER">Apoteker</button>
            <button class="btn btn-outline-primary" data-type="SECURITY">Security</button>
            <button class="btn btn-outline-primary" data-type="CLEANING_SERVICE">Cleaning Service</button>
          </div>
          <button class="btn btn-primary btn-sm fw-semibold" id="btnOpenModalAdd">
            <i class="bi bi-person-plus me-1"></i> Tambah Karyawan
          </button>
        </div>

        <!-- TABLE CARD -->
        <div class="card shadow-sm">
          <div class="card-header bg-white py-2">
            <h6 class="card-title mb-0 fw-bold"><i class="bi bi-table text-primary me-2"></i>Daftar Seluruh Karyawan (DataTables)</h6>
          </div>
          <div class="card-body p-3 table-responsive">
            <table class="table table-striped table-bordered table-hover align-middle mb-0" id="tableEmployees" style="width:100%">
              <thead class="table-light">
                <tr>
                  <th class="text-center" width="45">No</th>
                  <th>Nama Karyawan</th>
                  <th>Pekerjaan / Jabatan</th>
                  <th class="text-center" width="150">Jenis Jadwal</th>
                  <th class="text-center" width="90">Status</th>
                  <th class="text-center" width="120">Tanggal Mulai</th>
                  <th class="text-center" width="100">Aksi</th>
                </tr>
              </thead>
              <tbody id="tbodyEmployees"></tbody>
            </table>
          </div>
        </div>

      </div>
    </div>
  </main>

  <!-- MODAL FORM KARYAWAN -->
  <div class="modal fade" id="modalEmployee" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <form id="formEmployee">
          <div class="modal-header bg-primary text-white py-2">
            <h6 class="modal-title fw-bold" id="modalTitle"><i class="bi bi-person-plus me-1"></i> Tambah Karyawan</h6>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body p-3">
            <input type="hidden" id="empId" name="id" value="0">

            <div class="mb-3">
              <label class="form-label fw-bold small mb-1" for="empNameInput">Nama Lengkap</label>
              <input type="text" class="form-control form-control-sm" id="empNameInput" name="name" required placeholder="Contoh: Fitria Mahmudah">
            </div>

            <div class="mb-3">
              <label class="form-label fw-bold small mb-1" for="empPositionInput">Jabatan / Unit</label>
              <input type="text" class="form-control form-control-sm" id="empPositionInput" name="position" required placeholder="Contoh: Apotek / Perawat / Admin">
            </div>

            <div class="mb-3">
              <label class="form-label fw-bold small mb-1" for="empScheduleType">Jenis Jadwal</label>
              <select class="form-select form-select-sm" id="empScheduleType" name="schedule_type" required>
                <option value="NORMAL">NORMAL (Senin–Sabtu, Libur Minggu & Tanggal Merah)</option>
                <option value="APOTEKER">APOTEKER (Jadwal Hari Tertentu)</option>
                <option value="SECURITY">SECURITY (Rotasi 2 Hari Kerja, 1 Libur)</option>
                <option value="CLEANING_SERVICE">CLEANING_SERVICE (2 Hari Bergantian)</option>
              </select>
            </div>

            <!-- Khusus Apoteker: Pilihan Hari Kerja -->
            <div class="mb-3 p-2 rounded bg-light border" id="apotekerWorkdaysBox" style="display: none;">
              <label class="form-label fw-bold small mb-2 text-primary"><i class="bi bi-calendar-week me-1"></i> Hari Kerja Khusus Apoteker:</label>
              <div class="d-flex flex-wrap gap-3">
                <div class="form-check form-check-sm">
                  <input class="form-check-input chk-day" type="checkbox" value="1" id="d1">
                  <label class="form-check-label small" for="d1">Senin</label>
                </div>
                <div class="form-check form-check-sm">
                  <input class="form-check-input chk-day" type="checkbox" value="2" id="d2">
                  <label class="form-check-label small" for="d2">Selasa</label>
                </div>
                <div class="form-check form-check-sm">
                  <input class="form-check-input chk-day" type="checkbox" value="3" id="d3">
                  <label class="form-check-label small" for="d3">Rabu</label>
                </div>
                <div class="form-check form-check-sm">
                  <input class="form-check-input chk-day" type="checkbox" value="4" id="d4">
                  <label class="form-check-label small" for="d4">Kamis</label>
                </div>
                <div class="form-check form-check-sm">
                  <input class="form-check-input chk-day" type="checkbox" value="5" id="d5">
                  <label class="form-check-label small" for="d5">Jumat</label>
                </div>
                <div class="form-check form-check-sm">
                  <input class="form-check-input chk-day" type="checkbox" value="6" id="d6">
                  <label class="form-check-label small" for="d6">Sabtu</label>
                </div>
              </div>
            </div>

            <div class="row g-2 mb-3">
              <div class="col-6">
                <label class="form-label fw-bold small mb-1" for="empStartDate">Tanggal Mulai Bekerja</label>
                <input type="date" class="form-control form-control-sm" id="empStartDate" name="start_date" value="2025-01-01">
              </div>
              <div class="col-6">
                <label class="form-label fw-bold small mb-1" for="empEndDate">Tanggal Selesai (Opsional)</label>
                <input type="date" class="form-control form-control-sm" id="empEndDate" name="end_date">
              </div>
            </div>

            <div class="form-check">
              <input class="form-check-input" type="checkbox" id="empActive" name="active" value="1" checked>
              <label class="form-check-label small fw-semibold" for="empActive">Status Aktif</label>
            </div>
          </div>
          <div class="modal-footer py-2">
            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary btn-sm fw-semibold" id="btnSaveEmp"><i class="bi bi-save me-1"></i> Simpan</button>
          </div>
        </form>
      </div>
    </div>
  </div>

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
let dtEmployees = null;
let employeesList = [];
let selectedScheduleType = '';
const modalEmpEl = document.getElementById('modalEmployee');
const modalEmp = new bootstrap.Modal(modalEmpEl);

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

async function loadEmployees() {
  try {
    const url = selectedScheduleType ? `api/absensi_karyawan.php?schedule_type=${selectedScheduleType}` : 'api/absensi_karyawan.php';
    const res = await fetch(url);
    const json = await res.json();

    if (!json.success) {
      alert(json.message);
      return;
    }

    employeesList = json.data;
    renderEmployeesTable(employeesList);
  } catch (err) {
    alert('Gagal memuat data: ' + err.message);
  }
}

function renderEmployeesTable(data) {
  const tableData = data.map((emp, idx) => {
    const badgeClass = {
      'NORMAL': 'bg-primary',
      'APOTEKER': 'text-white" style="background:#8b5cf6;"',
      'SECURITY': 'bg-warning text-dark',
      'CLEANING_SERVICE': 'bg-info text-dark',
    }[emp.schedule_type] || 'bg-secondary';

    const statusBadge = emp.active == 1 
      ? '<span class="badge bg-success">Aktif</span>' 
      : '<span class="badge bg-secondary">Nonaktif</span>';

    return [
      idx + 1,
      `<strong>${emp.name}</strong>`,
      emp.position,
      `<span class="badge ${badgeClass}">${emp.schedule_type}</span>`,
      statusBadge,
      emp.start_date || '-',
      `<button class="btn btn-xs btn-outline-primary py-0 px-2 me-1" onclick="editEmployee(${emp.id})" title="Edit"><i class="bi bi-pencil"></i></button><button class="btn btn-xs btn-outline-danger py-0 px-2" onclick="deleteEmployee(${emp.id}, '${emp.name}')" title="Hapus"><i class="bi bi-trash"></i></button>`
    ];
  });

  if (dtEmployees) {
    dtEmployees.destroy();
    $('#tbodyEmployees').empty();
  }

  dtEmployees = $('#tableEmployees').DataTable({
    data: tableData,
    language: dtIndonesian,
    pageLength: 25,
    order: [[1, 'asc']],
    columnDefs: [
      { targets: [0, 3, 4, 5, 6], className: 'text-center' },
      { targets: [0, 6], orderable: false }
    ],
    responsive: true
  });
}

// Filter tabs
document.querySelectorAll('#filterTypeGroup button').forEach(btn => {
  btn.addEventListener('click', () => {
    document.querySelectorAll('#filterTypeGroup button').forEach(b => {
      b.classList.remove('btn-primary');
      b.classList.add('btn-outline-primary');
    });
    btn.classList.remove('btn-outline-primary');
    btn.classList.add('btn-primary');

    selectedScheduleType = btn.dataset.type;
    loadEmployees();
  });
});

// Toggle apoteker workdays box
document.getElementById('empScheduleType').addEventListener('change', (e) => {
  document.getElementById('apotekerWorkdaysBox').style.display = e.target.value === 'APOTEKER' ? 'block' : 'none';
});

// Open Add Modal
document.getElementById('btnOpenModalAdd').addEventListener('click', () => {
  document.getElementById('formEmployee').reset();
  document.getElementById('empId').value = '0';
  document.getElementById('modalTitle').innerHTML = '<i class="bi bi-person-plus me-1"></i> Tambah Karyawan';
  document.getElementById('empActive').checked = true;
  document.getElementById('apotekerWorkdaysBox').style.display = 'none';
  document.querySelectorAll('.chk-day').forEach(c => c.checked = false);
  modalEmp.show();
});

// Edit Employee
function editEmployee(id) {
  const emp = employeesList.find(e => e.id == id);
  if (!emp) return;

  document.getElementById('empId').value = emp.id;
  document.getElementById('empNameInput').value = emp.name;
  document.getElementById('empPositionInput').value = emp.position;
  document.getElementById('empScheduleType').value = emp.schedule_type;
  document.getElementById('empStartDate').value = emp.start_date || '';
  document.getElementById('empEndDate').value = emp.end_date || '';
  document.getElementById('empActive').checked = emp.active == 1;

  document.getElementById('modalTitle').innerHTML = '<i class="bi bi-pencil-square me-1"></i> Edit Karyawan';

  if (emp.schedule_type === 'APOTEKER') {
    document.getElementById('apotekerWorkdaysBox').style.display = 'block';
    const workdays = emp.workdays || [1,2,3,4,5,6];
    document.querySelectorAll('.chk-day').forEach(c => {
      c.checked = workdays.includes(parseInt(c.value));
    });
  } else {
    document.getElementById('apotekerWorkdaysBox').style.display = 'none';
  }

  modalEmp.show();
}

// Delete Employee
async function deleteEmployee(id, name) {
  if (!confirm(`Yakin ingin menghapus karyawan "${name}"?`)) return;

  try {
    const res = await fetch('api/absensi_karyawan.php', {
      method: 'DELETE',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ id: id })
    });
    const json = await res.json();
    if (json.success) {
      loadEmployees();
    } else {
      alert('Gagal: ' + json.message);
    }
  } catch (err) {
    alert('Error: ' + err.message);
  }
}

// Save Employee Form
document.getElementById('formEmployee').addEventListener('submit', async (e) => {
  e.preventDefault();
  const scheduleType = document.getElementById('empScheduleType').value;
  const workdays = [];
  if (scheduleType === 'APOTEKER') {
    document.querySelectorAll('.chk-day:checked').forEach(c => workdays.push(parseInt(c.value)));
  }

  const payload = {
    id: parseInt(document.getElementById('empId').value) || 0,
    name: document.getElementById('empNameInput').value.trim(),
    position: document.getElementById('empPositionInput').value.trim(),
    schedule_type: scheduleType,
    start_date: document.getElementById('empStartDate').value || null,
    end_date: document.getElementById('empEndDate').value || null,
    active: document.getElementById('empActive').checked ? 1 : 0,
    workdays: workdays,
  };

  try {
    const res = await fetch('api/absensi_karyawan.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(payload)
    });
    const json = await res.json();

    if (json.success) {
      modalEmp.hide();
      loadEmployees();
    } else {
      alert('Gagal: ' + json.message);
    }
  } catch (err) {
    alert('Error: ' + err.message);
  }
});

document.addEventListener('DOMContentLoaded', () => {
  loadEmployees();
});
</script>
</body>
</html>
