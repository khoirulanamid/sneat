<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once __DIR__ . '/../../config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $redir = page_url('matakuliah/matakuliah');
    if (!headers_sent()) {
        header('Location: ' . $redir);
        exit;
    }
    echo '<script>window.location.href = ' . json_encode($redir) . ';</script>';
    exit;
}

$kodeMatkul = isset($_POST['kode_matkul']) ? trim($_POST['kode_matkul']) : '';
$namaMatkul = trim($_POST['nama_matkul'] ?? '');
$sks = trim($_POST['sks'] ?? '');
$semester = trim($_POST['semester'] ?? '');
$idDosen = $_POST['id_dosen'] ?? '';
$jenisMatkul = trim($_POST['jenis_matkul'] ?? '');
$status = $_POST['status'] ?? '';
$idDosen = $idDosen === '' ? null : $idDosen;

$redirect = page_url('matakuliah/update-matakuliah') . '?kode=' . urlencode($kodeMatkul);

if (!$kodeMatkul || !$namaMatkul || !$sks || !$semester || !$jenisMatkul || !$status) {
    $_SESSION['edit_matkul_error'] = 'Semua field wajib diisi (pengampu opsional).';
    if (!headers_sent()) {
        header('Location: ' . $redirect);
        exit;
    }
    echo '<script>window.location.href = ' . json_encode($redirect) . ';</script>';
    exit;
}

try {
    $update = $pdo->prepare(
        "UPDATE matakuliah
         SET nama_matkul = :nama_matkul,
             sks = :sks,
             semester = :semester,
             id_dosen = :id_dosen,
             jenis_matkul = :jenis_matkul,
             status = :status
         WHERE kode_matkul = :kode_matkul"
    );

    $update->execute([
        ':nama_matkul' => $namaMatkul,
        ':sks' => $sks,
        ':semester' => $semester,
        ':id_dosen' => $idDosen,
        ':jenis_matkul' => $jenisMatkul,
        ':status' => $status,
        ':kode_matkul' => $kodeMatkul,
    ]);

    $ok = page_url('matakuliah/matakuliah');
    if (!headers_sent()) {
        header('Location: ' . $ok);
        exit;
    }
    echo '<script>window.location.href = ' . json_encode($ok) . ';</script>';
    exit;
} catch (PDOException $e) {
    $_SESSION['edit_matkul_error'] = 'Gagal memperbarui data: ' . $e->getMessage();
    if (!headers_sent()) {
        header('Location: ' . $redirect);
        exit;
    }
    echo '<script>window.location.href = ' . json_encode($redirect) . ';</script>';
    exit;
}
