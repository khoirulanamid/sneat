<?php
// Muat base URL lebih dulu agar konstanta tersedia
$baseUrlFile = __DIR__ . "/config/base_url.php";
if (file_exists($baseUrlFile)) {
    require_once $baseUrlFile;
} else {
    // Fallback BASE_URL supaya aplikasi tidak fatal jika file hilang
    $protocol = (!empty($_SERVER['HTTPS'] ?? '') && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $script = rtrim(dirname($_SERVER['SCRIPT_NAME'] ?? ''), '/\\');
    if (!defined('BASE_URL')) {
        define('BASE_URL', $protocol . '://' . $host . $script . '/');
    }
    $base_url = BASE_URL;
    error_log("Warning: base_url.php not found; using fallback BASE_URL=" . BASE_URL);
}

// Mulai session sedini mungkin sebelum layout mengirim output
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . "/config/koneksi.php";

$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
$page = resolve_page_route($page);
$content = __DIR__ . "/pages/" . $page . ".php";

// Untuk permintaan AJAX ringan (mis. memuat opsi select), bypass layout agar respons JSON bersih
if (isset($_GET['ajax']) && $_GET['ajax'] === '1') {
    if (file_exists($content)) {
        include $content;
    } else {
        header('Content-Type: application/json');
        http_response_code(404);
        echo json_encode(['error' => 'Halaman tidak ditemukan']);
    }
    exit;
}

if (!file_exists($content)) {
    $content = __DIR__ . "/pages/dashboard.php";
}

require_once __DIR__ . "/view/layout/app.php";
