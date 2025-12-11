<?php
include 'config/koneksi.php';

$kodeMatkul = isset($_GET['kode']) ? trim($_GET['kode']) : '';
$errorMessage = $_SESSION['edit_matkul_error'] ?? '';
unset($_SESSION['edit_matkul_error']);
$matakuliah = null;

// Ambil daftar dosen untuk dropdown pengampu
$dosenList = [];
try {
    $dosenStmt = $pdo->query("SELECT id_dosen, nama_dosen FROM dosen ORDER BY nama_dosen ASC");
    $dosenList = $dosenStmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // biarkan kosong jika gagal
}

if ($kodeMatkul !== '') {
$stmt = $pdo->prepare(
    "SELECT * FROM matakuliah WHERE kode_matkul = :kode_matkul LIMIT 1"
);
$stmt->execute([':kode_matkul' => $kodeMatkul]);
$matakuliah = $stmt->fetch(PDO::FETCH_ASSOC);
} else {
    $errorMessage = 'Kode mata kuliah tidak valid.';
}

// Proses update dipindah ke pages/matakuliah/proses-update.php
?>

<h4 class="fw-bold"><span class="text-muted fw-light">Mata Kuliah /</span> Edit Mata Kuliah</h4>

<div class="card">
    <div class="card-body">
        <?php if ($errorMessage) : ?>
            <div class="alert alert-danger" role="alert">
                <?php echo htmlspecialchars($errorMessage); ?>
            </div>
        <?php endif; ?>

        <?php if ($matakuliah) : ?>
            <form method="POST" action="<?php echo page_url('matakuliah/proses-update'); ?>">
                <input type="hidden" name="kode_matkul" value="<?php echo htmlspecialchars($kodeMatkul); ?>">
                <div class="mb-3">
                    <label for="kode_matkul" class="form-label">Kode Mata Kuliah</label>
                    <input type="text" class="form-control" id="kode_matkul" name="kode_matkul" value="<?php echo htmlspecialchars($matakuliah['kode_matkul'] ?? ''); ?>" disabled>
                </div>
                <div class="mb-3">
                    <label for="nama_matkul" class="form-label">Nama Mata Kuliah</label>
                    <input type="text" class="form-control" id="nama_matkul" name="nama_matkul" value="<?php echo htmlspecialchars($matakuliah['nama_matkul'] ?? ''); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="sks" class="form-label">SKS</label>
                    <input type="number" class="form-control" id="sks" name="sks" min="0" value="<?php echo htmlspecialchars($matakuliah['sks'] ?? ''); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="semester" class="form-label">Semester</label>
                    <input type="number" class="form-control" id="semester" name="semester" min="1" max="14" value="<?php echo htmlspecialchars($matakuliah['semester'] ?? ''); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="id_dosen" class="form-label">Dosen Pengampu (Opsional)</label>
                    <select class="form-select" id="id_dosen" name="id_dosen">
                        <option value="" <?php echo empty($matakuliah['id_dosen']) ? 'selected' : ''; ?>>-- Pilih Dosen --</option>
                        <?php foreach ($dosenList as $dosen) : ?>
                            <option value="<?php echo htmlspecialchars($dosen['id_dosen']); ?>" <?php echo (isset($matakuliah['id_dosen']) && (string)$matakuliah['id_dosen'] === (string)$dosen['id_dosen']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($dosen['nama_dosen']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="jenis_matkul" class="form-label">Jenis Mata Kuliah</label>
                    <select class="form-select" id="jenis_matkul" name="jenis_matkul" required>
                        <option value="" disabled <?php echo empty($matakuliah['jenis_matkul']) ? 'selected' : ''; ?>>Pilih Jenis</option>
                        <option value="Wajib" <?php echo (isset($matakuliah['jenis_matkul']) && $matakuliah['jenis_matkul'] === 'Wajib') ? 'selected' : ''; ?>>Wajib</option>
                        <option value="Pilihan" <?php echo (isset($matakuliah['jenis_matkul']) && $matakuliah['jenis_matkul'] === 'Pilihan') ? 'selected' : ''; ?>>Pilihan</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status" required>
                        <option value="" disabled <?php echo empty($matakuliah['status']) ? 'selected' : ''; ?>>Pilih Status</option>
                        <option value="Aktif" <?php echo (isset($matakuliah['status']) && $matakuliah['status'] === 'Aktif') ? 'selected' : ''; ?>>Aktif</option>
                        <option value="Tidak Aktif" <?php echo (isset($matakuliah['status']) && $matakuliah['status'] === 'Tidak Aktif') ? 'selected' : ''; ?>>Tidak Aktif</option>
                    </select>
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    <a href="<?php echo page_url('matakuliah/matakuliah'); ?>" class="btn btn-secondary">Kembali</a>
                </div>
            </form>
        <?php else : ?>
            <a href="<?php echo page_url('matakuliah/matakuliah'); ?>" class="btn btn-secondary">Kembali</a>
        <?php endif; ?>
    </div>
</div>
