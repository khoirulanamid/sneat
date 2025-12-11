<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once __DIR__ . '/../../config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $redir = page_url('mahasiswa/mahasiswa');
    if (!headers_sent()) {
        header('Location: ' . $redir);
        exit;
    }
    echo '<script>window.location.href = ' . json_encode($redir) . ';</script>';
    exit;
}

$nim = isset($_POST['nim']) ? trim($_POST['nim']) : '';
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

$redirect = page_url('mahasiswa/update-mahasiswa') . '?nim=' . urlencode($nim);

if (!$nim || !$nama || !$jenisKelamin || !$jurusan || !$tahunMasuk || !$status) {
    $_SESSION['edit_mahasiswa_error'] = 'Semua field wajib diisi.';
    if (!headers_sent()) {
        header('Location: ' . $redirect);
        exit;
    }
    echo '<script>window.location.href = ' . json_encode($redirect) . ';</script>';
    exit;
}

try {
    $update = $pdo->prepare(
        "UPDATE mahasiswa
         SET nama = :nama,
             jenis_kelamin = :jenis_kelamin,
             jurusan = :jurusan,
             tahun_masuk = :tahun_masuk,
             status = :status,
             tempat_lahir = :tempat_lahir,
             tanggal_lahir = :tanggal_lahir,
             email = :email,
             no_hp = :no_hp,
             alamat = :alamat
         WHERE nim = :nim"
    );

    $update->execute([
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
        ':nim' => $nim,
    ]);

    $ok = page_url('mahasiswa/mahasiswa');
    if (!headers_sent()) {
        header('Location: ' . $ok);
        exit;
    }
    echo '<script>window.location.href = ' . json_encode($ok) . ';</script>';
    exit;
} catch (PDOException $e) {
    $_SESSION['edit_mahasiswa_error'] = 'Gagal memperbarui data: ' . $e->getMessage();
    if (!headers_sent()) {
        header('Location: ' . $redirect);
        exit;
    }
    echo '<script>window.location.href = ' . json_encode($redirect) . ';</script>';
    exit;
}
