<?php
// sql/migrate_absensi.php – Setup & Seed Absensi Tables in kasir_db
declare(strict_types=1);

require_once __DIR__ . '/../bootstrap.php';

use App\Config\Database;

try {
    $pdo = Database::getInstance()->getConnection();

    // 1. Create employees table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS employees (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(150) NOT NULL,
            position VARCHAR(100) NOT NULL,
            schedule_type ENUM('NORMAL','APOTEKER','SECURITY','CLEANING_SERVICE') NOT NULL DEFAULT 'NORMAL',
            active TINYINT(1) NOT NULL DEFAULT 1,
            start_date DATE NULL,
            end_date DATE NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ");

    // 2. Create holidays table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS holidays (
            id INT AUTO_INCREMENT PRIMARY KEY,
            date DATE NOT NULL UNIQUE,
            name VARCHAR(150) NOT NULL,
            description TEXT NULL,
            active TINYINT(1) NOT NULL DEFAULT 1,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ");

    // 3. Create attendance_notes table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS attendance_notes (
            id INT AUTO_INCREMENT PRIMARY KEY,
            employee_id INT NOT NULL,
            type ENUM('sakit','izin','cuti') NOT NULL,
            start_date DATE NOT NULL,
            end_date DATE NOT NULL,
            notes TEXT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (employee_id) REFERENCES employees(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ");

    // 4. Create work_periods table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS work_periods (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            start_date DATE NOT NULL,
            end_date DATE NOT NULL,
            status ENUM('active','closed') NOT NULL DEFAULT 'active',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ");

    // 5. Create schedule_settings table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS schedule_settings (
            id INT AUTO_INCREMENT PRIMARY KEY,
            setting_key VARCHAR(100) NOT NULL UNIQUE,
            setting_value JSON NOT NULL,
            description TEXT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ");

    // Seed 19 Employees if empty
    $empCount = (int) $pdo->query("SELECT COUNT(*) FROM employees")->fetchColumn();
    if ($empCount === 0) {
        $employees = [
            ['Fitria Mahmudah', 'Apotek', 'NORMAL'],
            ['Masitah', 'Apotek', 'NORMAL'],
            ['Nur Hikmah', 'Apotek', 'NORMAL'],
            ['Weny', 'Apotek', 'NORMAL'],
            ['Jayadi Irwanto', 'Apotek', 'APOTEKER'],
            ['Zainal Naim', 'Apotek', 'APOTEKER'],
            ['M. Nuzulul Hair', 'Admin', 'NORMAL'],
            ['Riza Maulana', 'Admin', 'NORMAL'],
            ['Bella Novrianda', 'Perawat', 'NORMAL'],
            ['Hidayat', 'Perawat', 'NORMAL'],
            ['Nurlina', 'Perawat', 'NORMAL'],
            ['Supianoor', 'Perawat', 'NORMAL'],
            ['Seteriyo Wardani', 'Fisioterapi', 'NORMAL'],
            ['Mardiansyah', 'Security', 'SECURITY'],
            ['Mulyadi', 'Security', 'SECURITY'],
            ['Sopian', 'Security', 'SECURITY'],
            ['Rudiansyah', 'Cleaning Service', 'CLEANING_SERVICE'],
            ['Zainal Abidin', 'Cleaning Service', 'CLEANING_SERVICE'],
            ['Masdiani Awali Yunita', 'Laboratorium', 'NORMAL'],
        ];

        $stmt = $pdo->prepare("INSERT INTO employees (name, position, schedule_type, active, start_date) VALUES (?, ?, ?, 1, '2025-01-01')");
        foreach ($employees as $emp) {
            $stmt->execute($emp);
        }
        echo "Seeded 19 employees.\n";
    }

    // Seed 2026 Holidays if empty
    $holCount = (int) $pdo->query("SELECT COUNT(*) FROM holidays")->fetchColumn();
    if ($holCount === 0) {
        $holidays = [
            ['2026-01-01', 'Tahun Baru Masehi', ''],
            ['2026-01-27', 'Isra Miraj Nabi Muhammad SAW', ''],
            ['2026-02-17', 'Hari Raya Imlek', ''],
            ['2026-03-20', 'Hari Raya Nyepi', ''],
            ['2026-03-29', 'Wafat Isa Al Masih', ''],
            ['2026-03-31', 'Hari Raya Idul Fitri', ''],
            ['2026-04-01', 'Hari Raya Idul Fitri (Hari 2)', ''],
            ['2026-04-16', 'Hari Raya Waisak', ''],
            ['2026-05-01', 'Hari Buruh Internasional', ''],
            ['2026-05-14', 'Kenaikan Isa Al Masih', ''],
            ['2026-06-01', 'Hari Lahir Pancasila', ''],
            ['2026-06-06', 'Hari Raya Idul Adha', ''],
            ['2026-06-27', 'Tahun Baru Islam 1448 H', ''],
            ['2026-08-17', 'Hari Kemerdekaan Republik Indonesia', 'HUT RI ke-81'],
            ['2026-08-25', 'Maulid Nabi Muhammad SAW', ''],
            ['2026-12-25', 'Hari Raya Natal', ''],
        ];

        $stmt = $pdo->prepare("INSERT IGNORE INTO holidays (date, name, description, active) VALUES (?, ?, ?, 1)");
        foreach ($holidays as $h) {
            $stmt->execute($h);
        }
        echo "Seeded holidays.\n";
    }

    // Seed default work period if empty
    $periodCount = (int) $pdo->query("SELECT COUNT(*) FROM work_periods")->fetchColumn();
    if ($periodCount === 0) {
        $pdo->exec("INSERT INTO work_periods (name, start_date, end_date, status) VALUES ('27 Juli - 26 Agustus 2026', '2026-07-27', '2026-08-26', 'active')");
        echo "Seeded default work period.\n";
    }

    // Seed schedule settings if empty
    $setCount = (int) $pdo->query("SELECT COUNT(*) FROM schedule_settings")->fetchColumn();
    if ($setCount === 0) {
        // Fetch IDs for specific employees
        $empMap = [];
        $res = $pdo->query("SELECT id, name FROM employees")->fetchAll();
        foreach ($res as $r) {
            $empMap[$r['name']] = (int) $r['id'];
        }

        $stmt = $pdo->prepare("INSERT INTO schedule_settings (setting_key, setting_value, description) VALUES (?, ?, ?)");

        // Zainal Naim: Mon(1), Tue(2), Wed(3)
        if (isset($empMap['Zainal Naim'])) {
            $stmt->execute([
                'apoteker.employee_' . $empMap['Zainal Naim'] . '.workdays',
                json_encode([1, 2, 3]),
                'Hari kerja Zainal Naim: Senin, Selasa, Rabu'
            ]);
        }

        // Jayadi Irwanto: Thu(4), Fri(5), Sat(6)
        if (isset($empMap['Jayadi Irwanto'])) {
            $stmt->execute([
                'apoteker.employee_' . $empMap['Jayadi Irwanto'] . '.workdays',
                json_encode([4, 5, 6]),
                'Hari kerja Jayadi Irwanto: Kamis, Jumat, Sabtu'
            ]);
        }

        // Security rotation
        if (isset($empMap['Mardiansyah'], $empMap['Mulyadi'], $empMap['Sopian'])) {
            $secConfig = [
                'reference_date' => '2026-07-27',
                'pattern' => [
                    ['pagi' => $empMap['Mulyadi'],     'malam' => $empMap['Sopian'],      'libur' => $empMap['Mardiansyah']],
                    ['pagi' => $empMap['Mardiansyah'], 'malam' => $empMap['Mulyadi'],     'libur' => $empMap['Sopian']],
                    ['pagi' => $empMap['Sopian'],      'malam' => $empMap['Mardiansyah'], 'libur' => $empMap['Mulyadi']],
                ]
            ];
            $stmt->execute([
                'security.rotation',
                json_encode($secConfig),
                'Konfigurasi rotasi Security 3 orang'
            ]);
        }

        // Cleaning service sequence
        if (isset($empMap['Rudiansyah'], $empMap['Zainal Abidin'])) {
            $csConfig = [
                'reference_date' => '2026-07-27',
                'sequence' => [
                    ['employee_id' => $empMap['Rudiansyah'],   'days' => 2],
                    ['employee_id' => $empMap['Zainal Abidin'], 'days' => 2],
                ]
            ];
            $stmt->execute([
                'cleaning_service.sequence',
                json_encode($csConfig),
                'Konfigurasi giliran Cleaning Service'
            ]);
        }

        echo "Seeded schedule settings.\n";
    }

    echo "Migration completed successfully!\n";
} catch (\Throwable $e) {
    echo "Migration error: " . $e->getMessage() . "\n";
}
