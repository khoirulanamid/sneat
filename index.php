<?php
require_once __DIR__ . "/config/base_url.php";
require_once __DIR__ . "/config/koneksi.php";

$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
$page = resolve_page_route($page);
$content = "pages/" . $page . ".php";

if (!file_exists($content)) {
    $content = "pages/dashboard.php";
}

include "view/layout/app.php";
