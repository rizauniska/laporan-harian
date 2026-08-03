<?php
// includes/navbar.php – Reusable Top Navbar Component
declare(strict_types=1);
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<nav class="app-header navbar navbar-expand bg-white shadow-sm border-bottom">
  <div class="container-fluid">
    <!-- Sidebar Toggle -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
          <i class="bi bi-list fs-5"></i>
        </a>
      </li>
    </ul>
    <!-- Brand -->
    <a href="index.php" class="navbar-brand ms-2 d-none d-md-flex align-items-center gap-2">
      <i class="bi bi-clipboard2-data text-primary fs-5"></i>
      <span class="brand-text text-dark">Kasir Laporan Keuangan</span>
    </a>

    <?php if ($currentPage === 'laporan.php'): ?>
      <!-- Toolbar khusus Laporan.php -->
      <ul class="navbar-nav ms-auto align-items-center gap-1">
        <!-- Tom Select Riwayat -->
        <li class="nav-item" style="min-width:200px">
          <select id="selectLaporan" class="form-select form-select-sm" title="Pilih laporan yang sudah ada">
            <option value="">— Riwayat Laporan —</option>
          </select>
        </li>
        <!-- Tombol Prev / Next -->
        <li class="nav-item">
          <div class="btn-group btn-group-sm">
            <button class="btn btn-outline-secondary" id="btnPrev" title="Laporan sebelumnya" disabled>
              <i class="bi bi-chevron-left"></i>
            </button>
            <button class="btn btn-outline-secondary" id="btnNext" title="Laporan berikutnya" disabled>
              <i class="bi bi-chevron-right"></i>
            </button>
          </div>
        </li>
        <!-- Input Tanggal -->
        <li class="nav-item">
          <input type="date" class="form-control form-control-sm" id="inputTanggal"
                 value="<?= htmlspecialchars($yesterday ?? date('Y-m-d', strtotime('-1 day'))) ?>" style="width:145px">
        </li>
        <!-- Tombol Muat -->
        <li class="nav-item">
          <button class="btn btn-light btn-sm border fw-semibold" id="btnMuat">
            <i class="bi bi-folder2-open"></i> Muat
          </button>
        </li>
        <!-- Tombol Simpan -->
        <li class="nav-item">
          <button class="btn btn-success btn-sm fw-semibold" id="btnSave">
            <i class="bi bi-floppy"></i> Simpan
          </button>
        </li>
        <!-- Tombol Excel -->
        <li class="nav-item">
          <button class="btn btn-warning btn-sm text-dark fw-semibold" id="btnExcel">
            <i class="bi bi-file-earmark-excel"></i> Excel
          </button>
        </li>
        <!-- Tombol Cetak -->
        <li class="nav-item">
          <button class="btn btn-info btn-sm text-dark fw-semibold" id="btnCetak">
            <i class="bi bi-printer"></i> Cetak
          </button>
        </li>
        <!-- Tombol Hapus -->
        <li class="nav-item">
          <button class="btn btn-danger btn-sm" id="btnHapus" title="Hapus laporan ini">
            <i class="bi bi-trash3"></i>
          </button>
        </li>
      </ul>
    <?php elseif ($currentPage === 'index.php'): ?>
      <!-- Toolbar Dashboard -->
      <ul class="navbar-nav ms-auto align-items-center gap-2">
        <li class="nav-item">
          <a href="laporan.php" class="btn btn-primary btn-sm fw-semibold">
            <i class="bi bi-plus-lg me-1"></i> Buat Laporan Baru
          </a>
        </li>
      </ul>
    <?php else: ?>
      <!-- Toolbar Detail Pages -->
      <ul class="navbar-nav ms-auto align-items-center gap-2">
        <li class="nav-item">
          <button class="btn btn-info btn-sm fw-semibold text-dark no-print" id="btnCetak">
            <i class="bi bi-printer me-1"></i> Cetak / PDF
          </button>
        </li>
        <li class="nav-item">
          <a href="index.php" class="btn btn-outline-secondary btn-sm no-print">
            <i class="bi bi-arrow-left me-1"></i> Kembali
          </a>
        </li>
      </ul>
    <?php endif; ?>
  </div>
</nav>
