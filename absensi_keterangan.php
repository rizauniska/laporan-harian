<?php
// absensi_keterangan.php – Input Keterangan Absensi (Sakit, Izin, Cuti) dengan DataTables | AdminLTE 4
declare(strict_types=1);
?>
<!DOCTYPE html>
<html lang="id" data-bs-theme="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Keterangan Absensi | Klinik Millennia</title>

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
            <h3 class="mb-0 fw-bold"><i class="bi bi-journal-medical text-primary me-2"></i>Keterangan Absensi (Sakit / Izin / Cuti)</h3>
          </div>
          <div class="col-sm-6 text-end">
            <ol class="breadcrumb float-sm-end mb-0">
              <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="absensi_rekap.php">Absensi</a></li>
              <li class="breadcrumb-item active">Keterangan</li>
            </ol>
          </div>
        </div>
      </div>
    </div>

    <div class="app-content">
      <div class="container-fluid">

        <!-- INPUT FORM CARD -->
        <div class="card shadow-sm mb-4">
          <div class="card-header bg-white py-2">
            <h6 class="card-title mb-0 fw-bold"><i class="bi bi-plus-circle text-primary me-2"></i>Input Keterangan Absensi Baru</h6>
          </div>
          <div class="card-body p-3">
            <form id="formNote">
              <input type="hidden" id="noteId" value="0">
              <div class="row g-3">
                <div class="col-md-4 col-sm-12">
                  <label class="form-label fw-bold small mb-1" for="selectEmployee">Nama Karyawan</label>
                  <select class="form-select form-select-sm" id="selectEmployee" required>
                    <option value="">-- Pilih Karyawan --</option>
                  </select>
                </div>
                <div class="col-md-8 col-sm-12">
                  <label class="form-label fw-bold small mb-1">Jenis Keterangan</label>
                  <div class="d-flex gap-4 pt-1">
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="noteType" id="typeSakit" value="sakit" checked>
                      <label class="form-check-label small fw-bold text-danger" for="typeSakit">
                        <i class="bi bi-heart-pulse me-1"></i> Sakit
                      </label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="noteType" id="typeIzin" value="izin">
                      <label class="form-check-label small fw-bold text-warning text-dark" for="typeIzin">
                        <i class="bi bi-envelope me-1"></i> Izin
                      </label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="noteType" id="typeCuti" value="cuti">
                      <label class="form-check-label small fw-bold text-primary" for="typeCuti">
                        <i class="bi bi-calendar-check me-1"></i> Cuti
                      </label>
                    </div>
                  </div>
                </div>

                <div class="col-md-3 col-sm-6">
                  <label class="form-label fw-bold small mb-1" for="noteStartDate">Tanggal Mulai</label>
                  <input type="date" class="form-control form-control-sm" id="noteStartDate" required>
                </div>
                <div class="col-md-3 col-sm-6">
                  <label class="form-label fw-bold small mb-1" for="noteEndDate">Tanggal Selesai</label>
                  <input type="date" class="form-control form-control-sm" id="noteEndDate" required>
                </div>
                <div class="col-md-4 col-sm-12">
                  <label class="form-label fw-bold small mb-1" for="noteText">Catatan / Alasan</label>
                  <input type="text" class="form-control form-control-sm" id="noteText" placeholder="Contoh: Demam, Cuti Tahunan, dll.">
                </div>
                <div class="col-md-2 col-sm-12 d-flex align-items-end">
                  <button type="submit" class="btn btn-primary btn-sm w-100 fw-semibold">
                    <i class="bi bi-save me-1"></i> Simpan
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>

        <!-- HISTORY TABLE -->
        <div class="card shadow-sm">
          <div class="card-header bg-white py-2">
            <h6 class="card-title mb-0 fw-bold"><i class="bi bi-clock-history text-primary me-2"></i>Daftar Riwayat Keterangan Absensi (DataTables)</h6>
          </div>
          <div class="card-body p-3 table-responsive">
            <table class="table table-striped table-bordered table-hover align-middle mb-0" id="tableNotes" style="width:100%">
              <thead class="table-light">
                <tr>
                  <th class="text-center" width="45">No</th>
                  <th>Nama Karyawan</th>
                  <th>Jabatan</th>
                  <th class="text-center" width="100">Jenis</th>
                  <th class="text-center" width="120">Tanggal Mulai</th>
                  <th class="text-center" width="120">Tanggal Selesai</th>
                  <th class="text-center" width="110">Rentang Hari</th>
                  <th>Catatan</th>
                  <th class="text-center" width="70">Aksi</th>
                </tr>
              </thead>
              <tbody id="tbodyNotes"></tbody>
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
let dtNotes = null;
const todayStr = new Date().toISOString().split('T')[0];
document.getElementById('noteStartDate').value = todayStr;
document.getElementById('noteEndDate').value = todayStr;

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

