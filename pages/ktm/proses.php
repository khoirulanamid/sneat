<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once __DIR__ . '/../../config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../../index.php?page=ktm/tambah-ktm');
    exit;
}

$idMahasiswa = $_POST['id_mahasiswa'] ?? '';
$nomorKartu = trim($_POST['nomor_kartu'] ?? '');
$tglTerbit = $_POST['tgl_terbit'] ?? '';
$masaBerlaku = $_POST['masa_berlaku'] ?? '';
$status = $_POST['status'] ?? 'Aktif';
$fotoKartu = '';
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

    // Handle upload foto jika ada
    if (isset($_FILES['foto_kartu']) && $_FILES['foto_kartu']['error'] !== UPLOAD_ERR_NO_FILE) {
        $file = $_FILES['foto_kartu'];
        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new RuntimeException('Upload foto gagal. Silakan coba lagi.');
        }

        $allowedExt = ['jpg', 'jpeg', 'png', 'webp'];
        $maxSize = 2 * 1024 * 1024; // 2MB
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if (!in_array($ext, $allowedExt, true)) {
            throw new RuntimeException('Format foto harus jpg, jpeg, png, atau webp.');
        }
        if ($file['size'] > $maxSize) {
            throw new RuntimeException('Ukuran foto maksimal 2MB.');
        }

        $uploadDir = __DIR__ . '/../../public/uploads/ktm/';
        if (!is_dir($uploadDir) && !mkdir($uploadDir, 0777, true)) {
            throw new RuntimeException('Gagal membuat folder upload.');
        }

        $newName = 'ktm-' . time() . '-' . bin2hex(random_bytes(4)) . '.' . $ext;
        $targetPath = $uploadDir . $newName;

        if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
            throw new RuntimeException('Gagal menyimpan foto KTM.');
        }

        // Simpan path yang dapat diakses publik
        $fotoKartu = 'public/uploads/ktm/' . $newName;
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
    $redirect = '../../index.php?page=ktm/ktm';
    if (!headers_sent()) {
        header('Location: ' . $redirect);
        exit;
    }
    echo '<script>window.location.href = ' . json_encode($redirect) . ';</script>';
    exit;
} catch (Throwable $e) {
    $errorMessage = $e->getMessage();
}

$_SESSION['tambah_ktm_error'] = $errorMessage;
$_SESSION['tambah_ktm_old'] = $old;

$redirect = '../../index.php?page=ktm/tambah-ktm';
if (!headers_sent()) {
    header('Location: ' . $redirect);
    exit;
}
echo '<script>window.location.href = ' . json_encode($redirect) . ';</script>';
exit;
