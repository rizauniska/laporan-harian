<?php
// absensi_pengaturan.php – Pengaturan Jadwal, Rotasi & Periode dengan DataTables | AdminLTE 4
declare(strict_types=1);
?>
<!DOCTYPE html>
<html lang="id" data-bs-theme="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pengaturan Jadwal & Rotasi | Klinik Millennia</title>

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
            <h3 class="mb-0 fw-bold"><i class="bi bi-sliders text-primary me-2"></i>Pengaturan Jadwal, Rotasi & Periode</h3>
          </div>
          <div class="col-sm-6 text-end">
            <ol class="breadcrumb float-sm-end mb-0">
              <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="absensi_rekap.php">Absensi</a></li>
              <li class="breadcrumb-item active">Pengaturan</li>
            </ol>
          </div>
        </div>
      </div>
    </div>

    <div class="app-content">
      <div class="container-fluid">

        <!-- SECTION 1: PERIODE KERJA -->
        <div class="card shadow-sm mb-4">
          <div class="card-header bg-white py-2 d-flex justify-content-between align-items-center">
            <h6 class="card-title mb-0 fw-bold"><i class="bi bi-calendar3 text-primary me-2"></i>Manajemen Periode Kerja (DataTables)</h6>
            <button class="btn btn-primary btn-sm" id="btnOpenModalPeriod"><i class="bi bi-plus-circle me-1"></i> Tambah Periode</button>
          </div>
          <div class="card-body p-3 table-responsive">
            <table class="table table-striped table-bordered table-hover align-middle mb-0" id="tablePeriods" style="width:100%">
              <thead class="table-light">
                <tr>
                  <th class="text-center" width="45">No</th>
                  <th>Nama Periode</th>
                  <th class="text-center" width="140">Tanggal Mulai</th>
                  <th class="text-center" width="140">Tanggal Selesai</th>
                  <th class="text-center" width="110">Status</th>
                  <th class="text-center" width="140">Aksi</th>
                </tr>
              </thead>
              <tbody id="tbodyPeriods"></tbody>
            </table>
          </div>
        </div>

        <div class="row g-4">
          <!-- SECTION 2: SECURITY ROTATION -->
          <div class="col-md-6 col-sm-12">
            <div class="card shadow-sm border-warning" style="border-width: 2px 1px 1px;">
              <div class="card-header bg-warning text-dark fw-bold py-2">
                <i class="bi bi-shield-lock me-2"></i> Konfigurasi Rotasi Security (Pola 3 Hari)
              </div>
              <div class="card-body p-3">
                <div class="alert alert-info py-2 small mb-3">
                  <i class="bi bi-info-circle-fill me-1"></i> Rotasi berjalan <strong>terus-menerus tanpa henti</strong> (2 hari kerja & 1 hari libur). Hari Minggu dan Libur Nasional tidak menghentikan rotasi.
                </div>
                <form id="formSecurity">
                  <div class="mb-3">
                    <label class="form-label fw-bold small mb-1">Tanggal Referensi (Hari ke-1 / Index 0)</label>
                    <input type="date" class="form-control form-control-sm" id="secRefDate" required value="2026-07-27" style="max-width: 220px;">
                  </div>

                  <div class="table-responsive">
                    <table class="table table-bordered table-sm align-middle mb-3">
                      <thead class="table-light">
                        <tr>
                          <th class="text-center" width="70">Pola</th>
                          <th>Shift Pagi</th>
                          <th>Shift Malam</th>
                          <th>Libur Rotasi</th>
                        </tr>
                      </thead>
                      <tbody id="tbodySecPattern">
                        <!-- Filled by JS -->
                      </tbody>
                    </table>
                  </div>
                  <button type="submit" class="btn btn-warning btn-sm fw-bold text-dark w-100">
                    <i class="bi bi-save me-1"></i> Simpan Konfigurasi Security
                  </button>
                </form>
              </div>
            </div>
          </div>

          <!-- SECTION 3: CLEANING SERVICE -->
          <div class="col-md-6 col-sm-12">
            <div class="card shadow-sm border-info" style="border-width: 2px 1px 1px;">
              <div class="card-header bg-info text-dark fw-bold py-2">
                <i class="bi bi-stars me-2"></i> Konfigurasi Giliran Cleaning Service
              </div>
              <div class="card-body p-3">
                <div class="alert alert-info py-2 small mb-3">
                  <i class="bi bi-info-circle-fill me-1"></i> Menggunakan <strong>Working-Day Sequence</strong>. Hari Minggu dan Libur Nasional tidak dihitung dan tidak memajukan giliran.
                </div>
                <form id="formCS">
                  <div class="mb-3">
                    <label class="form-label fw-bold small mb-1">Tanggal Referensi Mulai Giliran</label>
                    <input type="date" class="form-control form-control-sm" id="csRefDate" required value="2026-07-27" style="max-width: 220px;">
                  </div>

                  <div class="table-responsive">
                    <table class="table table-bordered table-sm align-middle mb-3">
                      <thead class="table-light">
                        <tr>
                          <th class="text-center" width="70">Urutan</th>
                          <th>Karyawan CS</th>
                          <th class="text-center" width="130">Jumlah Hari</th>
                        </tr>
                      </thead>
                      <tbody id="tbodyCSSlot">
                        <!-- Filled by JS -->
                      </tbody>
                    </table>
                  </div>
                  <button type="submit" class="btn btn-info btn-sm fw-bold text-dark w-100">
                    <i class="bi bi-save me-1"></i> Simpan Konfigurasi Cleaning Service
                  </button>
                </form>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </main>

  <!-- MODAL FORM PERIODE -->
  <div class="modal fade" id="modalPeriod" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
      <div class="modal-content">
        <form id="formPeriod">
          <div class="modal-header bg-primary text-white py-2">
            <h6 class="modal-title fw-bold"><i class="bi bi-calendar3 me-1"></i> Tambah Periode Kerja</h6>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body p-3">
            <input type="hidden" id="pId" value="0">
            <div class="mb-2">
              <label class="form-label fw-bold small mb-1">Nama Periode</label>
              <input type="text" class="form-control form-control-sm" id="pName" required placeholder="Contoh: 27 Juli - 26 Agustus 2026">
            </div>
            <div class="mb-2">
              <label class="form-label fw-bold small mb-1">Tanggal Mulai</label>
              <input type="date" class="form-control form-control-sm" id="pStart" required>
            </div>
            <div class="mb-2">
              <label class="form-label fw-bold small mb-1">Tanggal Selesai</label>
              <input type="date" class="form-control form-control-sm" id="pEnd" required>
            </div>
            <div class="mb-2">
              <label class="form-label fw-bold small mb-1">Status</label>
              <select class="form-select form-select-sm" id="pStatus">
                <option value="active">Aktif (Berjalan)</option>
                <option value="closed">Closed (Arsip)</option>
              </select>
            </div>
          </div>
          <div class="modal-footer py-2">
            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary btn-sm fw-semibold">Simpan Periode</button>
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
let dtPeriods = null;
let securityEmps = [];
let csEmps = [];
const modalPeriodEl = document.getElementById('modalPeriod');
const modalPeriod = new bootstrap.Modal(modalPeriodEl);

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

