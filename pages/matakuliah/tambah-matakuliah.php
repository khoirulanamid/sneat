<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'config/koneksi.php';

// Ambil daftar dosen untuk dropdown pengampu
$dosenList = [];
try {
    $dosenStmt = $pdo->query("SELECT id_dosen, nama_dosen FROM dosen ORDER BY nama_dosen ASC");
    $dosenList = $dosenStmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Biarkan $dosenList kosong jika gagal, form tetap bisa dipakai tanpa dosen pengampu
}

$errorMessage = $_SESSION['tambah_matakuliah_error'] ?? '';
$old = $_SESSION['tambah_matakuliah_old'] ?? [
    'kode_matkul' => '',
    'nama_matkul' => '',
    'sks' => '',
    'semester' => '',
    'id_dosen' => '',
    'jenis_matkul' => '',
    'status' => 'Aktif',
];

unset($_SESSION['tambah_matakuliah_error'], $_SESSION['tambah_matakuliah_old']);
?>

<h4 class="fw-bold"><span class="text-muted fw-light">Mata Kuliah /</span> Tambah Mata Kuliah</h4>

<div class="card">
    <div class="card-body">
        <?php if ($errorMessage) : ?>
            <div class="alert alert-danger" role="alert">
                <?php echo htmlspecialchars($errorMessage); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?php echo $base_url; ?>pages/matakuliah/proses.php">
            <div class="mb-3">
                <label for="kode_matkul" class="form-label">Kode Mata Kuliah</label>
                <input type="text" class="form-control" id="kode_matkul" name="kode_matkul" value="<?php echo htmlspecialchars($old['kode_matkul']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="nama_matkul" class="form-label">Nama Mata Kuliah</label>
                <input type="text" class="form-control" id="nama_matkul" name="nama_matkul" value="<?php echo htmlspecialchars($old['nama_matkul']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="sks" class="form-label">SKS</label>
                <input type="number" class="form-control" id="sks" name="sks" min="0" value="<?php echo htmlspecialchars($old['sks']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="semester" class="form-label">Semester</label>
                <input type="number" class="form-control" id="semester" name="semester" min="1" max="14" value="<?php echo htmlspecialchars($old['semester']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="id_dosen" class="form-label">Dosen Pengampu (Opsional)</label>
                <select class="form-select" id="id_dosen" name="id_dosen">
                    <option value="" <?php echo $old['id_dosen'] === '' ? 'selected' : ''; ?>>-- Pilih Dosen --</option>
                    <?php foreach ($dosenList as $dosen) : ?>
                        <option value="<?php echo htmlspecialchars($dosen['id_dosen']); ?>" <?php echo (string)$old['id_dosen'] === (string)$dosen['id_dosen'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($dosen['nama_dosen']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="jenis_matkul" class="form-label">Jenis Mata Kuliah</label>
                <select class="form-select" id="jenis_matkul" name="jenis_matkul" required>
                    <option value="" disabled <?php echo empty($old['jenis_matkul']) ? 'selected' : ''; ?>>Pilih Jenis</option>
                    <option value="Wajib" <?php echo $old['jenis_matkul'] === 'Wajib' ? 'selected' : ''; ?>>Wajib</option>
                    <option value="Pilihan" <?php echo $old['jenis_matkul'] === 'Pilihan' ? 'selected' : ''; ?>>Pilihan</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-select" id="status" name="status" required>
                    <option value="" disabled <?php echo empty($old['status']) ? 'selected' : ''; ?>>Pilih Status</option>
                    <option value="Aktif" <?php echo $old['status'] === 'Aktif' ? 'selected' : ''; ?>>Aktif</option>
                    <option value="Tidak Aktif" <?php echo $old['status'] === 'Tidak Aktif' ? 'selected' : ''; ?>>Tidak Aktif</option>
                </select>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="<?php echo page_url('matakuliah/matakuliah'); ?>" class="btn btn-secondary">Kembali</a>
            </div>
        </form>
    </div>
</div>
