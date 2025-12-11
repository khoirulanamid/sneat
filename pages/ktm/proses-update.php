<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once __DIR__ . '/../../config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $redir = page_url('ktm/ktm');
    if (!headers_sent()) {
        header('Location: ' . $redir);
        exit;
    }
    echo '<script>window.location.href = ' . json_encode($redir) . ';</script>';
    exit;
}

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$idMahasiswa = $_POST['id_mahasiswa'] ?? '';
$nomorKartu = trim($_POST['nomor_kartu'] ?? '');
$tglTerbit = $_POST['tgl_terbit'] ?? '';
$masaBerlaku = $_POST['masa_berlaku'] ?? '';
$status = $_POST['status'] ?? 'Aktif';
$keterangan = trim($_POST['keterangan'] ?? '');
$fotoKartuLama = trim($_POST['foto_kartu_lama'] ?? '');

$allowedStatus = ['Aktif', 'Tidak Aktif', 'Hilang', 'Rusak'];
if (!in_array($status, $allowedStatus, true)) {
    $status = 'Aktif';
}

try {
    if (!$id || !$idMahasiswa || !$nomorKartu || !$tglTerbit || !$masaBerlaku) {
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

    // Cek unik per mahasiswa (satu KTM per mahasiswa)
    $cekKtmMahasiswa = $pdo->prepare("SELECT COUNT(*) FROM ktm WHERE id_mahasiswa = :id AND id_ktm <> :current");
    $cekKtmMahasiswa->execute([
        ':id' => $idMahasiswa,
        ':current' => $id,
    ]);
    if ($cekKtmMahasiswa->fetchColumn() > 0) {
        throw new RuntimeException('Mahasiswa ini sudah memiliki KTM.');
    }

    $cekNomor = $pdo->prepare("SELECT COUNT(*) FROM ktm WHERE nomor_kartu = :nomor AND id_ktm <> :current");
    $cekNomor->execute([
        ':nomor' => $nomorKartu,
        ':current' => $id,
    ]);
    if ($cekNomor->fetchColumn() > 0) {
        throw new RuntimeException('Nomor KTM sudah terdaftar.');
    }

    $fotoKartu = $fotoKartuLama;
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

        $fotoKartu = 'public/uploads/ktm/' . $newName;
    }

    $update = $pdo->prepare(
        "UPDATE ktm
         SET id_mahasiswa = :id_mahasiswa,
             nomor_kartu = :nomor_kartu,
             tgl_terbit = :tgl_terbit,
             masa_berlaku = :masa_berlaku,
             status = :status,
             foto_kartu = :foto_kartu,
             keterangan = :keterangan
         WHERE id_ktm = :id"
    );

$update->execute([
    ':id_mahasiswa' => $idMahasiswa,
    ':nomor_kartu' => $nomorKartu,
    ':tgl_terbit' => $tglTerbit,
    ':masa_berlaku' => $masaBerlaku,
    ':status' => $status,
    ':foto_kartu' => $fotoKartu,
    ':keterangan' => $keterangan,
    ':id' => $id,
]);

$ok = page_url('ktm/ktm');
if (!headers_sent()) {
    header('Location: ' . $ok);
    exit;
}
echo '<script>window.location.href = ' . json_encode($ok) . ';</script>';
exit;
} catch (Throwable $e) {
    $_SESSION['edit_ktm_error'] = $e->getMessage();
    $redirect = page_url('ktm/update-ktm') . '?id=' . urlencode((string)$id);
    if (!headers_sent()) {
        header('Location: ' . $redirect);
        exit;
    }
    echo '<script>window.location.href = ' . json_encode($redirect) . ';</script>';
    exit;
}