async function loadNotes() {
  try {
    const res = await fetch('api/absensi_keterangan.php');
    const json = await res.json();

    if (!json.success) {
      alert(json.message);
      return;
    }

    renderEmployeeSelect(json.employees);
    renderNotesTable(json.data);
  } catch (err) {
    alert('Gagal memuat data: ' + err.message);
  }
}

function renderEmployeeSelect(employees) {
  const sel = document.getElementById('selectEmployee');
  sel.innerHTML = '<option value="">-- Pilih Karyawan --</option>';
  employees.forEach(e => {
    const opt = document.createElement('option');
    opt.value = e.id;
    opt.textContent = `${e.name} (${e.position} - ${e.schedule_type})`;
    sel.appendChild(opt);
  });
}

function renderNotesTable(data) {
  const tableData = data.map((n, idx) => {
    const sDate = new Date(n.start_date);
    const eDate = new Date(n.end_date);
    const days = Math.round((eDate - sDate) / (1000 * 60 * 60 * 24)) + 1;

    const badgeClass = {
      'sakit': 'bg-danger',
      'izin': 'bg-warning text-dark',
      'cuti': 'bg-primary'
    }[n.type.toLowerCase()] || 'bg-secondary';

    return [
      idx + 1,
      `<strong>${n.employee_name}</strong>`,
      n.employee_position,
      `<span class="badge ${badgeClass}">${n.type.toUpperCase()}</span>`,
      n.start_date,
      n.end_date,
      `<span class="fw-semibold">${days} Hari</span>`,
      n.notes || '-',
      `<button class="btn btn-xs btn-outline-danger py-0 px-2" onclick="deleteNote(${n.id})" title="Hapus"><i class="bi bi-trash"></i></button>`
    ];
  });

  if (dtNotes) {
    dtNotes.destroy();
    $('#tbodyNotes').empty();
  }

  dtNotes = $('#tableNotes').DataTable({
    data: tableData,
    language: dtIndonesian,
    pageLength: 25,
    order: [[4, 'desc']],
    columnDefs: [
      { targets: 0, className: 'text-center', orderable: false, searchable: false },
      { targets: [3, 4, 5, 6, 8], className: 'text-center' },
      { targets: [8], orderable: false }
    ],
    responsive: true
  });

  dtNotes.on('draw.dt', function () {
    const start = dtNotes.page.info().start;
    dtNotes.column(0, { page: 'current' }).nodes().each(function (cell, i) {
      cell.innerHTML = start + i + 1;
    });
  }).draw();
}

// Auto update end date if start date changed and end date was equal
document.getElementById('noteStartDate').addEventListener('change', (e) => {
  const endEl = document.getElementById('noteEndDate');
  if (!endEl.value || endEl.value < e.target.value) {
    endEl.value = e.target.value;
  }
});

// Save note
document.getElementById('formNote').addEventListener('submit', async (e) => {
  e.preventDefault();
  const typeRadio = document.querySelector('input[name="noteType"]:checked');
  const payload = {
    id: parseInt(document.getElementById('noteId').value) || 0,
    employee_id: parseInt(document.getElementById('selectEmployee').value) || 0,
    type: typeRadio ? typeRadio.value : 'sakit',
    start_date: document.getElementById('noteStartDate').value,
    end_date: document.getElementById('noteEndDate').value,
    notes: document.getElementById('noteText').value.trim(),
  };

  try {
    const res = await fetch('api/absensi_keterangan.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(payload)
    });
    const json = await res.json();

    if (json.success) {
      document.getElementById('noteText').value = '';
      loadNotes();
    } else {
      alert('Gagal: ' + json.message);
    }
  } catch (err) {
    alert('Error: ' + err.message);
  }
});

// Delete note
async function deleteNote(id) {
  if (!confirm('Yakin ingin menghapus catatan absensi ini?')) return;
  try {
    const res = await fetch('api/absensi_keterangan.php', {
      method: 'DELETE',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ id: id })
    });
    const json = await res.json();
    if (json.success) {
      loadNotes();
    } else {
      alert('Gagal: ' + json.message);
    }
  } catch (err) {
    alert('Error: ' + err.message);
  }
}

document.addEventListener('DOMContentLoaded', () => {
  loadNotes();
});
</script>
</body>
</html>
