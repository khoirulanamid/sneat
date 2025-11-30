<?php
include_once __DIR__ . '/../../config/koneksi.php';

$redirectUrl = page_url('matakuliah/matakuliah');
$jsRedirect = function (string $url) {
    echo '<script>window.location.href = ' . json_encode($url) . ';</script>';
    exit;
};

$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kodeMatkul = isset($_POST['kode']) ? trim($_POST['kode']) : '';
    $confirm = $_POST['confirm'] ?? '';

    if ($confirm === 'yes' && $kodeMatkul !== '') {
        try {
            $stmt = $pdo->prepare("DELETE FROM matakuliah WHERE kode_matkul = :kode_matkul");
            $stmt->execute([':kode_matkul' => $kodeMatkul]);
        } catch (PDOException $e) {
            $errorMessage = 'Gagal menghapus data: ' . $e->getMessage();
        }
    }

    $jsRedirect($redirectUrl);
}

$kodeMatkul = isset($_GET['kode']) ? trim($_GET['kode']) : '';
if ($kodeMatkul === '') {
    $jsRedirect($redirectUrl);
}

$stmt = $pdo->prepare("SELECT kode_matkul, nama_matkul, sks FROM matakuliah WHERE kode_matkul = :kode");
$stmt->execute([':kode' => $kodeMatkul]);
$matkul = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$matkul) {
    $jsRedirect($redirectUrl);
}
?>

<h4 class="fw-bold mb-3"><span class="text-muted fw-light">Mata Kuliah /</span> Konfirmasi Hapus</h4>

<style>
    .delete-wrapper { min-height: 60vh; display: flex; align-items: center; justify-content: center; }
    .delete-card { max-width: 620px; margin: 0 auto; border-radius: 14px; border: 1px solid rgba(255,255,255,0.06); box-shadow: 0 18px 44px rgba(0,0,0,0.4); background: linear-gradient(145deg, rgba(12,18,31,0.96), rgba(18,26,42,0.96)); }
    .delete-meta { background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08); border-radius: 10px; }
</style>

<div class="delete-wrapper">
    <div class="card delete-card">
        <div class="card-body">
            <div class="d-flex align-items-center mb-2">
                <span class="badge bg-label-danger me-2">Peringatan</span>
                <h5 class="mb-0 text-white">Hapus Mata Kuliah?</h5>
            </div>
            <p class="text-muted mb-3">Data yang dihapus tidak dapat dikembalikan.</p>
            <?php if ($errorMessage): ?>
                <div class="alert alert-danger py-2 mb-3"><?php echo htmlspecialchars($errorMessage); ?></div>
            <?php endif; ?>
            <div class="p-3 mb-3 delete-meta text-white">
                <div class="mb-1"><strong>Kode:</strong> <?php echo htmlspecialchars($matkul['kode_matkul'] ?? '-'); ?></div>
                <div class="mb-1"><strong>Nama:</strong> <?php echo htmlspecialchars($matkul['nama_matkul'] ?? '-'); ?></div>
                <div><strong>SKS:</strong> <?php echo htmlspecialchars($matkul['sks'] ?? '-'); ?></div>
            </div>
            <form method="POST" class="d-flex gap-2">
                <input type="hidden" name="kode" value="<?php echo htmlspecialchars($kodeMatkul); ?>">
                <button type="submit" name="confirm" value="yes" class="btn btn-danger flex-fill">Ya, Hapus</button>
                <button type="submit" name="confirm" value="no" class="btn btn-outline-secondary flex-fill">Batal</button>
            </form>
        </div>
    </div>
</div>
