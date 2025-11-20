<?php
session_start();

$errorMessage = $_SESSION['tambah_mahasiswa_error'] ?? '';
$old = $_SESSION['tambah_mahasiswa_old'] ?? [
    'nim' => '',
    'nama' => '',
    'jenis_kelamin' => '',
    'jurusan' => '',
    'tahun_masuk' => '',
    'status' => 'Aktif',
];

unset($_SESSION['tambah_mahasiswa_error'], $_SESSION['tambah_mahasiswa_old']);
?>

<h4 class="fw-bold"><span class="text-muted fw-light">Mahasiswa /</span> Tambah Mahasiswa</h4>

<div class="card">
    <div class="card-body">
        <?php if ($errorMessage) : ?>
            <div class="alert alert-danger" role="alert">
                <?php echo htmlspecialchars($errorMessage); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?php echo page_url('mahasiswa/proses'); ?>">
            <div class="mb-3">
                <label for="nim" class="form-label">NIM</label>
                <input type="text" class="form-control" id="nim" name="nim" value="<?php echo htmlspecialchars($old['nim']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="nama" class="form-label">Nama</label>
                <input type="text" class="form-control" id="nama" name="nama" value="<?php echo htmlspecialchars($old['nama']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                <select class="form-select" id="jenis_kelamin" name="jenis_kelamin" required>
                    <option value="" disabled <?php echo empty($old['jenis_kelamin']) ? 'selected' : ''; ?>>Pilih Jenis Kelamin</option>
                    <option value="Laki-laki" <?php echo ($old['jenis_kelamin'] === 'Laki-laki') ? 'selected' : ''; ?>>Laki-laki</option>
                    <option value="Perempuan" <?php echo ($old['jenis_kelamin'] === 'Perempuan') ? 'selected' : ''; ?>>Perempuan</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="jurusan" class="form-label">Jurusan</label>
                <input type="text" class="form-control" id="jurusan" name="jurusan" value="<?php echo htmlspecialchars($old['jurusan']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="tahun_masuk" class="form-label">Tahun Masuk</label>
                <input type="number" class="form-control" id="tahun_masuk" name="tahun_masuk" min="1900" max="2100" value="<?php echo htmlspecialchars($old['tahun_masuk']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-select" id="status" name="status" required>
                    <option value="" disabled <?php echo empty($old['status']) ? 'selected' : ''; ?>>Pilih Status</option>
                    <option value="Aktif" <?php echo ($old['status'] === 'Aktif') ? 'selected' : ''; ?>>Aktif</option>
                    <option value="Cuti" <?php echo ($old['status'] === 'Cuti') ? 'selected' : ''; ?>>Cuti</option>
                    <option value="Lulus" <?php echo ($old['status'] === 'Lulus') ? 'selected' : ''; ?>>Lulus</option>
                    <option value="DO" <?php echo ($old['status'] === 'DO') ? 'selected' : ''; ?>>DO</option>
                </select>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="<?php echo page_url('mahasiswa/mahasiswa'); ?>" class="btn btn-secondary">Kembali</a>
            </div>
        </form>
    </div>
</div>
