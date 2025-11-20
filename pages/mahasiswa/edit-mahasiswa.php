<?php
include 'config/koneksi.php';

$nim = isset($_GET['nim']) ? trim($_GET['nim']) : '';
$errorMessage = '';
$mahasiswa = null;

if ($nim !== '') {
    $stmt = $pdo->prepare("SELECT * FROM mahasiswa WHERE nim = :nim");
    $stmt->execute([':nim' => $nim]);
    $mahasiswa = $stmt->fetch(PDO::FETCH_ASSOC);
} else {
    $errorMessage = 'NIM tidak valid.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $nim !== '') {
    $nama = trim($_POST['nama'] ?? '');
    $jenisKelamin = $_POST['jenis_kelamin'] ?? '';
    $jurusan = trim($_POST['jurusan'] ?? '');
    $tahunMasuk = trim($_POST['tahun_masuk'] ?? '');
    $status = $_POST['status'] ?? '';

    $mahasiswa = [
        'nim' => $nim,
        'nama' => $nama,
        'jenis_kelamin' => $jenisKelamin,
        'jurusan' => $jurusan,
        'tahun_masuk' => $tahunMasuk,
        'status' => $status,
    ];

    if ($nama && $jenisKelamin && $jurusan && $tahunMasuk && $status) {
        try {
            $update = $pdo->prepare(
                "UPDATE mahasiswa
                 SET nama = :nama,
                     jenis_kelamin = :jenis_kelamin,
                     jurusan = :jurusan,
                     tahun_masuk = :tahun_masuk,
                     status = :status
                 WHERE nim = :nim"
            );

            $update->execute([
                ':nama' => $nama,
                ':jenis_kelamin' => $jenisKelamin,
                ':jurusan' => $jurusan,
                ':tahun_masuk' => $tahunMasuk,
                ':status' => $status,
                ':nim' => $nim,
            ]);

            header("Location: " . page_url('mahasiswa/mahasiswa'));
            exit;
        } catch (PDOException $e) {
            $errorMessage = 'Gagal memperbarui data: ' . $e->getMessage();
        }
    } else {
        $errorMessage = 'Semua field wajib diisi.';
    }
}
?>

<h4 class="fw-bold"><span class="text-muted fw-light">Mahasiswa /</span> Edit Mahasiswa</h4>

<div class="card">
    <div class="card-body">
        <?php if ($errorMessage) : ?>
            <div class="alert alert-danger" role="alert">
                <?php echo htmlspecialchars($errorMessage); ?>
            </div>
        <?php endif; ?>

        <?php if ($mahasiswa) : ?>
            <form method="POST">
                <div class="mb-3">
                    <label for="nim" class="form-label">NIM</label>
                    <input type="text" class="form-control" id="nim" name="nim" value="<?php echo htmlspecialchars($mahasiswa['nim'] ?? ''); ?>" disabled>
                </div>
                <div class="mb-3">
                    <label for="nama" class="form-label">Nama</label>
                    <input type="text" class="form-control" id="nama" name="nama" value="<?php echo htmlspecialchars($mahasiswa['nama'] ?? ''); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                    <select class="form-select" id="jenis_kelamin" name="jenis_kelamin" required>
                        <option value="" disabled <?php echo empty($mahasiswa['jenis_kelamin']) ? 'selected' : ''; ?>>Pilih Jenis Kelamin</option>
                        <option value="Laki-laki" <?php echo (isset($mahasiswa['jenis_kelamin']) && $mahasiswa['jenis_kelamin'] === 'Laki-laki') ? 'selected' : ''; ?>>Laki-laki</option>
                        <option value="Perempuan" <?php echo (isset($mahasiswa['jenis_kelamin']) && $mahasiswa['jenis_kelamin'] === 'Perempuan') ? 'selected' : ''; ?>>Perempuan</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="jurusan" class="form-label">Jurusan</label>
                    <input type="text" class="form-control" id="jurusan" name="jurusan" value="<?php echo htmlspecialchars($mahasiswa['jurusan'] ?? ''); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="tahun_masuk" class="form-label">Tahun Masuk</label>
                    <input type="number" class="form-control" id="tahun_masuk" name="tahun_masuk" value="<?php echo htmlspecialchars($mahasiswa['tahun_masuk'] ?? ''); ?>" min="1900" max="2100" required>
                </div>
                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status" required>
                        <option value="" disabled <?php echo empty($mahasiswa['status']) ? 'selected' : ''; ?>>Pilih Status</option>
                        <option value="Aktif" <?php echo (isset($mahasiswa['status']) && $mahasiswa['status'] === 'Aktif') ? 'selected' : ''; ?>>Aktif</option>
                        <option value="Cuti" <?php echo (isset($mahasiswa['status']) && $mahasiswa['status'] === 'Cuti') ? 'selected' : ''; ?>>Cuti</option>
                        <option value="Lulus" <?php echo (isset($mahasiswa['status']) && $mahasiswa['status'] === 'Lulus') ? 'selected' : ''; ?>>Lulus</option>
                        <option value="DO" <?php echo (isset($mahasiswa['status']) && $mahasiswa['status'] === 'DO') ? 'selected' : ''; ?>>DO</option>
                    </select>
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    <a href="<?php echo page_url('mahasiswa/mahasiswa'); ?>" class="btn btn-secondary">Kembali</a>
                </div>
            </form>
        <?php else : ?>
            <a href="<?php echo page_url('mahasiswa/mahasiswa'); ?>" class="btn btn-secondary">Kembali</a>
        <?php endif; ?>
    </div>
</div>
