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
    $tempatLahir = trim($_POST['tempat_lahir'] ?? '');
    $tanggalLahir = trim($_POST['tanggal_lahir'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $noHp = trim($_POST['no_hp'] ?? '');
    $alamat = trim($_POST['alamat'] ?? '');

    $mahasiswa = [
        'nim' => $nim,
        'nama' => $nama,
        'jenis_kelamin' => $jenisKelamin,
        'jurusan' => $jurusan,
        'tahun_masuk' => $tahunMasuk,
        'status' => $status,
        'tempat_lahir' => $tempatLahir,
        'tanggal_lahir' => $tanggalLahir,
        'email' => $email,
        'no_hp' => $noHp,
        'alamat' => $alamat,
    ];

    if ($nama && $jenisKelamin && $jurusan && $tahunMasuk && $status) {
        try {
            $update = $pdo->prepare(
                "UPDATE mahasiswa
                 SET nama = :nama,
                     jenis_kelamin = :jenis_kelamin,
                     jurusan = :jurusan,
                     tahun_masuk = :tahun_masuk,
                     status = :status,
                     tempat_lahir = :tempat_lahir,
                     tanggal_lahir = :tanggal_lahir,
                     email = :email,
                     no_hp = :no_hp,
                     alamat = :alamat
                 WHERE nim = :nim"
            );

            $update->execute([
                ':nama' => $nama,
                ':jenis_kelamin' => $jenisKelamin,
                ':jurusan' => $jurusan,
                ':tahun_masuk' => $tahunMasuk,
                ':status' => $status,
                ':tempat_lahir' => $tempatLahir ?: null,
                ':tanggal_lahir' => $tanggalLahir ?: null,
                ':email' => $email ?: null,
                ':no_hp' => $noHp ?: null,
                ':alamat' => $alamat ?: null,
                ':nim' => $nim,
            ]);

            $redirectUrl = page_url('mahasiswa/mahasiswa');
            if (!headers_sent()) {
                header("Location: " . $redirectUrl);
                exit;
            } else {
                echo '<script>window.location.href = ' . json_encode($redirectUrl) . ';</script>';
                exit;
            }
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
                <div class="row g-3">
                    <div class="col-lg-6">
                        <div class="p-3 border rounded h-100">
                            <h6 class="fw-semibold mb-3">Data Personal</h6>
                            <div class="mb-3">
                                <label for="nim" class="form-label">NIM</label>
                                <input type="text" class="form-control" id="nim" name="nim" value="<?php echo htmlspecialchars($mahasiswa['nim'] ?? ''); ?>" disabled>
                            </div>
                            <div class="mb-3">
                                <label for="nama" class="form-label">Nama</label>
                                <input type="text" class="form-control" id="nama" name="nama" value="<?php echo htmlspecialchars($mahasiswa['nama'] ?? ''); ?>" required>
                            </div>
                            <div class="mb-0">
                                <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                                <select class="form-select" id="jenis_kelamin" name="jenis_kelamin" required>
                                    <option value="" disabled <?php echo empty($mahasiswa['jenis_kelamin']) ? 'selected' : ''; ?>>Pilih Jenis Kelamin</option>
                                    <option value="Laki-laki" <?php echo (isset($mahasiswa['jenis_kelamin']) && $mahasiswa['jenis_kelamin'] === 'Laki-laki') ? 'selected' : ''; ?>>Laki-laki</option>
                                    <option value="Perempuan" <?php echo (isset($mahasiswa['jenis_kelamin']) && $mahasiswa['jenis_kelamin'] === 'Perempuan') ? 'selected' : ''; ?>>Perempuan</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="tempat_lahir" class="form-label">Tempat Lahir</label>
                                <input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir" value="<?php echo htmlspecialchars($mahasiswa['tempat_lahir'] ?? ''); ?>">
                            </div>
                            <div class="mb-3">
                                <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                                <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" value="<?php echo htmlspecialchars($mahasiswa['tanggal_lahir'] ?? ''); ?>">
                            </div>
                            <div class="mb-0">
                                <label for="alamat" class="form-label">Alamat</label>
                                <textarea class="form-control" id="alamat" name="alamat" rows="3"><?php echo htmlspecialchars($mahasiswa['alamat'] ?? ''); ?></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="p-3 border rounded h-100">
                            <h6 class="fw-semibold mb-3">Data Akademik</h6>
                            <div class="mb-3">
                                <label for="jurusan" class="form-label">Jurusan</label>
                                <input type="text" class="form-control" id="jurusan" name="jurusan" value="<?php echo htmlspecialchars($mahasiswa['jurusan'] ?? ''); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="tahun_masuk" class="form-label">Tahun Masuk</label>
                                <input type="number" class="form-control" id="tahun_masuk" name="tahun_masuk" value="<?php echo htmlspecialchars($mahasiswa['tahun_masuk'] ?? ''); ?>" min="1900" max="2100" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($mahasiswa['email'] ?? ''); ?>">
                            </div>
                            <div class="mb-3">
                                <label for="no_hp" class="form-label">No HP</label>
                                <input type="text" class="form-control" id="no_hp" name="no_hp" value="<?php echo htmlspecialchars($mahasiswa['no_hp'] ?? ''); ?>">
                            </div>
                            <div class="mb-0">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="" disabled <?php echo empty($mahasiswa['status']) ? 'selected' : ''; ?>>Pilih Status</option>
                                    <option value="Aktif" <?php echo (isset($mahasiswa['status']) && $mahasiswa['status'] === 'Aktif') ? 'selected' : ''; ?>>Aktif</option>
                                    <option value="Cuti" <?php echo (isset($mahasiswa['status']) && $mahasiswa['status'] === 'Cuti') ? 'selected' : ''; ?>>Cuti</option>
                                    <option value="Lulus" <?php echo (isset($mahasiswa['status']) && $mahasiswa['status'] === 'Lulus') ? 'selected' : ''; ?>>Lulus</option>
                                    <option value="DO" <?php echo (isset($mahasiswa['status']) && $mahasiswa['status'] === 'DO') ? 'selected' : ''; ?>>DO</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex gap-2 mt-3">
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    <a href="<?php echo page_url('mahasiswa/mahasiswa'); ?>" class="btn btn-secondary">Kembali</a>
                </div>
            </form>
        <?php else : ?>
            <a href="<?php echo page_url('mahasiswa/mahasiswa'); ?>" class="btn btn-secondary">Kembali</a>
        <?php endif; ?>
    </div>
</div>
