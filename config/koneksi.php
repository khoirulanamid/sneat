<?php
$host = "localhost";
$dbname = "akademik";
$username = "root";
$password = "";

// safer include for base-url.php (tries same folder, parent folder, then fallback)
$baseUrlPaths = [
    __DIR__ . '/base_url.php',
    __DIR__ . '/base-url.php',
    __DIR__ . '/../base_url.php',
    __DIR__ . '/../base-url.php',
];

$included = false;
foreach ($baseUrlPaths as $p) {
    if (file_exists($p)) {
        include_once $p;
        $included = true;
        break;
    }
}

if (!$included) {
    // fallback default if base_url.php is missing (adjust to your app path)
    $base_url = 'http://localhost/sneat/';
    if (!defined('BASE_URL')) {
        define('BASE_URL', $base_url);
    }
}

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    // Set error mode ke exception agar mudah debugging
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Koneksi database gagal: " . $e->getMessage());
}
