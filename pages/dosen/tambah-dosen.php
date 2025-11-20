<?php
session_start();

$errorMessage = $_SESSION['tambah_dosen_error'] ?? '';
$old = $_SESSION['tambah_dosen_old'] ?? [
    'nidn' => '',
    'nip' => '',
    'nama_dosen' => '',
    'jenis_kelamin' => '',
    'tempat_lahir' => '',
    'tanggal_lahir' => '',
    'keahlian' => '',
    'jabatan_akademik' => 'Asisten Ahli',
    'pendidikan_terakhir' => '',
    'email' => '',
    'no_hp' => '',
    'alamat' => '',
    'status_dosen' => 'Tetap',
];

unset($_SESSION['tambah_dosen_error'], $_SESSION['tambah_dosen_old']);
?>

<h4 class="fw-bold"><span class="text-muted fw-light">Dosen /</span> Tambah Dosen</h4>

<div class="card">
    <div class="card-body">
        <?php if ($errorMessage) : ?>
            <div class="alert alert-danger" role="alert">
                <?php echo htmlspecialchars($errorMessage); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?php echo page_url('dosen/proses'); ?>">
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="nidn" class="form-label">NIDN</label>
                    <input type="text" class="form-control" id="nidn" name="nidn" value="<?php echo htmlspecialchars($old['nidn']); ?>" required>
                </div>
                <div class="col-md-6">
                    <label for="nip" class="form-label">NIP</label>
                    <input type="text" class="form-control" id="nip" name="nip" value="<?php echo htmlspecialchars($old['nip']); ?>">
                </div>
                <div class="col-md-6">
                    <label for="nama_dosen" class="form-label">Nama Dosen</label>
                    <input type="text" class="form-control" id="nama_dosen" name="nama_dosen" value="<?php echo htmlspecialchars($old['nama_dosen']); ?>" required>
                </div>
                <div class="col-md-6">
                    <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                    <select class="form-select" id="jenis_kelamin" name="jenis_kelamin" required>
                        <option value="" disabled <?php echo empty($old['jenis_kelamin']) ? 'selected' : ''; ?>>Pilih Jenis Kelamin</option>
                        <option value="Laki-laki" <?php echo ($old['jenis_kelamin'] === 'Laki-laki') ? 'selected' : ''; ?>>Laki-laki</option>
                        <option value="Perempuan" <?php echo ($old['jenis_kelamin'] === 'Perempuan') ? 'selected' : ''; ?>>Perempuan</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="tempat_lahir" class="form-label">Tempat Lahir</label>
                    <input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir" value="<?php echo htmlspecialchars($old['tempat_lahir']); ?>">
                </div>
                <div class="col-md-6">
                    <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                    <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" value="<?php echo htmlspecialchars($old['tanggal_lahir']); ?>">
                </div>
                <div class="col-md-6">
                    <label for="keahlian" class="form-label">Keahlian</label>
                    <input type="text" class="form-control" id="keahlian" name="keahlian" value="<?php echo htmlspecialchars($old['keahlian']); ?>" required>
                </div>
                <div class="col-md-6">
                    <label for="jabatan_akademik" class="form-label">Jabatan Akademik</label>
                    <select class="form-select" id="jabatan_akademik" name="jabatan_akademik">
                        <option value="Asisten Ahli" <?php echo ($old['jabatan_akademik'] === 'Asisten Ahli') ? 'selected' : ''; ?>>Asisten Ahli</option>
                        <option value="Lektor" <?php echo ($old['jabatan_akademik'] === 'Lektor') ? 'selected' : ''; ?>>Lektor</option>
                        <option value="Lektor Kepala" <?php echo ($old['jabatan_akademik'] === 'Lektor Kepala') ? 'selected' : ''; ?>>Lektor Kepala</option>
                        <option value="Guru Besar" <?php echo ($old['jabatan_akademik'] === 'Guru Besar') ? 'selected' : ''; ?>>Guru Besar</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="pendidikan_terakhir" class="form-label">Pendidikan Terakhir</label>
                    <select class="form-select" id="pendidikan_terakhir" name="pendidikan_terakhir" required>
                        <option value="" disabled <?php echo empty($old['pendidikan_terakhir']) ? 'selected' : ''; ?>>Pilih Pendidikan</option>
                        <option value="S1" <?php echo ($old['pendidikan_terakhir'] === 'S1') ? 'selected' : ''; ?>>S1</option>
                        <option value="S2" <?php echo ($old['pendidikan_terakhir'] === 'S2') ? 'selected' : ''; ?>>S2</option>
                        <option value="S3" <?php echo ($old['pendidikan_terakhir'] === 'S3') ? 'selected' : ''; ?>>S3</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($old['email']); ?>">
                </div>
                <div class="col-md-6">
                    <label for="no_hp" class="form-label">No HP</label>
                    <input type="text" class="form-control" id="no_hp" name="no_hp" value="<?php echo htmlspecialchars($old['no_hp']); ?>">
                </div>
                <div class="col-md-6">
                    <label for="status_dosen" class="form-label">Status</label>
                    <select class="form-select" id="status_dosen" name="status_dosen" required>
                        <option value="" disabled <?php echo empty($old['status_dosen']) ? 'selected' : ''; ?>>Pilih Status</option>
                        <option value="Tetap" <?php echo ($old['status_dosen'] === 'Tetap') ? 'selected' : ''; ?>>Tetap</option>
                        <option value="Kontrak" <?php echo ($old['status_dosen'] === 'Kontrak') ? 'selected' : ''; ?>>Kontrak</option>
                        <option value="LB" <?php echo ($old['status_dosen'] === 'LB') ? 'selected' : ''; ?>>LB</option>
                    </select>
                </div>
            </div>
            <div class="mt-3 mb-3">
                <label for="alamat" class="form-label">Alamat</label>
                <textarea class="form-control" id="alamat" name="alamat" rows="3"><?php echo htmlspecialchars($old['alamat']); ?></textarea>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="<?php echo page_url('dosen/dosen'); ?>" class="btn btn-secondary">Kembali</a>
            </div>
        </form>
    </div>
</div>