async function loadSettings() {
  try {
    const res = await fetch('api/absensi_pengaturan.php');
    const json = await res.json();
    if (!json.success) return;

    securityEmps = json.security_emps || [];
    csEmps = json.cs_emps || [];

    renderSecurityForm(json.security_config);
    renderCSForm(json.cs_config);
  } catch (err) {
    console.error(err);
  }
}

function renderSecurityForm(config) {
  document.getElementById('secRefDate').value = config.reference_date || '2026-07-27';
  const pattern = config.pattern || [
    { pagi: 0, malam: 0, libur: 0 },
    { pagi: 0, malam: 0, libur: 0 },
    { pagi: 0, malam: 0, libur: 0 },
  ];

  let html = '';
  for (let i = 0; i < 3; i++) {
    const row = pattern[i] || { pagi: 0, malam: 0, libur: 0 };
    html += `
      <tr>
        <td class="text-center fw-bold small">Hari ${i + 1}</td>
        <td>
          <select class="form-select form-select-sm sec-pagi" required>
            ${renderEmpOptions(securityEmps, row.pagi)}
          </select>
        </td>
        <td>
          <select class="form-select form-select-sm sec-malam" required>
            ${renderEmpOptions(securityEmps, row.malam)}
          </select>
        </td>
        <td>
          <select class="form-select form-select-sm sec-libur" required>
            ${renderEmpOptions(securityEmps, row.libur)}
          </select>
        </td>
      </tr>
    `;
  }
  document.getElementById('tbodySecPattern').innerHTML = html;
}

function renderCSForm(config) {
  document.getElementById('csRefDate').value = config.reference_date || '2026-07-27';
  const sequence = config.sequence || [
    { employee_id: 0, days: 2 },
    { employee_id: 0, days: 2 },
  ];

  let html = '';
  const count = Math.max(sequence.length, csEmps.length || 2);
  for (let i = 0; i < count; i++) {
    const slot = sequence[i] || { employee_id: 0, days: 2 };
    html += `
      <tr>
        <td class="text-center fw-bold small">Giliran ${i + 1}</td>
        <td>
          <select class="form-select form-select-sm cs-emp" required>
            ${renderEmpOptions(csEmps, slot.employee_id)}
          </select>
        </td>
        <td>
          <div class="input-group input-group-sm">
            <input type="number" class="form-control text-center cs-days" value="${slot.days || 2}" min="1" max="30" required>
            <span class="input-group-text">Hari</span>
          </div>
        </td>
      </tr>
    `;
  }
  document.getElementById('tbodyCSSlot').innerHTML = html;
}

