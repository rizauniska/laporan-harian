<?php
declare(strict_types=1);
define('BASE_PATH', __DIR__);

spl_autoload_register(function (string $class): void {
    $prefix = 'App\\';
    if (strpos($class, $prefix) !== 0) return;
    $relative = substr($class, strlen($prefix));
    $parts    = explode('\\', $relative);
    $parts[0] = strtolower($parts[0]);
    $file     = BASE_PATH . '/' . implode('/', $parts) . '.php';
    if (file_exists($file)) require_once $file;
});

function jsonResponse(array $data, int $code = 200): never {
    http_response_code($code);
    header('Content-Type: application/json; charset=utf-8');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, OPTIONS, DELETE');
    header('Access-Control-Allow-Headers: Content-Type');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}
