<?php
// Centralized base URL helper
if (!isset($base_url) || !$base_url) {
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $scriptDir = isset($_SERVER['SCRIPT_NAME']) ? dirname($_SERVER['SCRIPT_NAME']) : '';
    $scriptDir = rtrim($scriptDir, '/\\');
    $base_url = $scheme . '://' . $host . ($scriptDir ? $scriptDir : '');
    // Default fallback for CLI or missing server vars
    if (!isset($_SERVER['HTTP_HOST'])) {
        $base_url = 'http://localhost/sneat';
    }
}

if (!function_exists('asset_url')) {
    /**
     * Build full URL for assets relative to project public root.
     */
    function asset_url(string $path = ''): string
    {
        global $base_url;
        return rtrim($base_url, '/') . '/' . ltrim($path, '/');
    }
}

require_once __DIR__ . '/routes.php';
