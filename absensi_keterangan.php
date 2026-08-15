<?php
// absensi_keterangan.php – Input Keterangan Absensi (Sakit, Izin, Cuti) | AdminLTE 4
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
            <h6 class="card-title mb-0 fw-bold"><i class="bi bi-clock-history text-primary me-2"></i>Daftar Riwayat Keterangan Absensi</h6>
          </div>
          <div class="card-body p-0 table-responsive">
            <table class="table table-hover align-middle mb-0" id="tableNotes">
              <thead class="table-light">
                <tr>
                  <th class="text-center" width="50">No</th>
                  <th>Nama Karyawan</th>
                  <th>Jabatan</th>
                  <th class="text-center" width="120">Jenis</th>
                  <th class="text-center" width="130">Tanggal Mulai</th>
                  <th class="text-center" width="130">Tanggal Selesai</th>
                  <th class="text-center" width="110">Rentang Hari</th>
                  <th>Catatan</th>
                  <th class="text-center" width="80">Aksi</th>
                </tr>
              </thead>
              <tbody id="tbodyNotes">
                <tr>
                  <td colspan="9" class="text-center py-4 text-muted">
                    <div class="spinner-border spinner-border-sm text-primary me-1"></div> Memuat data...
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

      </div>
    </div>
  </main>

  <!-- FOOTER -->
  <?php require_once __DIR__ . '/includes/footer.php'; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0-rc4/dist/js/adminlte.min.js"></script>

<script>
// Set default today date
const todayStr = new Date().toISOString().split('T')[0];
document.getElementById('noteStartDate').value = todayStr;
document.getElementById('noteEndDate').value = todayStr;

async function loadNotes() {
  const tbody = document.getElementById('tbodyNotes');
  try {
    const res = await fetch('api/absensi_keterangan.php');
    const json = await res.json();

    if (!json.success) {
      tbody.innerHTML = `<tr><td colspan="9" class="text-center py-4 text-danger">${json.message}</td></tr>`;
      return;
    }

    renderEmployeeSelect(json.employees);
    renderNotesTable(json.data);
  } catch (err) {
    tbody.innerHTML = `<tr><td colspan="9" class="text-center py-4 text-danger">Gagal memuat data: ${err.message}</td></tr>`;
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
  const tbody = document.getElementById('tbodyNotes');
  if (data.length === 0) {
    tbody.innerHTML = `<tr><td colspan="9" class="text-center py-4 text-muted">Belum ada catatan sakit, izin, atau cuti.</td></tr>`;
    return;
  }

  let html = '';
  data.forEach((n, idx) => {
    const sDate = new Date(n.start_date);
    const eDate = new Date(n.end_date);
    const days = Math.round((eDate - sDate) / (1000 * 60 * 60 * 24)) + 1;

    const badgeClass = {
      'sakit': 'bg-danger',
      'izin': 'bg-warning text-dark',
      'cuti': 'bg-primary'
    }[n.type.toLowerCase()] || 'bg-secondary';

    html += `
      <tr>
        <td class="text-center">${idx + 1}</td>
        <td class="fw-bold">${n.employee_name}</td>
        <td>${n.employee_position}</td>
        <td class="text-center"><span class="badge ${badgeClass}">${n.type.toUpperCase()}</span></td>
        <td class="text-center">${n.start_date}</td>
        <td class="text-center">${n.end_date}</td>
        <td class="text-center fw-semibold">${days} Hari</td>
        <td>${n.notes || '-'}</td>
        <td class="text-center">
          <button class="btn btn-xs btn-outline-danger py-0 px-2" onclick="deleteNote(${n.id})" title="Hapus">
            <i class="bi bi-trash"></i>
          </button>
        </td>
      </tr>
    `;
  });

  tbody.innerHTML = html;
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
