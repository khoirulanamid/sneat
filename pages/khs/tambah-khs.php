<?php
session_start();
include 'config/koneksi.php';

$mahasiswaList = $pdo->query("SELECT id_mahasiswa, nim, nama FROM mahasiswa ORDER BY nama ASC")->fetchAll(PDO::FETCH_ASSOC);
$matakuliahList = $pdo->query("SELECT id_matkul, kode_matkul, nama_matkul, sks FROM matakuliah ORDER BY nama_matkul ASC")->fetchAll(PDO::FETCH_ASSOC);

$errorMessage = $_SESSION['tambah_khs_error'] ?? '';
$old = $_SESSION['tambah_khs_old'] ?? [
    'id_mahasiswa' => '',
    'id_matkul' => '',
    'semester' => '',
    'tahun_ajaran' => '',
    'nilai_angka' => '',
    'status' => 'Lulus',
    'catatan' => '',
];

unset($_SESSION['tambah_khs_error'], $_SESSION['tambah_khs_old']);
?>

<h4 class="fw-bold"><span class="text-muted fw-light">KHS /</span> Tambah Data Nilai</h4>

<div class="card">
    <div class="card-body">
        <?php if ($errorMessage) : ?>
            <div class="alert alert-danger" role="alert">
                <?php echo htmlspecialchars($errorMessage); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?php echo page_url('khs/proses'); ?>">
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
                <label for="id_matkul" class="form-label">Mata Kuliah</label>
                <select class="form-select" id="id_matkul" name="id_matkul" required>
                    <option value="" disabled <?php echo empty($old['id_matkul']) ? 'selected' : ''; ?>>Pilih Mata Kuliah</option>
                    <?php foreach ($matakuliahList as $matkul) : ?>
                        <?php $label = $matkul['kode_matkul'] . ' - ' . $matkul['nama_matkul'] . ' (' . (int)$matkul['sks'] . ' SKS)'; ?>
                        <option value="<?php echo $matkul['id_matkul']; ?>" <?php echo ($old['id_matkul'] == $matkul['id_matkul']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($label); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="semester" class="form-label">Semester</label>
                        <input type="number" class="form-control" id="semester" name="semester" min="1" max="14" value="<?php echo htmlspecialchars($old['semester']); ?>" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="tahun_ajaran" class="form-label">Tahun Ajaran</label>
                        <input type="text" class="form-control" id="tahun_ajaran" name="tahun_ajaran" placeholder="2024/2025" value="<?php echo htmlspecialchars($old['tahun_ajaran']); ?>" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="nilai_angka" class="form-label">Nilai Angka (0 - 100)</label>
                        <input type="number" step="0.01" min="0" max="100" class="form-control" id="nilai_angka" name="nilai_angka" value="<?php echo htmlspecialchars($old['nilai_angka']); ?>" required>
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-select" id="status" name="status" required>
                    <?php
                    $statusOptions = ['Lulus', 'Tidak Lulus', 'Remedial'];
                    foreach ($statusOptions as $status) :
                    ?>
                        <option value="<?php echo $status; ?>" <?php echo ($old['status'] === $status) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($status); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="catatan" class="form-label">Catatan</label>
                <textarea class="form-control" id="catatan" name="catatan" rows="3"><?php echo htmlspecialchars($old['catatan']); ?></textarea>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="<?php echo page_url('khs/khs'); ?>" class="btn btn-secondary">Kembali</a>
            </div>
        </form>
    </div>
</div>
