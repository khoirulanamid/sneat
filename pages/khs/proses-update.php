<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once __DIR__ . '/../../config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $redir = page_url('khs/khs');
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
$nilaiAngka = trim($_POST['nilai_angka'] ?? '');
$status = $_POST['status'] ?? 'Lulus';
$catatan = trim($_POST['catatan'] ?? '');
$allowedStatus = ['Lulus', 'Tidak Lulus', 'Remedial'];
if (!in_array($status, $allowedStatus, true)) {
    $status = 'Lulus';
}

$redirect = page_url('khs/update-khs') . '?id=' . urlencode((string)$id);

try {
    if (!$id || !$idMahasiswa || !$idMatkul || !$semester || !$tahunAjaran || $nilaiAngka === '') {
        throw new RuntimeException('Semua field wajib diisi.');
    }

    if (!preg_match('/^\d+$/', $semester) || (int)$semester < 1 || (int)$semester > 14) {
        throw new RuntimeException('Semester harus berupa angka antara 1 sampai 14.');
    }

    if (!preg_match('/^\d{4}\/\d{4}$/', $tahunAjaran)) {
        throw new RuntimeException('Format tahun ajaran tidak valid. Contoh: 2024/2025.');
    }

    if (!is_numeric($nilaiAngka) || $nilaiAngka < 0 || $nilaiAngka > 100) {
        throw new RuntimeException('Nilai angka harus berada di rentang 0 - 100.');
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

    $nilaiHuruf = convertGrade((float)$nilaiAngka);

    $update = $pdo->prepare(
        "UPDATE khs
         SET id_mahasiswa = :id_mahasiswa,
             id_matkul = :id_matkul,
             semester = :semester,
             tahun_ajaran = :tahun_ajaran,
             nilai_angka = :nilai_angka,
             nilai_huruf = :nilai_huruf,
             status = :status,
             catatan = :catatan
         WHERE id_khs = :id"
    );

    $update->execute([
        ':id_mahasiswa' => $idMahasiswa,
        ':id_matkul' => $idMatkul,
        ':semester' => (int)$semester,
        ':tahun_ajaran' => $tahunAjaran,
        ':nilai_angka' => $nilaiAngka,
        ':nilai_huruf' => $nilaiHuruf,
        ':status' => $status,
        ':catatan' => $catatan,
        ':id' => $id,
    ]);

    $ok = page_url('khs/khs');
    if (!headers_sent()) {
        header('Location: ' . $ok);
        exit;
    }
    echo '<script>window.location.href = ' . json_encode($ok) . ';</script>';
    exit;
} catch (Throwable $e) {
    $_SESSION['edit_khs_error'] = $e->getMessage();
    if (!headers_sent()) {
        header('Location: ' . $redirect);
        exit;
    }
    echo '<script>window.location.href = ' . json_encode($redirect) . ';</script>';
    exit;
}

function convertGrade(float $nilai): string
{
    if ($nilai >= 85) {
        return 'A';
    }
    if ($nilai >= 70) {
        return 'B';
    }
    if ($nilai >= 55) {
        return 'C';
    }
    if ($nilai >= 40) {
        return 'D';
    }
    return 'E';
}
