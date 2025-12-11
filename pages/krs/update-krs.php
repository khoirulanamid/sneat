<?php
include 'config/koneksi.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$errorMessage = $_SESSION['edit_krs_error'] ?? '';
unset($_SESSION['edit_krs_error']);
$krs = null;

if ($id > 0) {
    $stmt = $pdo->prepare("SELECT * FROM krs WHERE id_krs = :id");
    $stmt->execute([':id' => $id]);
    $krs = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$krs) {
        $errorMessage = 'Data KRS tidak ditemukan.';
    }
} else {
    $errorMessage = 'ID KRS tidak valid.';
}

$mahasiswaList = $pdo->query("SELECT id_mahasiswa, nim, nama FROM mahasiswa ORDER BY nama ASC")->fetchAll(PDO::FETCH_ASSOC);
$matakuliahList = $pdo->query("SELECT id_matkul, kode_matkul, nama_matkul, sks FROM matakuliah ORDER BY nama_matkul ASC")->fetchAll(PDO::FETCH_ASSOC);
$allowedStatus = ['Belum Disetujui', 'Disetujui', 'Ditolak'];
?>

<h4 class="fw-bold"><span class="text-muted fw-light">KRS /</span> Edit KRS</h4>

<div class="card">
    <div class="card-body">
        <?php if ($errorMessage) : ?>
            <div class="alert alert-<?php echo $krs ? 'danger' : 'warning'; ?>" role="alert">
                <?php echo htmlspecialchars($errorMessage); ?>
            </div>
        <?php endif; ?>

        <?php if ($krs) : ?>
            <form method="POST" action="<?php echo page_url('krs/proses-update'); ?>">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars((string)$id); ?>">
                <div class="mb-3">
                    <label for="id_mahasiswa" class="form-label">Mahasiswa</label>
                    <select class="form-select" id="id_mahasiswa" name="id_mahasiswa" required>
                        <option value="" disabled>Pilih Mahasiswa</option>
                        <?php foreach ($mahasiswaList as $mahasiswa) : ?>
                            <option value="<?php echo $mahasiswa['id_mahasiswa']; ?>" <?php echo ($krs['id_mahasiswa'] == $mahasiswa['id_mahasiswa']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($mahasiswa['nim'] . ' - ' . $mahasiswa['nama']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="id_matkul" class="form-label">Mata Kuliah</label>
                    <select class="form-select" id="id_matkul" name="id_matkul" required>
                        <option value="" disabled>Pilih Mata Kuliah</option>
                        <?php foreach ($matakuliahList as $matkul) : ?>
                            <?php $matkulLabel = $matkul['kode_matkul'] . ' - ' . $matkul['nama_matkul'] . ' (' . (int)$matkul['sks'] . ' SKS)'; ?>
                            <option value="<?php echo $matkul['id_matkul']; ?>" <?php echo ($krs['id_matkul'] == $matkul['id_matkul']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($matkulLabel); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="semester" class="form-label">Semester</label>
                    <input type="number" class="form-control" id="semester" name="semester" min="1" max="14" value="<?php echo htmlspecialchars($krs['semester']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="tahun_ajaran" class="form-label">Tahun Ajaran</label>
                    <input type="text" class="form-control" id="tahun_ajaran" name="tahun_ajaran" value="<?php echo htmlspecialchars($krs['tahun_ajaran']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status" required>
                        <?php foreach ($allowedStatus as $opt) : ?>
                            <option value="<?php echo $opt; ?>" <?php echo ($krs['status'] === $opt) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($opt); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Dicatat Pada</label>
                    <input type="text" class="form-control" value="<?php echo htmlspecialchars(date('d/m/Y H:i', strtotime($krs['tanggal_pengisian']))); ?>" disabled>
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    <a href="<?php echo page_url('krs/krs'); ?>" class="btn btn-secondary">Kembali</a>
                </div>
            </form>
        <?php else : ?>
            <a href="<?php echo page_url('krs/krs'); ?>" class="btn btn-secondary">Kembali</a>
        <?php endif; ?>
    </div>
</div>
