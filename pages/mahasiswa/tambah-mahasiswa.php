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
    'tempat_lahir' => '',
    'tanggal_lahir' => '',
    'email' => '',
    'no_hp' => '',
    'alamat' => '',
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
            <div class="row g-3">
                <div class="col-lg-6">
                    <div class="p-3 border rounded h-100">
                        <h6 class="fw-semibold mb-3">Data Personal</h6>
                        <div class="mb-3">
                            <label for="nim" class="form-label">NIM</label>
                            <input type="text" class="form-control" id="nim" name="nim" value="<?php echo htmlspecialchars($old['nim']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="nama" name="nama" value="<?php echo htmlspecialchars($old['nama']); ?>" required>
                        </div>
                        <div class="mb-0">
                            <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                            <select class="form-select" id="jenis_kelamin" name="jenis_kelamin" required>
                                <option value="" disabled <?php echo empty($old['jenis_kelamin']) ? 'selected' : ''; ?>>Pilih Jenis Kelamin</option>
                                <option value="Laki-laki" <?php echo ($old['jenis_kelamin'] === 'Laki-laki') ? 'selected' : ''; ?>>Laki-laki</option>
                                <option value="Perempuan" <?php echo ($old['jenis_kelamin'] === 'Perempuan') ? 'selected' : ''; ?>>Perempuan</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="tempat_lahir" class="form-label">Tempat Lahir</label>
                            <input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir" value="<?php echo htmlspecialchars($old['tempat_lahir']); ?>">
                        </div>
                        <div class="mb-3">
                            <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                            <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" value="<?php echo htmlspecialchars($old['tanggal_lahir']); ?>">
                        </div>
                        <div class="mb-0">
                            <label for="alamat" class="form-label">Alamat</label>
                            <textarea class="form-control" id="alamat" name="alamat" rows="3"><?php echo htmlspecialchars($old['alamat']); ?></textarea>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="p-3 border rounded h-100">
                        <h6 class="fw-semibold mb-3">Data Akademik</h6>
                        <div class="mb-3">
                            <label for="jurusan" class="form-label">Jurusan</label>
                            <input type="text" class="form-control" id="jurusan" name="jurusan" value="<?php echo htmlspecialchars($old['jurusan']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="tahun_masuk" class="form-label">Tahun Masuk</label>
                            <input type="number" class="form-control" id="tahun_masuk" name="tahun_masuk" min="1900" max="2100" value="<?php echo htmlspecialchars($old['tahun_masuk']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($old['email']); ?>">
                        </div>
                        <div class="mb-3">
                            <label for="no_hp" class="form-label">No HP</label>
                            <input type="text" class="form-control" id="no_hp" name="no_hp" value="<?php echo htmlspecialchars($old['no_hp']); ?>">
                        </div>
                        <div class="mb-0">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="" disabled <?php echo empty($old['status']) ? 'selected' : ''; ?>>Pilih Status</option>
                                <option value="Aktif" <?php echo ($old['status'] === 'Aktif') ? 'selected' : ''; ?>>Aktif</option>
                                <option value="Cuti" <?php echo ($old['status'] === 'Cuti') ? 'selected' : ''; ?>>Cuti</option>
                                <option value="Lulus" <?php echo ($old['status'] === 'Lulus') ? 'selected' : ''; ?>>Lulus</option>
                                <option value="DO" <?php echo ($old['status'] === 'DO') ? 'selected' : ''; ?>>DO</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex gap-2 mt-3">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="<?php echo page_url('mahasiswa/mahasiswa'); ?>" class="btn btn-secondary">Kembali</a>
            </div>
        </form>
    </div>
</div>
