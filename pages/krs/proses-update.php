<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once __DIR__ . '/../../config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $redir = page_url('krs/krs');
    if (!headers_sent()) {
        header('Location: ' . $redir);
        exit;
    }
    echo '<script>window.location.href = ' . json_encode($redir) . ';</script>';
    exit;
}

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$idMahasiswa = $_POST['id_mahasiswa'] ?? '';
$idMatkul = $_POST['id_matkul'] ?? '';
$semester = trim($_POST['semester'] ?? '');
$tahunAjaran = trim($_POST['tahun_ajaran'] ?? '');
$status = $_POST['status'] ?? 'Belum Disetujui';
$allowedStatus = ['Belum Disetujui', 'Disetujui', 'Ditolak'];
if (!in_array($status, $allowedStatus, true)) {
    $status = 'Belum Disetujui';
}

$redirect = page_url('krs/update-krs') . '?id=' . urlencode((string)$id);

try {
    if (!$id || !$idMahasiswa || !$idMatkul || !$semester || !$tahunAjaran) {
        throw new RuntimeException('Semua field wajib diisi.');
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

    $cekMatkul = $pdo->prepare("SELECT COUNT(*) FROM matakuliah WHERE id_matkul = :id");
    $cekMatkul->execute([':id' => $idMatkul]);
    if ($cekMatkul->fetchColumn() == 0) {
        throw new RuntimeException('Mata kuliah tidak ditemukan.');
    }

    $update = $pdo->prepare(
        "UPDATE krs
         SET id_mahasiswa = :id_mahasiswa,
             id_matkul = :id_matkul,
             semester = :semester,
             tahun_ajaran = :tahun_ajaran,
             status = :status
         WHERE id_krs = :id"
    );

    $update->execute([
        ':id_mahasiswa' => $idMahasiswa,
        ':id_matkul' => $idMatkul,
        ':semester' => (int)$semester,
        ':tahun_ajaran' => $tahunAjaran,
        ':status' => $status,
        ':id' => $id,
    ]);

    $ok = page_url('krs/krs');
    if (!headers_sent()) {
        header('Location: ' . $ok);
        exit;
    }
    echo '<script>window.location.href = ' . json_encode($ok) . ';</script>';
    exit;
} catch (Throwable $e) {
    $_SESSION['edit_krs_error'] = $e->getMessage();
    if (!headers_sent()) {
        header('Location: ' . $redirect);
        exit;
    }
    echo '<script>window.location.href = ' . json_encode($redirect) . ';</script>';
    exit;
}
