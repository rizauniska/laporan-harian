# Kasir Laporan PHP

Aplikasi backend PHP untuk sistem laporan kasir (Apotek & Pendaftaran).

## Persyaratan
1. XAMPP (Apache + MySQL)
2. PHP 8.0 atau lebih baru

## Cara Instalasi (Setup Instructions)

1. Copy folder `kasir-laporan-php` ke direktori `C:\xampp\htdocs\kasir-laporan`.
2. Buka phpMyAdmin di browser Anda: [http://localhost/phpmyadmin](http://localhost/phpmyadmin).
3. Import file `sql/schema.sql` untuk membuat database `kasir_db` beserta tabel-tabel yang dibutuhkan.
4. Buka aplikasi di browser: [http://localhost/kasir-laporan](http://localhost/kasir-laporan).

## Konfigurasi
Konfigurasi database (host, user, password, dbname) dapat diubah pada file `config/Database.php`.

## Fitur
- Pilih tanggal untuk laporan
- Isi form (Kasir Apotek dan Kasir Pendaftaran)
- Auto-save data
- Export data ke Excel
- Cetak ke PDF
