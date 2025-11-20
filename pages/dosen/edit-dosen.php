<?php
include 'config/koneksi.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$errorMessage = '';
$dosen = null;

if ($id > 0) {
    $stmt = $pdo->prepare("SELECT * FROM dosen WHERE id_dosen = :id");
    $stmt->execute([':id' => $id]);
    $dosen = $stmt->fetch(PDO::FETCH_ASSOC);
} else {
    $errorMessage = 'ID tidak valid.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id > 0) {
    $nidn = trim($_POST['nidn'] ?? '');
    $namaDosen = trim($_POST['nama_dosen'] ?? '');
    $jenisKelamin = $_POST['jenis_kelamin'] ?? '';
    $jabatanAkademik = trim($_POST['jabatan_akademik'] ?? '');
    $pendidikanTerakhir = trim($_POST['pendidikan_terakhir'] ?? '');
    $keahlian = trim($_POST['keahlian'] ?? '');
    $statusDosen = $_POST['status_dosen'] ?? '';

    $dosen = [
        'id_dosen' => $id,
        'nidn' => $nidn,
        'nama_dosen' => $namaDosen,
        'jenis_kelamin' => $jenisKelamin,
        'jabatan_akademik' => $jabatanAkademik,
        'pendidikan_terakhir' => $pendidikanTerakhir,
        'keahlian' => $keahlian,
        'status_dosen' => $statusDosen,
    ];

    if ($nidn && $namaDosen && $jenisKelamin && $jabatanAkademik && $pendidikanTerakhir && $keahlian && $statusDosen) {
        try {
            $update = $pdo->prepare(
                "UPDATE dosen
                 SET nidn = :nidn,
                     nama_dosen = :nama_dosen,
                     jenis_kelamin = :jenis_kelamin,
                     jabatan_akademik = :jabatan_akademik,
                     pendidikan_terakhir = :pendidikan_terakhir,
                     keahlian = :keahlian,
                     status_dosen = :status_dosen
                 WHERE id_dosen = :id"
            );

            $update->execute([
                ':nidn' => $nidn,
                ':nama_dosen' => $namaDosen,
                ':jenis_kelamin' => $jenisKelamin,
                ':jabatan_akademik' => $jabatanAkademik,
                ':pendidikan_terakhir' => $pendidikanTerakhir,
                ':keahlian' => $keahlian,
                ':status_dosen' => $statusDosen,
                ':id' => $id,
            ]);

            header("Location: " . page_url('dosen/dosen'));
            exit;
        } catch (PDOException $e) {
            $errorMessage = 'Gagal memperbarui data: ' . $e->getMessage();
        }
    } else {
        $errorMessage = 'Semua field wajib diisi.';
    }
}
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
            <form method="POST">
                <div class="mb-3">
                    <label for="nidn" class="form-label">NIDN</label>
                    <input type="text" class="form-control" id="nidn" name="nidn" value="<?php echo htmlspecialchars($dosen['nidn'] ?? ''); ?>" required>
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
                    <label for="keahlian" class="form-label">Keahlian</label>
                    <input type="text" class="form-control" id="keahlian" name="keahlian" value="<?php echo htmlspecialchars($dosen['keahlian'] ?? ''); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="status_dosen" class="form-label">Status</label>
                    <select class="form-select" id="status_dosen" name="status_dosen" required>
                        <option value="" disabled <?php echo empty($dosen['status_dosen']) ? 'selected' : ''; ?>>Pilih Status</option>
                        <option value="Tetap" <?php echo (isset($dosen['status_dosen']) && $dosen['status_dosen'] === 'Tetap') ? 'selected' : ''; ?>>Tetap</option>
                        <option value="Kontrak" <?php echo (isset($dosen['status_dosen']) && $dosen['status_dosen'] === 'Kontrak') ? 'selected' : ''; ?>>Kontrak</option>
                    </select>
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    <a href="<?php echo page_url('dosen/dosen'); ?>" class="btn btn-secondary">Kembali</a>
                </div>
            </form>
        <?php else : ?>
            <a href="<?php echo page_url('dosen/dosen'); ?>" class="btn btn-secondary">Kembali</a>
        <?php endif; ?>
    </div>
</div>
