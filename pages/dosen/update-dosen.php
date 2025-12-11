<?php
include 'config/koneksi.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$errorMessage = $_SESSION['edit_dosen_error'] ?? '';
unset($_SESSION['edit_dosen_error']);
$dosen = null;

if ($id > 0) {
    $stmt = $pdo->prepare("SELECT * FROM dosen WHERE id_dosen = :id");
    $stmt->execute([':id' => $id]);
    $dosen = $stmt->fetch(PDO::FETCH_ASSOC);
} else {
    $errorMessage = 'ID tidak valid.';
}

// Proses update dipindah ke pages/dosen/proses-update.php
?>

<h4 class="fw-bold"><span class="text-muted fw-light">Dosen /</span> Edit Dosen</h4>

<div class="card">
    <div class="card-body">
        <?php if ($errorMessage) : ?>
            <div class="alert alert-danger" role="alert">
                <?php echo htmlspecialchars($errorMessage); ?>
            </div>
        <?php endif; ?>

<?php if ($dosen) : ?>
            <form method="POST" action="<?php echo page_url('dosen/proses-update'); ?>" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars((string)$id); ?>">
                <?php $fotoPreview = !empty($dosen['foto']) ? asset_url($dosen['foto']) : ''; ?>
                <div class="row g-3">
                    <div class="col-lg-6">
                        <div class="p-3 border rounded h-100">
                            <h6 class="fw-semibold mb-3">Data Personal</h6>
                            <div class="mb-3">
                                <label for="nidn" class="form-label">NIDN</label>
                                <input type="text" class="form-control" id="nidn" name="nidn" value="<?php echo htmlspecialchars($dosen['nidn'] ?? ''); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="nip" class="form-label">NIP</label>
                                <input type="text" class="form-control" id="nip" name="nip" value="<?php echo htmlspecialchars($dosen['nip'] ?? ''); ?>">
                            </div>
                            <div class="mb-3">
                                <label for="nama_dosen" class="form-label">Nama Dosen</label>
                                <input type="text" class="form-control" id="nama_dosen" name="nama_dosen" value="<?php echo htmlspecialchars($dosen['nama_dosen'] ?? ''); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                                <select class="form-select" id="jenis_kelamin" name="jenis_kelamin" required>
                                    <option value="" disabled <?php echo empty($dosen['jenis_kelamin']) ? 'selected' : ''; ?>>Pilih Jenis Kelamin</option>
                                    <option value="Laki-laki" <?php echo (isset($dosen['jenis_kelamin']) && $dosen['jenis_kelamin'] === 'Laki-laki') ? 'selected' : ''; ?>>Laki-laki</option>
                                    <option value="Perempuan" <?php echo (isset($dosen['jenis_kelamin']) && $dosen['jenis_kelamin'] === 'Perempuan') ? 'selected' : ''; ?>>Perempuan</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="tempat_lahir" class="form-label">Tempat Lahir</label>
                                <input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir" value="<?php echo htmlspecialchars($dosen['tempat_lahir'] ?? ''); ?>">
                            </div>
                            <div class="mb-3">
                                <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                                <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" value="<?php echo htmlspecialchars($dosen['tanggal_lahir'] ?? ''); ?>">
                            </div>
                            <div class="mb-0">
                                <label for="alamat" class="form-label">Alamat</label>
                                <textarea class="form-control" id="alamat" name="alamat" rows="3"><?php echo htmlspecialchars($dosen['alamat'] ?? ''); ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="foto">Foto Dosen (opsional)</label>
                                <?php if (!empty($dosen['foto'])) : ?>
                                    <div class="mb-2">
                                        <img src="<?php echo htmlspecialchars($fotoPreview); ?>" alt="Foto Dosen" class="img-thumbnail" style="max-width: 180px;">
                                    </div>
                                <?php endif; ?>
                                <input type="file" class="form-control" id="foto" name="foto" accept="image/*">
                                <div class="form-text">Maksimal ~2MB, format jpg/png/webp.</div>
                                <input type="hidden" name="foto_lama" value="<?php echo htmlspecialchars($dosen['foto'] ?? ''); ?>">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="p-3 border rounded h-100">
                            <h6 class="fw-semibold mb-3">Data Akademik & Kontak</h6>
                            <div class="mb-3">
                                <label for="keahlian" class="form-label">Keahlian</label>
                                <input type="text" class="form-control" id="keahlian" name="keahlian" value="<?php echo htmlspecialchars($dosen['keahlian'] ?? ''); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="jabatan_akademik" class="form-label">Jabatan Akademik</label>
                                <input type="text" class="form-control" id="jabatan_akademik" name="jabatan_akademik" value="<?php echo htmlspecialchars($dosen['jabatan_akademik'] ?? ''); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="pendidikan_terakhir" class="form-label">Pendidikan Terakhir</label>
                                <select class="form-select" id="pendidikan_terakhir" name="pendidikan_terakhir" required>
                                    <option value="" disabled <?php echo empty($dosen['pendidikan_terakhir']) ? 'selected' : ''; ?>>Pilih Pendidikan</option>
                                    <option value="S1" <?php echo (isset($dosen['pendidikan_terakhir']) && $dosen['pendidikan_terakhir'] === 'S1') ? 'selected' : ''; ?>>S1</option>
                                    <option value="S2" <?php echo (isset($dosen['pendidikan_terakhir']) && $dosen['pendidikan_terakhir'] === 'S2') ? 'selected' : ''; ?>>S2</option>
                                    <option value="S3" <?php echo (isset($dosen['pendidikan_terakhir']) && $dosen['pendidikan_terakhir'] === 'S3') ? 'selected' : ''; ?>>S3</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($dosen['email'] ?? ''); ?>">
                            </div>
                            <div class="mb-3">
                                <label for="no_hp" class="form-label">No HP</label>
                                <input type="text" class="form-control" id="no_hp" name="no_hp" value="<?php echo htmlspecialchars($dosen['no_hp'] ?? ''); ?>">
                            </div>
                            <div class="mb-0">
                                <label for="status_dosen" class="form-label">Status</label>
                                <select class="form-select" id="status_dosen" name="status_dosen" required>
                                    <option value="" disabled <?php echo empty($dosen['status_dosen']) ? 'selected' : ''; ?>>Pilih Status</option>
                                    <option value="Tetap" <?php echo (isset($dosen['status_dosen']) && $dosen['status_dosen'] === 'Tetap') ? 'selected' : ''; ?>>Tetap</option>
                                    <option value="Kontrak" <?php echo (isset($dosen['status_dosen']) && $dosen['status_dosen'] === 'Kontrak') ? 'selected' : ''; ?>>Kontrak</option>
                                    <option value="Luar" <?php echo (isset($dosen['status_dosen']) && $dosen['status_dosen'] === 'Luar') ? 'selected' : ''; ?>>Luar Biasa</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex gap-2 mt-3">
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    <a href="<?php echo page_url('dosen/dosen'); ?>" class="btn btn-secondary">Kembali</a>
                </div>
            </form>
        <?php else : ?>
            <a href="<?php echo page_url('dosen/dosen'); ?>" class="btn btn-secondary">Kembali</a>
        <?php endif; ?>
    </div>
</div>
