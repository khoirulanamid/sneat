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
    $nip = trim($_POST['nip'] ?? '');
    $namaDosen = trim($_POST['nama_dosen'] ?? '');
    $jenisKelamin = $_POST['jenis_kelamin'] ?? '';
    $tempatLahir = trim($_POST['tempat_lahir'] ?? '');
    $tanggalLahir = trim($_POST['tanggal_lahir'] ?? '');
    $keahlian = trim($_POST['keahlian'] ?? '');
    $jabatanAkademik = trim($_POST['jabatan_akademik'] ?? '');
    $pendidikanTerakhir = trim($_POST['pendidikan_terakhir'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $noHp = trim($_POST['no_hp'] ?? '');
    $alamat = trim($_POST['alamat'] ?? '');
    $statusDosen = $_POST['status_dosen'] ?? '';

    $dosen = [
        'id_dosen' => $id,
        'nidn' => $nidn,
        'nip' => $nip,
        'nama_dosen' => $namaDosen,
        'jenis_kelamin' => $jenisKelamin,
        'tempat_lahir' => $tempatLahir,
        'tanggal_lahir' => $tanggalLahir,
        'keahlian' => $keahlian,
        'jabatan_akademik' => $jabatanAkademik,
        'pendidikan_terakhir' => $pendidikanTerakhir,
        'email' => $email,
        'no_hp' => $noHp,
        'alamat' => $alamat,
        'status_dosen' => $statusDosen,
    ];

    $tanggalLahirDb = $tanggalLahir !== '' ? $tanggalLahir : null;

    if ($nidn && $namaDosen && $jenisKelamin && $pendidikanTerakhir && $keahlian && $statusDosen) {
        try {
            $update = $pdo->prepare(
                "UPDATE dosen
                 SET nidn = :nidn,
                     nip = :nip,
                     nama_dosen = :nama_dosen,
                     jenis_kelamin = :jenis_kelamin,
                     tempat_lahir = :tempat_lahir,
                     tanggal_lahir = :tanggal_lahir,
                     keahlian = :keahlian,
                     jabatan_akademik = :jabatan_akademik,
                     pendidikan_terakhir = :pendidikan_terakhir,
                     email = :email,
                     no_hp = :no_hp,
                     alamat = :alamat,
                     status_dosen = :status_dosen
                 WHERE id_dosen = :id"
            );

            $update->execute([
                ':nidn' => $nidn,
                ':nip' => $nip ?: null,
                ':nama_dosen' => $namaDosen,
                ':jenis_kelamin' => $jenisKelamin,
                ':tempat_lahir' => $tempatLahir ?: null,
                ':tanggal_lahir' => $tanggalLahirDb,
                ':keahlian' => $keahlian,
                ':jabatan_akademik' => $jabatanAkademik,
                ':pendidikan_terakhir' => $pendidikanTerakhir,
                ':email' => $email ?: null,
                ':no_hp' => $noHp ?: null,
                ':alamat' => $alamat ?: null,
                ':status_dosen' => $statusDosen,
                ':id' => $id,
            ]);

            $redirectUrl = page_url('dosen/dosen');
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
                <div class="row g-3">
                    <div class="col-lg-6">
                        <div class="p-3 border rounded h-100">
                            <h6 class="fw-semibold mb-3">Data Personal</h6>
                            <div class="mb-3">
                                <label for="nidn" class="form-label">NIDN</label>
                                <input type="text" class="form-control" id="nidn" name="nidn" value="<?php echo htmlspecialchars($dosen['nidn'] ?? ''); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="nip" class="form-label">NIP</label>
                                <input type="text" class="form-control" id="nip" name="nip" value="<?php echo htmlspecialchars($dosen['nip'] ?? ''); ?>">
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
                                <label for="tempat_lahir" class="form-label">Tempat Lahir</label>
                                <input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir" value="<?php echo htmlspecialchars($dosen['tempat_lahir'] ?? ''); ?>">
                            </div>
                            <div class="mb-3">
                                <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                                <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" value="<?php echo htmlspecialchars($dosen['tanggal_lahir'] ?? ''); ?>">
                            </div>
                            <div class="mb-0">
                                <label for="alamat" class="form-label">Alamat</label>
                                <textarea class="form-control" id="alamat" name="alamat" rows="3"><?php echo htmlspecialchars($dosen['alamat'] ?? ''); ?></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="p-3 border rounded h-100">
                            <h6 class="fw-semibold mb-3">Data Akademik & Kontak</h6>
                            <div class="mb-3">
                                <label for="keahlian" class="form-label">Keahlian</label>
                                <input type="text" class="form-control" id="keahlian" name="keahlian" value="<?php echo htmlspecialchars($dosen['keahlian'] ?? ''); ?>" required>
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
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($dosen['email'] ?? ''); ?>">
                            </div>
                            <div class="mb-3">
                                <label for="no_hp" class="form-label">No HP</label>
                                <input type="text" class="form-control" id="no_hp" name="no_hp" value="<?php echo htmlspecialchars($dosen['no_hp'] ?? ''); ?>">
                            </div>
                            <div class="mb-0">
                                <label for="status_dosen" class="form-label">Status</label>
                                <select class="form-select" id="status_dosen" name="status_dosen" required>
                                    <option value="" disabled <?php echo empty($dosen['status_dosen']) ? 'selected' : ''; ?>>Pilih Status</option>
                                    <option value="Tetap" <?php echo (isset($dosen['status_dosen']) && $dosen['status_dosen'] === 'Tetap') ? 'selected' : ''; ?>>Tetap</option>
                                    <option value="Kontrak" <?php echo (isset($dosen['status_dosen']) && $dosen['status_dosen'] === 'Kontrak') ? 'selected' : ''; ?>>Kontrak</option>
                                    <option value="Luar" <?php echo (isset($dosen['status_dosen']) && $dosen['status_dosen'] === 'Luar') ? 'selected' : ''; ?>>Luar Biasa</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex gap-2 mt-3">
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    <a href="<?php echo page_url('dosen/dosen'); ?>" class="btn btn-secondary">Kembali</a>
                </div>
            </form>
        <?php else : ?>
            <a href="<?php echo page_url('dosen/dosen'); ?>" class="btn btn-secondary">Kembali</a>
        <?php endif; ?>
    </div>
</div>
