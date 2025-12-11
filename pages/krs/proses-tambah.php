<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once __DIR__ . '/../../config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $redir = '../../index.php?page=krs/tambah-krs';
    if (!headers_sent()) {
        header('Location: ' . $redir);
        exit;
    }
    echo '<script>window.location.href = ' . json_encode($redir) . ';</script>';
    exit;
}

$idMahasiswa = $_POST['id_mahasiswa'] ?? '';
$idMatkulList = $_POST['id_matkul'] ?? [];
$semester = trim($_POST['semester'] ?? '');
$tahunAjaran = trim($_POST['tahun_ajaran'] ?? '');
$status = $_POST['status'] ?? 'Belum Disetujui';
$allowedStatus = ['Belum Disetujui', 'Disetujui', 'Ditolak'];
if (!in_array($status, $allowedStatus, true)) {
    $status = 'Belum Disetujui';
}

$old = [
    'id_mahasiswa' => $idMahasiswa,
    'id_matkul' => $idMatkulList,
    'semester' => $semester,
    'tahun_ajaran' => $tahunAjaran,
    'status' => $status,
];

$errorMessage = '';

try {
    if (!$idMahasiswa || empty($idMatkulList) || !$semester || !$tahunAjaran || !$status) {
        throw new RuntimeException('Semua field wajib diisi.');
    }

    if (!is_array($idMatkulList)) {
        throw new RuntimeException('Mata kuliah harus berupa array.');
    }

    if (!preg_match('/^\d+$/', $semester) || (int)$semester < 1 || (int)$semester > 14) {
        throw new RuntimeException('Semester harus berupa angka antara 1 sampai 14.');
    }

    if (!preg_match('/^\d{4}\/\d{4}$/', $tahunAjaran)) {
        throw new RuntimeException('Format tahun ajaran tidak valid. Contoh: 2024/2025.');
    }

    $cekMahasiswa = $pdo->prepare("SELECT COUNT(*) FROM mahasiswa WHERE id_mahasiswa = :id");
    $cekMahasiswa->execute([':id' => $idMahasiswa]);
    if ($cekMahasiswa->fetchColumn() == 0) {
        throw new RuntimeException('Mahasiswa tidak ditemukan.');
    }

    $pdo->beginTransaction();

    $stmt = $pdo->prepare(
        "INSERT INTO krs (id_mahasiswa, id_matkul, semester, tahun_ajaran, tanggal_pengisian, status)
         VALUES (:id_mahasiswa, :id_matkul, :semester, :tahun_ajaran, NOW(), :status)"
    );

    $cekMatkul = $pdo->prepare("SELECT COUNT(*) FROM matakuliah WHERE id_matkul = :id");

    foreach ($idMatkulList as $idMatkul) {
        $cekMatkul->execute([':id' => $idMatkul]);
        if ($cekMatkul->fetchColumn() == 0) {
            throw new RuntimeException("Mata kuliah dengan ID {$idMatkul} tidak ditemukan.");
        }

        $stmt->execute([
            ':id_mahasiswa' => $idMahasiswa,
            ':id_matkul' => $idMatkul,
            ':semester' => (int)$semester,
            ':tahun_ajaran' => $tahunAjaran,
            ':status' => $status,
        ]);
    }

    $pdo->commit();

    unset($_SESSION['tambah_krs_error'], $_SESSION['tambah_krs_old']);
    $ok = '../../index.php?page=krs/krs';
    if (!headers_sent()) {
        header('Location: ' . $ok);
        exit;
    }
    echo '<script>window.location.href = ' . json_encode($ok) . ';</script>';
    exit;
} catch (Throwable $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    $errorMessage = $e->getMessage();
}

$_SESSION['tambah_krs_error'] = $errorMessage;
$_SESSION['tambah_krs_old'] = $old;

$redir = '../../index.php?page=krs/tambah-krs';
if (!headers_sent()) {
    header('Location: ' . $redir);
    exit;
}
echo '<script>window.location.href = ' . json_encode($redir) . ';</script>';
exit;
