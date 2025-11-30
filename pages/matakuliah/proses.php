<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once __DIR__ . '/../../config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../../index.php?page=matakuliah/tambah-matakuliah");
    exit;
}

$kodeMatkul = trim($_POST['kode_matkul'] ?? '');
$namaMatkul = trim($_POST['nama_matkul'] ?? '');
$sks = trim($_POST['sks'] ?? '');
$semester = trim($_POST['semester'] ?? '');
$idDosen = $_POST['id_dosen'] ?? '';
$jenisMatkul = trim($_POST['jenis_matkul'] ?? '');
$status = $_POST['status'] ?? '';

$idDosen = $idDosen === '' ? null : $idDosen;

$old = [
    'kode_matkul' => $kodeMatkul,
    'nama_matkul' => $namaMatkul,
    'sks' => $sks,
    'semester' => $semester,
    'id_dosen' => $idDosen ?? '',
    'jenis_matkul' => $jenisMatkul,
    'status' => $status ?: 'Aktif',
];

$errorMessage = '';

if ($kodeMatkul && $namaMatkul && $sks && $semester && $jenisMatkul && $status) {
    try {
        $stmt = $pdo->prepare(
            "INSERT INTO matakuliah (kode_matkul, nama_matkul, sks, semester, id_dosen, jenis_matkul, status)
             VALUES (:kode_matkul, :nama_matkul, :sks, :semester, :id_dosen, :jenis_matkul, :status)"
        );

        $stmt->execute([
            ':kode_matkul' => $kodeMatkul,
            ':nama_matkul' => $namaMatkul,
            ':sks' => $sks,
            ':semester' => $semester,
            ':id_dosen' => $idDosen,
            ':jenis_matkul' => $jenisMatkul,
            ':status' => $status,
        ]);

        unset($_SESSION['tambah_matakuliah_error'], $_SESSION['tambah_matakuliah_old']);
        header("Location: ../../index.php?page=matakuliah/matakuliah");
        exit;
    } catch (PDOException $e) {
        $errorMessage = 'Gagal menambah data: ' . $e->getMessage();
    }
} else {
    $errorMessage = 'Semua field wajib diisi (kecuali dosen pengampu opsional).';
}

$_SESSION['tambah_matakuliah_error'] = $errorMessage;
$_SESSION['tambah_matakuliah_old'] = $old;

header("Location: ../../index.php?page=matakuliah/tambah-matakuliah");
exit;
