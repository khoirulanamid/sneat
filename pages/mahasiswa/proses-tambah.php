<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once __DIR__ . '/../../config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $redir = '../../index.php?page=mahasiswa/tambah-mahasiswa';
    if (!headers_sent()) {
        header("Location: " . $redir);
        exit;
    }
    echo '<script>window.location.href = ' . json_encode($redir) . ';</script>';
    exit;
}

$nim = trim($_POST['nim'] ?? '');
$nama = trim($_POST['nama'] ?? '');
$jenisKelamin = $_POST['jenis_kelamin'] ?? '';
$jurusan = trim($_POST['jurusan'] ?? '');
$tahunMasuk = trim($_POST['tahun_masuk'] ?? '');
$status = $_POST['status'] ?? '';
$tempatLahir = trim($_POST['tempat_lahir'] ?? '');
$tanggalLahir = trim($_POST['tanggal_lahir'] ?? '');
$email = trim($_POST['email'] ?? '');
$noHp = trim($_POST['no_hp'] ?? '');
$alamat = trim($_POST['alamat'] ?? '');

$old = [
    'nim' => $nim,
    'nama' => $nama,
    'jenis_kelamin' => $jenisKelamin,
    'jurusan' => $jurusan,
    'tahun_masuk' => $tahunMasuk,
    'status' => $status ?: 'Aktif',
    'tempat_lahir' => $tempatLahir,
    'tanggal_lahir' => $tanggalLahir,
    'email' => $email,
    'no_hp' => $noHp,
    'alamat' => $alamat,
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
                "INSERT INTO mahasiswa (
                    nim, nama, jenis_kelamin, jurusan, tahun_masuk, status,
                    tempat_lahir, tanggal_lahir, email, no_hp, alamat
                )
                VALUES (
                    :nim, :nama, :jenis_kelamin, :jurusan, :tahun_masuk, :status,
                    :tempat_lahir, :tanggal_lahir, :email, :no_hp, :alamat
                )"
            );

            $stmt->execute([
                ':nim' => $nim,
                ':nama' => $nama,
                ':jenis_kelamin' => $jenisKelamin,
                ':jurusan' => $jurusan,
                ':tahun_masuk' => $tahunMasuk,
                ':status' => $status,
                ':tempat_lahir' => $tempatLahir ?: null,
                ':tanggal_lahir' => $tanggalLahir ?: null,
                ':email' => $email ?: null,
                ':no_hp' => $noHp ?: null,
                ':alamat' => $alamat ?: null,
            ]);

            unset($_SESSION['tambah_mahasiswa_error'], $_SESSION['tambah_mahasiswa_old']);
            $ok = '../../index.php?page=mahasiswa/mahasiswa';
            if (!headers_sent()) {
                header("Location: " . $ok);
                exit;
            }
            echo '<script>window.location.href = ' . json_encode($ok) . ';</script>';
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

$redir = '../../index.php?page=mahasiswa/tambah-mahasiswa';
if (!headers_sent()) {
    header("Location: " . $redir);
    exit;
}
echo '<script>window.location.href = ' . json_encode($redir) . ';</script>';
exit;
