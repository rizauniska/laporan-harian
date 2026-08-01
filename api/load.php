<?php
require_once __DIR__ . '/../bootstrap.php';

use App\Controllers\LaporanController;

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    jsonResponse(['status' => 'ok']);
}

$tanggal = $_GET['tanggal'] ?? '';
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $tanggal)) {
    jsonResponse(['success' => false, 'message' => 'Invalid or missing tanggal parameter. Format must be YYYY-MM-DD'], 400);
}

$controller = new LaporanController();
$data = $controller->load($tanggal);

jsonResponse($data);
