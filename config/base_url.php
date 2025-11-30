<?php
// ...existing code...
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$script = rtrim(dirname($_SERVER['SCRIPT_NAME'] ?? ''), '/\\');
$base_url = $protocol . '://' . $host . $script . '/';

// Pastikan juga ada konstanta BASE_URL untuk file yang mungkin memakai konstanta
if (!defined('BASE_URL')) {
    define('BASE_URL', $base_url);
}

if (!function_exists('asset_url')) {
    function asset_url(string $path = ''): string
    {
        return rtrim(BASE_URL, '/') . '/' . ltrim($path, '/');
    }
}

require_once __DIR__ . '/routes.php';