function renderEmpOptions(emps, selectedId) {
  let opts = '<option value="">-- Pilih --</option>';
  emps.forEach(e => {
    opts += `<option value="${e.id}" ${e.id == selectedId ? 'selected' : ''}>${e.name}</option>`;
  });
  return opts;
}

// Save Security
document.getElementById('formSecurity').addEventListener('submit', async (e) => {
  e.preventDefault();
  const pattern = [];
  const rows = document.querySelectorAll('#tbodySecPattern tr');
  rows.forEach(r => {
    pattern.push({
      pagi: parseInt(r.querySelector('.sec-pagi').value) || 0,
      malam: parseInt(r.querySelector('.sec-malam').value) || 0,
      libur: parseInt(r.querySelector('.sec-libur').value) || 0,
    });
  });

  const payload = {
    type: 'security',
    reference_date: document.getElementById('secRefDate').value,
    pattern: pattern,
  };

  try {
    const res = await fetch('api/absensi_pengaturan.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(payload)
    });
    const json = await res.json();
    alert(json.message);
  } catch (err) {
    alert('Error: ' + err.message);
  }
});

// Save CS
document.getElementById('formCS').addEventListener('submit', async (e) => {
  e.preventDefault();
  const sequence = [];
  const rows = document.querySelectorAll('#tbodyCSSlot tr');
  rows.forEach(r => {
    sequence.push({
      employee_id: parseInt(r.querySelector('.cs-emp').value) || 0,
      days: parseInt(r.querySelector('.cs-days').value) || 2,
    });
  });

  const payload = {
    type: 'cleaning_service',
    reference_date: document.getElementById('csRefDate').value,
    sequence: sequence,
  };

  try {
    const res = await fetch('api/absensi_pengaturan.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(payload)
    });
    const json = await res.json();
    alert(json.message);
  } catch (err) {
    alert('Error: ' + err.message);
  }
});

// Periods CRUD
async function loadPeriods() {
  try {
    const res = await fetch('api/absensi_periode.php');
    const json = await res.json();
    if (!json.success) return;

    const tableData = json.data.map((p, idx) => {
      const statusBadge = p.status === 'active' 
        ? '<span class="badge bg-success"><i class="bi bi-check-circle me-1"></i> Aktif</span>' 
        : '<span class="badge bg-secondary">Closed</span>';

      const activateBtn = p.status !== 'active'
        ? `<button class="btn btn-xs btn-outline-success py-0 px-2 me-1" onclick="activatePeriod(${p.id})"><i class="bi bi-check2"></i> Aktifkan</button>`
        : '';

      return [
        idx + 1,
        `<strong>${p.name}</strong>`,
        p.start_date,
        p.end_date,
        statusBadge,
        `${activateBtn}<button class="btn btn-xs btn-outline-danger py-0 px-2" onclick="deletePeriod(${p.id})"><i class="bi bi-trash"></i></button>`
      ];
    });

    if (dtPeriods) {
      dtPeriods.destroy();
      $('#tbodyPeriods').empty();
    }

    dtPeriods = $('#tablePeriods').DataTable({
      data: tableData,
      language: dtIndonesian,
      pageLength: 10,
      order: [[2, 'desc']],
      columnDefs: [
        { targets: [0, 2, 3, 4, 5], className: 'text-center' },
        { targets: [0, 5], orderable: false }
      ],
      responsive: true
    });
  } catch (err) {
    console.error(err);
  }
}

document.getElementById('btnOpenModalPeriod').addEventListener('click', () => {
  document.getElementById('formPeriod').reset();
  modalPeriod.show();
});

document.getElementById('formPeriod').addEventListener('submit', async (e) => {
  e.preventDefault();
  const payload = {
    id: parseInt(document.getElementById('pId').value) || 0,
    name: document.getElementById('pName').value.trim(),
    start_date: document.getElementById('pStart').value,
    end_date: document.getElementById('pEnd').value,
    status: document.getElementById('pStatus').value,
  };

  try {
    const res = await fetch('api/absensi_periode.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(payload)
    });
    const json = await res.json();
    if (json.success) {
      modalPeriod.hide();
      loadPeriods();
    } else {
      alert('Gagal: ' + json.message);
    }
  } catch (err) {
    alert('Error: ' + err.message);
  }
});

async function activatePeriod(id) {
  try {
    const res = await fetch('api/absensi_periode.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ action: 'set_active', id: id })
    });
    const json = await res.json();
    if (json.success) loadPeriods();
  } catch (err) {
    alert('Error: ' + err.message);
  }
}

async function deletePeriod(id) {
  if (!confirm('Yakin ingin menghapus periode ini?')) return;
  try {
    const res = await fetch('api/absensi_periode.php', {
      method: 'DELETE',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ id: id })
    });
    const json = await res.json();
    if (json.success) loadPeriods();
  } catch (err) {
    alert('Error: ' + err.message);
  }
}

document.addEventListener('DOMContentLoaded', () => {
  loadSettings();
  loadPeriods();
});
</script>
</body>
</html>
