<?php
require_once __DIR__ . '/../bootstrap.php';

use App\Controllers\LaporanController;

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    jsonResponse(['status' => 'ok']);
}

$isFull = isset($_GET['full']) && $_GET['full'] == '1';
$controller = new LaporanController();
$data = $isFull ? $controller->getDashboardList() : $controller->getList();

jsonResponse(['success' => true, 'data' => $data]);
