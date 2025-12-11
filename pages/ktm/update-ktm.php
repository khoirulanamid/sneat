<?php
include 'config/koneksi.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$errorMessage = $_SESSION['edit_ktm_error'] ?? '';
unset($_SESSION['edit_ktm_error']);
$ktm = null;
$allowedStatus = ['Aktif', 'Tidak Aktif', 'Hilang', 'Rusak'];

if ($id > 0) {
    $stmt = $pdo->prepare(
        "SELECT ktm.*, mahasiswa.nim, mahasiswa.nama
         FROM ktm
         LEFT JOIN mahasiswa ON ktm.id_mahasiswa = mahasiswa.id_mahasiswa
         WHERE id_ktm = :id"
    );
    $stmt->execute([':id' => $id]);
    $ktm = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$ktm) {
        $errorMessage = 'Data KTM tidak ditemukan.';
    }
} else {
    $errorMessage = 'ID KTM tidak valid.';
}

$mahasiswaList = $pdo->query("SELECT id_mahasiswa, nim, nama FROM mahasiswa ORDER BY nama ASC")->fetchAll(PDO::FETCH_ASSOC);
?>

<h4 class="fw-bold"><span class="text-muted fw-light">KTM /</span> Edit KTM</h4>

<div class="card">
    <div class="card-body">
        <?php if ($errorMessage) : ?>
            <div class="alert alert-<?php echo $ktm ? 'danger' : 'warning'; ?>" role="alert">
                <?php echo htmlspecialchars($errorMessage); ?>
            </div>
        <?php endif; ?>

        <?php if ($ktm) : ?>
            <form method="POST" action="<?php echo page_url('ktm/proses-update'); ?>" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars((string)$id); ?>">
                <div class="mb-3">
                    <label for="id_mahasiswa" class="form-label">Mahasiswa</label>
                    <select class="form-select" id="id_mahasiswa" name="id_mahasiswa" required>
                        <option value="" disabled>Pilih Mahasiswa</option>
                        <?php foreach ($mahasiswaList as $mahasiswa) : ?>
                            <option value="<?php echo $mahasiswa['id_mahasiswa']; ?>" <?php echo ($ktm['id_mahasiswa'] == $mahasiswa['id_mahasiswa']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($mahasiswa['nim'] . ' - ' . $mahasiswa['nama']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="nomor_kartu" class="form-label">Nomor KTM</label>
                    <input type="text" class="form-control" id="nomor_kartu" name="nomor_kartu" value="<?php echo htmlspecialchars($ktm['nomor_kartu']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="tgl_terbit" class="form-label">Tanggal Terbit</label>
                    <input type="date" class="form-control" id="tgl_terbit" name="tgl_terbit" value="<?php echo htmlspecialchars($ktm['tgl_terbit']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="masa_berlaku" class="form-label">Masa Berlaku</label>
                    <input type="date" class="form-control" id="masa_berlaku" name="masa_berlaku" value="<?php echo htmlspecialchars($ktm['masa_berlaku']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status" required>
                        <?php foreach ($allowedStatus as $opt) : ?>
                            <option value="<?php echo $opt; ?>" <?php echo ($ktm['status'] === $opt) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($opt); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="foto_kartu" class="form-label">Foto KTM</label>
                    <?php if (!empty($ktm['foto_kartu'])) : ?>
                        <div class="mb-2">
                            <img src="<?php echo htmlspecialchars($ktm['foto_kartu']); ?>" alt="Foto KTM" class="img-fluid rounded border" style="max-width: 220px;">
                        </div>
                    <?php endif; ?>
                    <input type="file" class="form-control" id="foto_kartu" name="foto_kartu" accept="image/*">
                    <div class="form-text">Biarkan kosong jika tidak ingin mengganti. Maksimal ~2MB, format jpg/png/webp.</div>
                    <input type="hidden" name="foto_kartu_lama" value="<?php echo htmlspecialchars($ktm['foto_kartu']); ?>">
                </div>
                <div class="mb-3">
                    <label for="keterangan" class="form-label">Keterangan</label>
                    <textarea class="form-control" id="keterangan" name="keterangan" rows="3"><?php echo htmlspecialchars($ktm['keterangan']); ?></textarea>
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    <a href="<?php echo page_url('ktm/ktm'); ?>" class="btn btn-secondary">Kembali</a>
                </div>
            </form>
        <?php else : ?>
            <a href="<?php echo page_url('ktm/ktm'); ?>" class="btn btn-secondary">Kembali</a>
        <?php endif; ?>
    </div>
</div>
