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

if (!isset($data['id'])) {
    jsonResponse(['success' => false, 'message' => 'Missing id'], 400);
}

$controller = new LaporanController();
$result = $controller->delete((int)$data['id']);

jsonResponse($result);
