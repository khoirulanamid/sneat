<?php
include_once __DIR__ . '/../../config/koneksi.php';

$redirectUrl = page_url('mahasiswa/mahasiswa');
$jsRedirect = function (string $url) {
    echo '<script>window.location.href = ' . json_encode($url) . ';</script>';
    exit;
};

$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nim = isset($_POST['nim']) ? trim($_POST['nim']) : '';
    $confirm = $_POST['confirm'] ?? '';

    if ($confirm === 'yes' && $nim !== '') {
        try {
            $stmt = $pdo->prepare("DELETE FROM mahasiswa WHERE nim = :nim");
            $stmt->execute([':nim' => $nim]);
        } catch (PDOException $e) {
            $errorMessage = 'Gagal menghapus data: ' . $e->getMessage();
        }
    }

    $jsRedirect($redirectUrl);
}

$nim = isset($_GET['nim']) ? trim($_GET['nim']) : '';
if ($nim === '') {
    $jsRedirect($redirectUrl);
}

$stmt = $pdo->prepare("SELECT nama, nim, jurusan FROM mahasiswa WHERE nim = :nim");
$stmt->execute([':nim' => $nim]);
$mahasiswa = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$mahasiswa) {
    $jsRedirect($redirectUrl);
}
?>

<h4 class="fw-bold mb-3"><span class="text-muted fw-light">Mahasiswa /</span> Konfirmasi Hapus</h4>

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
                <h5 class="mb-0 text-white">Hapus Mahasiswa?</h5>
            </div>
            <p class="text-muted mb-3">Data yang dihapus tidak dapat dikembalikan.</p>
            <?php if ($errorMessage): ?>
                <div class="alert alert-danger py-2 mb-3"><?php echo htmlspecialchars($errorMessage); ?></div>
            <?php endif; ?>
            <div class="p-3 mb-3 delete-meta text-white">
                <div class="mb-1"><strong>Nama:</strong> <?php echo htmlspecialchars($mahasiswa['nama'] ?? '-'); ?></div>
                <div class="mb-1"><strong>NIM:</strong> <?php echo htmlspecialchars($mahasiswa['nim'] ?? '-'); ?></div>
                <div><strong>Jurusan:</strong> <?php echo htmlspecialchars($mahasiswa['jurusan'] ?? '-'); ?></div>
            </div>
            <form method="POST" class="d-flex gap-2">
                <input type="hidden" name="nim" value="<?php echo htmlspecialchars($nim); ?>">
                <button type="submit" name="confirm" value="yes" class="btn btn-danger flex-fill">Ya, Hapus</button>
                <button type="submit" name="confirm" value="no" class="btn btn-outline-secondary flex-fill">Batal</button>
            </form>
        </div>
    </div>
</div>
