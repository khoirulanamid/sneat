<?php
include_once __DIR__ . '/../../config/koneksi.php';

$redirectUrl = page_url('dosen/dosen');
$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    $confirm = $_POST['confirm'] ?? '';

    if ($confirm === 'yes' && $id > 0) {
        try {
            $stmt = $pdo->prepare("DELETE FROM dosen WHERE id_dosen = :id");
            $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            $errorMessage = 'Gagal menghapus data: ' . $e->getMessage();
        }
    }

    echo '<script>window.location.href = ' . json_encode($redirectUrl) . ';</script>';
    return;
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    echo '<script>window.location.href = ' . json_encode($redirectUrl) . ';</script>';
    return;
}

$stmt = $pdo->prepare("SELECT nama_dosen, nidn FROM dosen WHERE id_dosen = :id");
$stmt->execute([':id' => $id]);
$dosen = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$dosen) {
    echo '<script>window.location.href = ' . json_encode($redirectUrl) . ';</script>';
    return;
}
?>

<h4 class="fw-bold mb-3"><span class="text-muted fw-light">Dosen /</span> Konfirmasi Hapus</h4>

<style>
    .delete-wrapper {
        min-height: 60vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .delete-card {
        max-width: 620px;
        margin: 0 auto;
        border-radius: 14px;
        border: 1px solid rgba(255,255,255,0.06);
        box-shadow: 0 16px 40px rgba(0,0,0,0.38);
        background: linear-gradient(145deg, rgba(12,18,31,0.96), rgba(18,26,42,0.96));
    }
    .delete-meta {
        background: rgba(255,255,255,0.04);
        border: 1px solid rgba(255,255,255,0.08);
        border-radius: 10px;
    }
</style>

<div class="delete-wrapper">
    <div class="card delete-card">
        <div class="card-body">
            <div class="d-flex align-items-center mb-2">
                <span class="badge bg-label-danger me-2">Peringatan</span>
                <h5 class="mb-0 text-white">Hapus Dosen?</h5>
            </div>
            <p class="text-muted mb-3">Data yang dihapus tidak dapat dikembalikan.</p>
            <?php if ($errorMessage): ?>
                <div class="alert alert-danger py-2 mb-3"><?php echo htmlspecialchars($errorMessage); ?></div>
            <?php endif; ?>
            <div class="p-3 mb-3 delete-meta text-white">
                <div class="mb-1"><strong>Nama:</strong> <?php echo htmlspecialchars($dosen['nama_dosen'] ?? '-'); ?></div>
                <div><strong>NIDN:</strong> <?php echo htmlspecialchars($dosen['nidn'] ?? '-'); ?></div>
            </div>
            <form method="POST" class="d-flex gap-2">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars((string)$id); ?>">
                <button type="submit" name="confirm" value="yes" class="btn btn-danger flex-fill">Ya, Hapus</button>
                <button type="submit" name="confirm" value="no" class="btn btn-outline-secondary flex-fill">Batal</button>
            </form>
        </div>
    </div>
</div>
