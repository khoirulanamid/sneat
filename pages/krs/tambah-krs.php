<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once __DIR__ . '/../../config/koneksi.php';

$mahasiswaList = $pdo->query("SELECT id_mahasiswa, nim, nama FROM mahasiswa ORDER BY nama ASC")->fetchAll(PDO::FETCH_ASSOC);
$matakuliahList = $pdo->query("SELECT id_matkul, kode_matkul, nama_matkul, sks FROM matakuliah ORDER BY nama_matkul ASC")->fetchAll(PDO::FETCH_ASSOC);

// detect if this is an edit for a group (prefill form)
$errorMessage = $_SESSION['tambah_krs_error'] ?? '';
$old = $_SESSION['tambah_krs_old'] ?? null;
unset($_SESSION['tambah_krs_error'], $_SESSION['tambah_krs_old']);

$isEditGroup = false;
$original = ['id_mahasiswa' => null, 'semester' => null, 'tahun_ajaran' => null];

// If query params provided, load existing group to prefill
if (isset($_GET['id_mahasiswa'], $_GET['semester'], $_GET['tahun_ajaran']) && $old === null) {
    $origId = (int)$_GET['id_mahasiswa'];
    $origSemester = (int)$_GET['semester'];
    $origTahun = $_GET['tahun_ajaran'];

    $stmt = $pdo->prepare("SELECT * FROM krs WHERE id_mahasiswa = :id_mahasiswa AND semester = :semester AND tahun_ajaran = :tahun_ajaran");
    $stmt->execute([':id_mahasiswa' => $origId, ':semester' => $origSemester, ':tahun_ajaran' => $origTahun]);
    $entries = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (!empty($entries)) {
        $isEditGroup = true;
        $original = ['id_mahasiswa' => $origId, 'semester' => $origSemester, 'tahun_ajaran' => $origTahun];
        $old = [
            'id_mahasiswa' => $origId,
            'id_matkul' => array_map(function($e){return $e['id_matkul'];}, $entries),
            'semester' => $origSemester,
            'tahun_ajaran' => $origTahun,
            'status' => $entries[0]['status'] ?? 'Belum Disetujui',
        ];
    } else {
        // no entries found, fallback to defaults
        $old = [ 'id_mahasiswa' => '', 'id_matkul' => [], 'semester' => '', 'tahun_ajaran' => '', 'status' => 'Belum Disetujui' ];
    }
} elseif ($old === null) {
    $old = [ 'id_mahasiswa' => '', 'id_matkul' => [], 'semester' => '', 'tahun_ajaran' => '', 'status' => 'Belum Disetujui' ];
}
?>

<h4 class="fw-bold"><span class="text-muted fw-light">KRS /</span> Tambah KRS</h4>

<div class="card">
    <div class="card-body">
        <?php if ($errorMessage) : ?>
            <div class="alert alert-danger" role="alert">
                <?php echo htmlspecialchars($errorMessage); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?php echo $isEditGroup ? page_url('krs/proses-update-group') : page_url('krs/proses-tambah'); ?>">
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
                <label class="form-label">Mata Kuliah</label>
                <div class="row">
                    <?php foreach ($matakuliahList as $matkul) : ?>
                        <?php
                        $matkulLabel = $matkul['kode_matkul'] . ' - ' . $matkul['nama_matkul'] . ' (' . (int)$matkul['sks'] . ' SKS)';
                        $isChecked = !empty($old['id_matkul']) && in_array($matkul['id_matkul'], (array)$old['id_matkul']);
                        ?>
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="id_matkul[]" value="<?php echo $matkul['id_matkul']; ?>" id="matkul_<?php echo $matkul['id_matkul']; ?>" <?php echo $isChecked ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="matkul_<?php echo $matkul['id_matkul']; ?>">
                                    <?php echo htmlspecialchars($matkulLabel); ?>
                                </label>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="mb-3">
                <label for="semester" class="form-label">Semester</label>
                <input type="number" class="form-control" id="semester" name="semester" min="1" max="14" value="<?php echo htmlspecialchars($old['semester']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="tahun_ajaran" class="form-label">Tahun Ajaran</label>
                <input type="text" class="form-control" id="tahun_ajaran" name="tahun_ajaran" placeholder="2024/2025" value="<?php echo htmlspecialchars($old['tahun_ajaran']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-select" id="status" name="status" required>
                    <option value="Belum Disetujui" <?php echo ($old['status'] ?? '') === 'Belum Disetujui' ? 'selected' : ''; ?>>Belum Disetujui</option>
                    <option value="Disetujui" <?php echo ($old['status'] ?? '') === 'Disetujui' ? 'selected' : ''; ?>>Disetujui</option>
                    <option value="Ditolak" <?php echo ($old['status'] ?? '') === 'Ditolak' ? 'selected' : ''; ?>>Ditolak</option>
                </select>
            </div>
            <div class="d-flex gap-2">
                <?php if ($isEditGroup) : ?>
                    <input type="hidden" name="original_id_mahasiswa" value="<?php echo htmlspecialchars((string)$original['id_mahasiswa']); ?>">
                    <input type="hidden" name="original_semester" value="<?php echo htmlspecialchars((string)$original['semester']); ?>">
                    <input type="hidden" name="original_tahun_ajaran" value="<?php echo htmlspecialchars($original['tahun_ajaran']); ?>">
                <?php endif; ?>
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="<?php echo page_url('krs/krs'); ?>" class="btn btn-secondary">Kembali</a>
            </div>
        </form>
    </div>
</div>
