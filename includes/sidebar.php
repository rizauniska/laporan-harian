<?php
// includes/sidebar.php – Reusable Sidebar Navigation Component
declare(strict_types=1);
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
  <div class="sidebar-brand">
    <a href="index.php" class="brand-link d-flex align-items-center gap-2 px-3 py-3 text-decoration-none">
      <i class="bi bi-hospital text-primary fs-4"></i>
      <span class="brand-text fw-bold fs-6">Klinik Millennia</span>
    </a>
  </div>
  <div class="sidebar-wrapper">
    <nav class="mt-2">
      <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu">
        <li class="nav-header">MENU UTAMA KASIR</li>
        <li class="nav-item">
          <a href="index.php" class="nav-link <?= $currentPage === 'index.php' ? 'active' : '' ?>">
            <i class="nav-icon bi bi-speedometer2"></i>
            <p>Dashboard</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="laporan.php" class="nav-link <?= $currentPage === 'laporan.php' ? 'active' : '' ?>">
            <i class="nav-icon bi bi-file-earmark-medical"></i>
            <p>Input / Edit Laporan</p>
          </a>
        </li>
        <li class="nav-header">LAPORAN DETAIL KASIR</li>
        <li class="nav-item">
          <a href="jm_dr_zainuddin.php" class="nav-link <?= $currentPage === 'jm_dr_zainuddin.php' ? 'active' : '' ?>">
            <i class="nav-icon fas fa-user-md"></i>
            <p>JM dr. Zainuddin</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="jm_dr_ali.php" class="nav-link <?= $currentPage === 'jm_dr_ali.php' ? 'active' : '' ?>">
            <i class="nav-icon fas fa-stethoscope"></i>
            <p>JM dr. Ali</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="fisioterapi.php" class="nav-link <?= $currentPage === 'fisioterapi.php' ? 'active' : '' ?>">
            <i class="nav-icon fas fa-heartbeat"></i>
            <p>Total Fisioterapi</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="laboratorium.php" class="nav-link <?= $currentPage === 'laboratorium.php' ? 'active' : '' ?>">
            <i class="nav-icon fas fa-flask"></i>
            <p>Total Laboratorium</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="fisio_90.php" class="nav-link <?= $currentPage === 'fisio_90.php' ? 'active' : '' ?>">
            <i class="nav-icon fas fa-users"></i>
            <p>Pasien Fisio 90rb</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="parkir.php" class="nav-link <?= $currentPage === 'parkir.php' ? 'active' : '' ?>">
            <i class="nav-icon fas fa-parking"></i>
            <p>Pendapatan Parkir</p>
          </a>
        </li>

        <li class="nav-header">ABSENSI KARYAWAN</li>
        <li class="nav-item">
          <a href="absensi_rekap.php" class="nav-link <?= in_array($currentPage, ['absensi_rekap.php', 'absensi_detail.php'], true) ? 'active' : '' ?>">
            <i class="nav-icon bi bi-calendar2-check"></i>
            <p>Rekap Absensi</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="absensi_karyawan.php" class="nav-link <?= $currentPage === 'absensi_karyawan.php' ? 'active' : '' ?>">
            <i class="nav-icon bi bi-people"></i>
            <p>Data Karyawan</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="absensi_keterangan.php" class="nav-link <?= $currentPage === 'absensi_keterangan.php' ? 'active' : '' ?>">
            <i class="nav-icon bi bi-journal-medical"></i>
            <p>Keterangan Sakit/Izin</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="absensi_libur.php" class="nav-link <?= $currentPage === 'absensi_libur.php' ? 'active' : '' ?>">
            <i class="nav-icon bi bi-calendar-event"></i>
            <p>Hari Libur Nasional</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="absensi_pengaturan.php" class="nav-link <?= $currentPage === 'absensi_pengaturan.php' ? 'active' : '' ?>">
            <i class="nav-icon bi bi-sliders"></i>
            <p>Pengaturan Jadwal</p>
          </a>
        </li>
      </ul>
    </nav>
  </div>
</aside>
