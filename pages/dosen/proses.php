<?php
session_start();
include_once __DIR__ . '/../../config/koneksi.php';

// Only allow POST; otherwise, bounce back to the form.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: " . page_url('dosen/tambah-dosen'));
    exit;
}

$nidn = trim($_POST['nidn'] ?? '');
$nip = trim($_POST['nip'] ?? '');
$namaDosen = trim($_POST['nama_dosen'] ?? '');
$jenisKelamin = $_POST['jenis_kelamin'] ?? '';
$tempatLahir = trim($_POST['tempat_lahir'] ?? '');
$tanggalLahir = trim($_POST['tanggal_lahir'] ?? '');
$jabatanAkademik = trim($_POST['jabatan_akademik'] ?? '');
$pendidikanTerakhir = trim($_POST['pendidikan_terakhir'] ?? '');
$keahlian = trim($_POST['keahlian'] ?? '');
$email = trim($_POST['email'] ?? '');
$noHp = trim($_POST['no_hp'] ?? '');
$alamat = trim($_POST['alamat'] ?? '');
$statusDosen = $_POST['status_dosen'] ?? '';

$old = [
    'nidn' => $nidn,
    'nip' => $nip,
    'nama_dosen' => $namaDosen,
    'jenis_kelamin' => $jenisKelamin,
    'tempat_lahir' => $tempatLahir,
    'tanggal_lahir' => $tanggalLahir,
    'keahlian' => $keahlian,
    'jabatan_akademik' => $jabatanAkademik ?: 'Asisten Ahli',
    'pendidikan_terakhir' => $pendidikanTerakhir,
    'email' => $email,
    'no_hp' => $noHp,
    'alamat' => $alamat,
    'status_dosen' => $statusDosen ?: 'Tetap',
];

$errorMessage = '';

if ($nidn && $namaDosen && $jenisKelamin && $pendidikanTerakhir && $keahlian && $statusDosen) {
    $jabatanAkademik = $jabatanAkademik ?: 'Asisten Ahli';
    $tanggalLahir = $tanggalLahir !== '' ? $tanggalLahir : null;
    try {
        $stmt = $pdo->prepare(
            "INSERT INTO dosen (
                nidn,
                nip,
                nama_dosen,
                jenis_kelamin,
                tempat_lahir,
                tanggal_lahir,
                keahlian,
                jabatan_akademik,
                pendidikan_terakhir,
                email,
                no_hp,
                alamat,
                status_dosen
            )
            VALUES (
                :nidn,
                :nip,
                :nama_dosen,
                :jenis_kelamin,
                :tempat_lahir,
                :tanggal_lahir,
                :keahlian,
                :jabatan_akademik,
                :pendidikan_terakhir,
                :email,
                :no_hp,
                :alamat,
                :status_dosen
            )"
        );

        $stmt->execute([
            ':nidn' => $nidn,
            ':nip' => $nip ?: null,
            ':nama_dosen' => $namaDosen,
            ':jenis_kelamin' => $jenisKelamin,
            ':tempat_lahir' => $tempatLahir ?: null,
            ':tanggal_lahir' => $tanggalLahir,
            ':jabatan_akademik' => $jabatanAkademik,
            ':pendidikan_terakhir' => $pendidikanTerakhir,
            ':keahlian' => $keahlian,
            ':email' => $email ?: null,
            ':no_hp' => $noHp ?: null,
            ':alamat' => $alamat ?: null,
            ':status_dosen' => $statusDosen,
        ]);

        unset($_SESSION['tambah_dosen_error'], $_SESSION['tambah_dosen_old']);
            header("Location: " . page_url('dosen/dosen'));
        exit;
    } catch (PDOException $e) {
        $errorMessage = 'Gagal menambah data: ' . $e->getMessage();
    }
} else {
    $errorMessage = 'Semua field wajib diisi.';
}

$_SESSION['tambah_dosen_error'] = $errorMessage;
$_SESSION['tambah_dosen_old'] = $old;

header("Location: " . page_url('dosen/tambah-dosen'));
exit;
