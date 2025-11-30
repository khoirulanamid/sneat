<?php
include_once __DIR__ . '/../../config/koneksi.php';

$redirectUrl = page_url('krs/krs');
$jsRedirect = function (string $url) {
    echo '<script>window.location.href = ' . json_encode($url) . ';</script>';
    exit;
};

$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    $confirm = $_POST['confirm'] ?? '';

    if ($confirm === 'yes' && $id > 0) {
        try {
            $stmt = $pdo->prepare("DELETE FROM krs WHERE id_krs = :id");
            $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            $errorMessage = 'Gagal menghapus data: ' . $e->getMessage();
        }
    }

    $jsRedirect($redirectUrl);
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    $jsRedirect($redirectUrl);
}

$stmt = $pdo->prepare(
    "SELECT krs.id_krs, mahasiswa.nama, mahasiswa.nim, matakuliah.nama_matkul, matakuliah.kode_matkul
     FROM krs
     LEFT JOIN mahasiswa ON krs.id_mahasiswa = mahasiswa.id_mahasiswa
     LEFT JOIN matakuliah ON krs.id_matkul = matakuliah.id_matkul
     WHERE krs.id_krs = :id"
);
$stmt->execute([':id' => $id]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$row) {
    $jsRedirect($redirectUrl);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Konfirmasi Hapus KRS</title>
    <?php $assetPath = rtrim(BASE_URL, '/') . '/public/assets/'; ?>
    <link rel="stylesheet" href="<?= $assetPath ?>vendor/css/core.css" />
    <link rel="stylesheet" href="<?= $assetPath ?>vendor/css/theme-default.css" />
    <link rel="stylesheet" href="<?= $assetPath ?>css/demo.css" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<h4 class="fw-bold mb-3"><span class="text-muted fw-light">KRS /</span> Konfirmasi Hapus</h4>

<style>
    .delete-wrapper { min-height: 60vh; display: flex; align-items: center; justify-content: center; }
    .delete-card { max-width: 620px; margin: 0 auto; border-radius: 14px; border: 1px solid rgba(255,255,255,0.06); box-shadow: 0 18px 44px rgba(0,0,0,0.4); background: linear-gradient(145deg, rgba(12,18,31,0.96), rgba(18,26,42,0.96)); }
    .delete-meta { background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08); border-radius: 10px; }
</style>
</head>
<div class="delete-wrapper">
    <div class="card delete-card">
        <div class="card-body">
            <div class="d-flex align-items-center mb-2">
                <span class="badge bg-label-danger me-2">Peringatan</span>
                <h5 class="mb-0 text-white">Hapus KRS?</h5>
            </div>
            <p class="text-muted mb-3">Data yang dihapus tidak dapat dikembalikan.</p>
            <?php if ($errorMessage): ?>
                <div class="alert alert-danger py-2 mb-3"><?php echo htmlspecialchars($errorMessage); ?></div>
            <?php endif; ?>
            <div class="p-3 mb-3 delete-meta text-white">
                <div class="mb-1"><strong>Mahasiswa:</strong> <?php echo htmlspecialchars(($row['nim'] ?? '-') . ' - ' . ($row['nama'] ?? '-')); ?></div>
                <div><strong>Mata Kuliah:</strong> <?php echo htmlspecialchars(($row['kode_matkul'] ?? '-') . ' - ' . ($row['nama_matkul'] ?? '-')); ?></div>
            </div>
            <form method="POST" class="d-flex gap-2">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars((string)$id); ?>">
                <button type="submit" name="confirm" value="yes" class="btn btn-danger flex-fill">Ya, Hapus</button>
                <button type="submit" name="confirm" value="no" class="btn btn-outline-secondary flex-fill">Batal</button>
            </form>
        </div>
    </div>
</div>
