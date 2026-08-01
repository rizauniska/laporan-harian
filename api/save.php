<?php
require_once __DIR__ . '/../bootstrap.php';

use App\Controllers\LaporanController;

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    jsonResponse(['status' => 'ok']);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(['success' => false, 'message' => 'Method not allowed'], 405);
}

$json = file_get_contents('php://input');
$data = json_decode($json, true);

if (!is_array($data) || !isset($data['tanggal'])) {
    jsonResponse(['success' => false, 'message' => 'Invalid JSON payload or missing tanggal'], 400);
}

$controller = new LaporanController();
$result = $controller->save($data);

jsonResponse($result);
