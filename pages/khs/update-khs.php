<?php
include_once __DIR__ . '/../../config/koneksi.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$errorMessage = $_SESSION['edit_khs_error'] ?? '';
unset($_SESSION['edit_khs_error']);
$khs = null;
$allowedStatus = ['Lulus', 'Tidak Lulus', 'Remedial'];

if ($id > 0) {
    $stmt = $pdo->prepare("SELECT * FROM khs WHERE id_khs = :id");
    $stmt->execute([':id' => $id]);
    $khs = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$khs) {
        $errorMessage = 'Data KHS tidak ditemukan.';
    }
} else {
    $errorMessage = 'ID KHS tidak valid.';
}

$mahasiswaList = $pdo->query("SELECT id_mahasiswa, nim, nama FROM mahasiswa ORDER BY nama ASC")->fetchAll(PDO::FETCH_ASSOC);
$matakuliahList = $pdo->query("SELECT id_matkul, kode_matkul, nama_matkul, sks FROM matakuliah ORDER BY nama_matkul ASC")->fetchAll(PDO::FETCH_ASSOC);

function convertGrade(float $nilai): string
{
    if ($nilai >= 85) {
        return 'A';
    }
    if ($nilai >= 70) {
        return 'B';
    }
    if ($nilai >= 55) {
        return 'C';
    }
    if ($nilai >= 40) {
        return 'D';
    }
    return 'E';
}
?>

<h4 class="fw-bold"><span class="text-muted fw-light">KHS /</span> Edit Data Nilai</h4>

<div class="card">
    <div class="card-body">
        <?php if ($errorMessage) : ?>
            <div class="alert alert-<?php echo $khs ? 'danger' : 'warning'; ?>" role="alert">
                <?php echo htmlspecialchars($errorMessage); ?>
            </div>
        <?php endif; ?>

        <?php if ($khs) : ?>
            <form method="POST" action="<?php echo page_url('khs/proses-update'); ?>">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars((string)$id); ?>">
                <div class="mb-3">
                    <label for="id_mahasiswa" class="form-label">Mahasiswa</label>
                    <select class="form-select" id="id_mahasiswa" name="id_mahasiswa" required>
                        <option value="" disabled>Pilih Mahasiswa</option>
                        <?php foreach ($mahasiswaList as $mahasiswa) : ?>
                            <option value="<?php echo $mahasiswa['id_mahasiswa']; ?>" <?php echo ($khs['id_mahasiswa'] == $mahasiswa['id_mahasiswa']) ? 'selected' : ''; ?>>
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
                            <?php $label = $matkul['kode_matkul'] . ' - ' . $matkul['nama_matkul'] . ' (' . (int)$matkul['sks'] . ' SKS)'; ?>
                            <option value="<?php echo $matkul['id_matkul']; ?>" <?php echo ($khs['id_matkul'] == $matkul['id_matkul']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($label); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="semester" class="form-label">Semester</label>
                            <input type="number" class="form-control" id="semester" name="semester" min="1" max="14" value="<?php echo htmlspecialchars($khs['semester']); ?>" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="tahun_ajaran" class="form-label">Tahun Ajaran</label>
                            <input type="text" class="form-control" id="tahun_ajaran" name="tahun_ajaran" value="<?php echo htmlspecialchars($khs['tahun_ajaran']); ?>" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="nilai_angka" class="form-label">Nilai Angka (0 - 100)</label>
                            <input type="number" step="0.01" min="0" max="100" class="form-control" id="nilai_angka" name="nilai_angka" value="<?php echo htmlspecialchars($khs['nilai_angka']); ?>" required>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status" required>
                        <?php foreach ($allowedStatus as $status) : ?>
                            <option value="<?php echo $status; ?>" <?php echo ($khs['status'] === $status) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($status); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="catatan" class="form-label">Catatan</label>
                    <textarea class="form-control" id="catatan" name="catatan" rows="3"><?php echo htmlspecialchars($khs['catatan']); ?></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Nilai Huruf</label>
                    <input type="text" class="form-control" value="<?php echo htmlspecialchars($khs['nilai_huruf']); ?>" disabled>
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    <a href="<?php echo page_url('khs/khs'); ?>" class="btn btn-secondary">Kembali</a>
                </div>
            </form>
        <?php else : ?>
            <a href="<?php echo page_url('khs/khs'); ?>" class="btn btn-secondary">Kembali</a>
        <?php endif; ?>
    </div>
</div>
