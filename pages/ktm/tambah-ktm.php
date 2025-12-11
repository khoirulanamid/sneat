<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once __DIR__ . '/../../config/koneksi.php';

$mahasiswaList = $pdo->query("SELECT id_mahasiswa, nim, nama FROM mahasiswa ORDER BY nama ASC")->fetchAll(PDO::FETCH_ASSOC);

$errorMessage = $_SESSION['tambah_ktm_error'] ?? '';
$old = $_SESSION['tambah_ktm_old'] ?? [
    'id_mahasiswa' => '',
    'nomor_kartu' => '',
    'tgl_terbit' => '',
    'masa_berlaku' => '',
    'status' => 'Aktif',
    'foto_kartu' => '',
    'keterangan' => '',
];

unset($_SESSION['tambah_ktm_error'], $_SESSION['tambah_ktm_old']);
?>

<h4 class="fw-bold"><span class="text-muted fw-light">KTM /</span> Tambah KTM</h4>

<div class="card">
    <div class="card-body">
        <?php if ($errorMessage) : ?>
            <div class="alert alert-danger" role="alert">
                <?php echo htmlspecialchars($errorMessage); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?php echo page_url('ktm/proses-tambah'); ?>" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="id_mahasiswa" class="form-label">Mahasiswa</label>
                <select class="form-select" id="id_mahasiswa" name="id_mahasiswa" required>
                    <option value="" disabled <?php echo empty($old['id_mahasiswa']) ? 'selected' : ''; ?>>Pilih Mahasiswa</option>
                    <?php foreach ($mahasiswaList as $mahasiswa) : ?>
                        <option value="<?php echo $mahasiswa['id_mahasiswa']; ?>" <?php echo ($old['id_mahasiswa'] == $mahasiswa['id_mahasiswa']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($mahasiswa['nim'] . ' - ' . $mahasiswa['nama']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="nomor_kartu" class="form-label">Nomor KTM</label>
                <input type="text" class="form-control" id="nomor_kartu" name="nomor_kartu" value="<?php echo htmlspecialchars($old['nomor_kartu']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="tgl_terbit" class="form-label">Tanggal Terbit</label>
                <input type="date" class="form-control" id="tgl_terbit" name="tgl_terbit" value="<?php echo htmlspecialchars($old['tgl_terbit']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="masa_berlaku" class="form-label">Masa Berlaku</label>
                <input type="date" class="form-control" id="masa_berlaku" name="masa_berlaku" value="<?php echo htmlspecialchars($old['masa_berlaku']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-select" id="status" name="status" required>
                    <?php
                    $statusOptions = ['Aktif', 'Tidak Aktif', 'Hilang', 'Rusak'];
                    foreach ($statusOptions as $status) :
                    ?>
                        <option value="<?php echo $status; ?>" <?php echo ($old['status'] === $status) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($status); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="foto_kartu" class="form-label">Foto KTM</label>
                <input type="file" class="form-control" id="foto_kartu" name="foto_kartu" accept="image/*">
                <div class="form-text">Opsional. Maksimal ~2MB, format jpg/png/webp.</div>
            </div>
            <div class="mb-3">
                <label for="keterangan" class="form-label">Keterangan</label>
                <textarea class="form-control" id="keterangan" name="keterangan" rows="3"><?php echo htmlspecialchars($old['keterangan']); ?></textarea>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="<?php echo page_url('ktm/ktm'); ?>" class="btn btn-secondary">Kembali</a>
            </div>
        </form>
    </div>
</div>
