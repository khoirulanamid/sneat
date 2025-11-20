<?php
session_start();
include_once __DIR__ . '/../../config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: " . page_url('mahasiswa/tambah-mahasiswa'));
    exit;
}

$nim = trim($_POST['nim'] ?? '');
$nama = trim($_POST['nama'] ?? '');
$jenisKelamin = $_POST['jenis_kelamin'] ?? '';
$jurusan = trim($_POST['jurusan'] ?? '');
$tahunMasuk = trim($_POST['tahun_masuk'] ?? '');
$status = $_POST['status'] ?? '';

$old = [
    'nim' => $nim,
    'nama' => $nama,
    'jenis_kelamin' => $jenisKelamin,
    'jurusan' => $jurusan,
    'tahun_masuk' => $tahunMasuk,
    'status' => $status ?: 'Aktif',
];

$errorMessage = '';

if ($nim && $nama && $jenisKelamin && $jurusan && $tahunMasuk && $status) {
    try {
        $cekNim = $pdo->prepare("SELECT COUNT(*) FROM mahasiswa WHERE nim = :nim");
        $cekNim->execute([':nim' => $nim]);

        if ($cekNim->fetchColumn() > 0) {
            $errorMessage = 'NIM sudah terdaftar, silakan gunakan NIM lain.';
        } else {
            $stmt = $pdo->prepare(
                "INSERT INTO mahasiswa (nim, nama, jenis_kelamin, jurusan, tahun_masuk, status)
                 VALUES (:nim, :nama, :jenis_kelamin, :jurusan, :tahun_masuk, :status)"
            );

            $stmt->execute([
                ':nim' => $nim,
                ':nama' => $nama,
                ':jenis_kelamin' => $jenisKelamin,
                ':jurusan' => $jurusan,
                ':tahun_masuk' => $tahunMasuk,
                ':status' => $status,
            ]);

            unset($_SESSION['tambah_mahasiswa_error'], $_SESSION['tambah_mahasiswa_old']);
            header("Location: " . page_url('mahasiswa/mahasiswa'));
            exit;
        }
    } catch (PDOException $e) {
        $errorMessage = 'Gagal menambah data: ' . $e->getMessage();
    }
} else {
    $errorMessage = 'Semua field wajib diisi.';
}

$_SESSION['tambah_mahasiswa_error'] = $errorMessage;
$_SESSION['tambah_mahasiswa_old'] = $old;

header("Location: " . page_url('mahasiswa/tambah-mahasiswa'));
exit;
