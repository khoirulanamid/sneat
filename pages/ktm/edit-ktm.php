<?php
include 'config/koneksi.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$errorMessage = '';
$ktm = null;
$allowedStatus = ['Aktif', 'Tidak Aktif', 'Hilang', 'Rusak'];

if ($id > 0) {
    $stmt = $pdo->prepare(
        "SELECT ktm.*, mahasiswa.nim, mahasiswa.nama
         FROM ktm
         LEFT JOIN mahasiswa ON ktm.id_mahasiswa = mahasiswa.id_mahasiswa
         WHERE id_ktm = :id"
    );
    $stmt->execute([':id' => $id]);
    $ktm = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$ktm) {
        $errorMessage = 'Data KTM tidak ditemukan.';
    }
} else {
    $errorMessage = 'ID KTM tidak valid.';
}

$mahasiswaList = $pdo->query("SELECT id_mahasiswa, nim, nama FROM mahasiswa ORDER BY nama ASC")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $ktm) {
    $idMahasiswa = $_POST['id_mahasiswa'] ?? '';
    $nomorKartu = trim($_POST['nomor_kartu'] ?? '');
    $tglTerbit = $_POST['tgl_terbit'] ?? '';
    $masaBerlaku = $_POST['masa_berlaku'] ?? '';
    $status = $_POST['status'] ?? 'Aktif';
    $fotoKartu = trim($_POST['foto_kartu'] ?? '');
    $keterangan = trim($_POST['keterangan'] ?? '');

    if (!in_array($status, $allowedStatus, true)) {
        $status = 'Aktif';
    }

    $ktm['id_mahasiswa'] = $idMahasiswa;
    $ktm['nomor_kartu'] = $nomorKartu;
    $ktm['tgl_terbit'] = $tglTerbit;
    $ktm['masa_berlaku'] = $masaBerlaku;
    $ktm['status'] = $status;
    $ktm['foto_kartu'] = $fotoKartu;
    $ktm['keterangan'] = $keterangan;

    try {
        if (!$idMahasiswa || !$nomorKartu || !$tglTerbit || !$masaBerlaku) {
            throw new RuntimeException('Mahasiswa, nomor kartu, tanggal terbit, dan masa berlaku wajib diisi.');
        }

        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $tglTerbit) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $masaBerlaku)) {
            throw new RuntimeException('Format tanggal tidak valid.');
        }

        if (strtotime($masaBerlaku) < strtotime($tglTerbit)) {
            throw new RuntimeException('Masa berlaku tidak boleh lebih awal dari tanggal terbit.');
        }

        $cekMahasiswa = $pdo->prepare("SELECT COUNT(*) FROM mahasiswa WHERE id_mahasiswa = :id");
        $cekMahasiswa->execute([':id' => $idMahasiswa]);
        if ($cekMahasiswa->fetchColumn() == 0) {
            throw new RuntimeException('Mahasiswa tidak ditemukan.');
        }

        $cekNomor = $pdo->prepare("SELECT COUNT(*) FROM ktm WHERE nomor_kartu = :nomor AND id_ktm <> :current");
        $cekNomor->execute([
            ':nomor' => $nomorKartu,
            ':current' => $id,
        ]);
        if ($cekNomor->fetchColumn() > 0) {
            throw new RuntimeException('Nomor KTM sudah terdaftar.');
        }

        $update = $pdo->prepare(
            "UPDATE ktm
             SET id_mahasiswa = :id_mahasiswa,
                 nomor_kartu = :nomor_kartu,
                 tgl_terbit = :tgl_terbit,
                 masa_berlaku = :masa_berlaku,
                 status = :status,
                 foto_kartu = :foto_kartu,
                 keterangan = :keterangan
             WHERE id_ktm = :id"
        );

        $update->execute([
            ':id_mahasiswa' => $idMahasiswa,
            ':nomor_kartu' => $nomorKartu,
            ':tgl_terbit' => $tglTerbit,
            ':masa_berlaku' => $masaBerlaku,
            ':status' => $status,
            ':foto_kartu' => $fotoKartu,
            ':keterangan' => $keterangan,
            ':id' => $id,
        ]);

        header('Location: ' . page_url('ktm/ktm'));
        exit;
    } catch (Throwable $e) {
        $errorMessage = $e->getMessage();
    }
}
?>

<h4 class="fw-bold"><span class="text-muted fw-light">KTM /</span> Edit KTM</h4>

<div class="card">
    <div class="card-body">
        <?php if ($errorMessage) : ?>
            <div class="alert alert-<?php echo $ktm ? 'danger' : 'warning'; ?>" role="alert">
                <?php echo htmlspecialchars($errorMessage); ?>
            </div>
        <?php endif; ?>

        <?php if ($ktm) : ?>
            <form method="POST">
                <div class="mb-3">
                    <label for="id_mahasiswa" class="form-label">Mahasiswa</label>
                    <select class="form-select" id="id_mahasiswa" name="id_mahasiswa" required>
                        <option value="" disabled>Pilih Mahasiswa</option>
                        <?php foreach ($mahasiswaList as $mahasiswa) : ?>
                            <option value="<?php echo $mahasiswa['id_mahasiswa']; ?>" <?php echo ($ktm['id_mahasiswa'] == $mahasiswa['id_mahasiswa']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($mahasiswa['nim'] . ' - ' . $mahasiswa['nama']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="nomor_kartu" class="form-label">Nomor KTM</label>
                    <input type="text" class="form-control" id="nomor_kartu" name="nomor_kartu" value="<?php echo htmlspecialchars($ktm['nomor_kartu']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="tgl_terbit" class="form-label">Tanggal Terbit</label>
                    <input type="date" class="form-control" id="tgl_terbit" name="tgl_terbit" value="<?php echo htmlspecialchars($ktm['tgl_terbit']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="masa_berlaku" class="form-label">Masa Berlaku</label>
                    <input type="date" class="form-control" id="masa_berlaku" name="masa_berlaku" value="<?php echo htmlspecialchars($ktm['masa_berlaku']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status" required>
                        <?php foreach ($allowedStatus as $opt) : ?>
                            <option value="<?php echo $opt; ?>" <?php echo ($ktm['status'] === $opt) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($opt); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="foto_kartu" class="form-label">Foto KTM (URL / path)</label>
                    <input type="text" class="form-control" id="foto_kartu" name="foto_kartu" value="<?php echo htmlspecialchars($ktm['foto_kartu']); ?>">
                </div>
                <div class="mb-3">
                    <label for="keterangan" class="form-label">Keterangan</label>
                    <textarea class="form-control" id="keterangan" name="keterangan" rows="3"><?php echo htmlspecialchars($ktm['keterangan']); ?></textarea>
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    <a href="<?php echo page_url('ktm/ktm'); ?>" class="btn btn-secondary">Kembali</a>
                </div>
            </form>
        <?php else : ?>
            <a href="<?php echo page_url('ktm/ktm'); ?>" class="btn btn-secondary">Kembali</a>
        <?php endif; ?>
    </div>
</div>
