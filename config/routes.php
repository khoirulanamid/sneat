<?php
$prettyRoutes = [
    'dashboard' => 'dashboard',
    'dosen/dosen' => 'dosen',
    'dosen/tambah-dosen' => 'tambah-dosen',
    'dosen/edit-dosen' => 'edit-dosen',
    'dosen/view-dosen' => 'view-dosen',
    'dosen/delete-dosen' => 'delete-dosen',
    'dosen/proses-tambah' => 'dosen/proses-tambah',
    'dosen/proses-update' => 'dosen/proses-update',
    'dosen/update-dosen' => 'dosen/update-dosen',
    'mahasiswa/mahasiswa' => 'mahasiswa',
    'mahasiswa/tambah-mahasiswa' => 'tambah-mahasiswa',
    'mahasiswa/edit-mahasiswa' => 'edit-mahasiswa',
    'mahasiswa/view-mahasiswa' => 'view-mahasiswa',
    'mahasiswa/delete-mahasiswa' => 'delete-mahasiswa',
    'mahasiswa/proses-tambah' => 'mahasiswa/proses-tambah',
    'mahasiswa/proses-update' => 'mahasiswa/proses-update',
    'mahasiswa/update-mahasiswa' => 'mahasiswa/update-mahasiswa',
    'matakuliah/matakuliah' => 'matakuliah',
    'matakuliah/tambah-matakuliah' => 'tambah-matakuliah',
    'matakuliah/edit-matakuliah' => 'edit-matakuliah',
    'matakuliah/view-matakuliah' => 'view-matakuliah',
    'matakuliah/delete-matakuliah' => 'delete-matakuliah',
    'matakuliah/proses-tambah' => 'matakuliah/proses-tambah',
    'matakuliah/proses-update' => 'matakuliah/proses-update',
    'matakuliah/update-matakuliah' => 'matakuliah/update-matakuliah',
    'krs/krs' => 'krs',
    'krs/tambah-krs' => 'tambah-krs',
    'krs/edit-krs' => 'edit-krs',
    'krs/view-krs' => 'view-krs',
    'krs/delete-krs' => 'delete-krs',
    'krs/proses-tambah' => 'krs/proses-tambah',
    'krs/proses-update' => 'krs/proses-update',
    'krs/update-krs' => 'krs/update-krs',
    'khs/khs' => 'khs',
    'khs/tambah-khs' => 'tambah-khs',
    'khs/edit-khs' => 'edit-khs',
    'khs/view-khs' => 'view-khs',
    'khs/delete-khs' => 'delete-khs',
    'khs/proses-tambah' => 'khs/proses-tambah',
    'khs/proses-update' => 'khs/proses-update',
    'khs/update-khs' => 'khs/update-khs',
    'ktm/ktm' => 'ktm',
    'ktm/tambah-ktm' => 'tambah-ktm',
    'ktm/edit-ktm' => 'edit-ktm',
    'ktm/view-ktm' => 'view-ktm',
    'ktm/delete-ktm' => 'delete-ktm',
    'ktm/proses-tambah' => 'ktm/proses-tambah',
    'ktm/proses-update' => 'ktm/proses-update',
    'ktm/update-ktm' => 'ktm/update-ktm',
    'laporan/laporankrs' => 'laporan/krs',
    'laporan/laporankhs' => 'laporan/khs',
];

$incomingRoutes = array_flip($prettyRoutes);

if (!function_exists('resolve_page_route')) {
    function resolve_page_route(string $page): string
    {
        global $incomingRoutes;
        return $incomingRoutes[$page] ?? $page;
    }
}

if (!function_exists('page_url')) {
    function page_url(string $page): string
    {
        global $prettyRoutes, $base_url;
        $base = rtrim($base_url, '/');
        if (isset($prettyRoutes[$page])) {
            return $base . '/' . ltrim($prettyRoutes[$page], '/');
        }
        return $base . '/index.php?page=' . $page;
    }
}
