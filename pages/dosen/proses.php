<?php
/**
 * Endpoint proses tambah dosen (tanpa layout).
 * Dipanggil langsung lewat action form: BASE_URL/pages/dosen/proses.php
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once __DIR__ . '/../../config/koneksi.php';

// Hanya izinkan POST; selain itu kembalikan ke form.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../../index.php?page=dosen/tambah-dosen");
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
$statusMap = ['Tetap', 'Kontrak', 'Luar', 'LB'];
if ($statusDosen === 'LB') {
    $statusDosen = 'Luar';
}

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

if ($nidn && $namaDosen && $jenisKelamin && $pendidikanTerakhir && $keahlian && $statusDosen && in_array($statusDosen, $statusMap, true)) {
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
        header("Location: ../../index.php?page=dosen/dosen");
        exit;
    } catch (PDOException $e) {
        $errorMessage = 'Gagal menambah data: ' . $e->getMessage();
    }
} else {
    $errorMessage = 'Semua field wajib diisi.';
}

$_SESSION['tambah_dosen_error'] = $errorMessage;
$_SESSION['tambah_dosen_old'] = $old;

header("Location: ../../index.php?page=dosen/tambah-dosen");
exit;
