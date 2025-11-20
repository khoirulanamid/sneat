<?php
session_start();
include_once __DIR__ . '/../../config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . page_url('ktm/tambah-ktm'));
    exit;
}

$idMahasiswa = $_POST['id_mahasiswa'] ?? '';
$nomorKartu = trim($_POST['nomor_kartu'] ?? '');
$tglTerbit = $_POST['tgl_terbit'] ?? '';
$masaBerlaku = $_POST['masa_berlaku'] ?? '';
$status = $_POST['status'] ?? 'Aktif';
$fotoKartu = trim($_POST['foto_kartu'] ?? '');
$keterangan = trim($_POST['keterangan'] ?? '');

$allowedStatus = ['Aktif', 'Tidak Aktif', 'Hilang', 'Rusak'];
if (!in_array($status, $allowedStatus, true)) {
    $status = 'Aktif';
}

$old = [
    'id_mahasiswa' => $idMahasiswa,
    'nomor_kartu' => $nomorKartu,
    'tgl_terbit' => $tglTerbit,
    'masa_berlaku' => $masaBerlaku,
    'status' => $status,
    'foto_kartu' => $fotoKartu,
    'keterangan' => $keterangan,
];

$errorMessage = '';

try {
    if (!$idMahasiswa || !$nomorKartu || !$tglTerbit || !$masaBerlaku) {
        throw new RuntimeException('Mahasiswa, nomor kartu, tanggal terbit, dan masa berlaku wajib diisi.');
    }

    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $tglTerbit) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $masaBerlaku)) {
        throw new RuntimeException('Format tanggal tidak valid.');
    }

    if (strtotime($masaBerlaku) < strtotime($tglTerbit)) {
        throw new RuntimeException('Masa berlaku tidak boleh lebih awal dari tanggal terbit.');
    }

    $cekMahasiswa = $pdo->prepare("SELECT COUNT(*) FROM mahasiswa WHERE id_mahasiswa = :id");
    $cekMahasiswa->execute([':id' => $idMahasiswa]);
    if ($cekMahasiswa->fetchColumn() == 0) {
        throw new RuntimeException('Mahasiswa tidak ditemukan.');
    }

    $cekNomor = $pdo->prepare("SELECT COUNT(*) FROM ktm WHERE nomor_kartu = :nomor");
    $cekNomor->execute([':nomor' => $nomorKartu]);
    if ($cekNomor->fetchColumn() > 0) {
        throw new RuntimeException('Nomor KTM sudah terdaftar.');
    }

    $stmt = $pdo->prepare(
        "INSERT INTO ktm (id_mahasiswa, nomor_kartu, tgl_terbit, masa_berlaku, status, foto_kartu, keterangan)
         VALUES (:id_mahasiswa, :nomor_kartu, :tgl_terbit, :masa_berlaku, :status, :foto_kartu, :keterangan)"
    );

    $stmt->execute([
        ':id_mahasiswa' => $idMahasiswa,
        ':nomor_kartu' => $nomorKartu,
        ':tgl_terbit' => $tglTerbit,
        ':masa_berlaku' => $masaBerlaku,
        ':status' => $status,
        ':foto_kartu' => $fotoKartu,
        ':keterangan' => $keterangan,
    ]);

    unset($_SESSION['tambah_ktm_error'], $_SESSION['tambah_ktm_old']);
    header('Location: ' . page_url('ktm/ktm'));
    exit;
} catch (Throwable $e) {
    $errorMessage = $e->getMessage();
}

$_SESSION['tambah_ktm_error'] = $errorMessage;
$_SESSION['tambah_ktm_old'] = $old;

header('Location: ' . page_url('ktm/tambah-ktm'));
exit;
