<?php
include 'config/koneksi.php';

$kodeMatkul = isset($_GET['kode']) ? trim($_GET['kode']) : '';
$matakuliah = null;

if ($kodeMatkul !== '') {
    $stmt = $pdo->prepare(
        "SELECT m.*, d.nama_dosen
         FROM matakuliah m
         LEFT JOIN dosen d ON m.id_dosen = d.id_dosen
         WHERE m.kode_matkul = :kode_matkul
         LIMIT 1"
    );
    $stmt->execute([':kode_matkul' => $kodeMatkul]);
    $matakuliah = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<h4 class="fw-bold"><span class="text-muted fw-light">Mata Kuliah /</span> Detail Mata Kuliah</h4>

<div class="card">
    <div class="card-body">
        <?php if ($matakuliah) : ?>
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="mb-2"><strong>Kode:</strong> <?php echo htmlspecialchars($matakuliah['kode_matkul']); ?></div>
                    <div class="mb-2"><strong>Nama:</strong> <?php echo htmlspecialchars($matakuliah['nama_matkul']); ?></div>
                    <div class="mb-2"><strong>SKS:</strong> <?php echo htmlspecialchars($matakuliah['sks']); ?></div>
                    <div class="mb-2"><strong>Semester:</strong> <?php echo htmlspecialchars($matakuliah['semester']); ?></div>
                </div>
                <div class="col-md-6">
                    <div class="mb-2"><strong>Dosen Pengampu:</strong> <?php echo htmlspecialchars($matakuliah['nama_dosen'] ?? 'Belum Ditentukan'); ?></div>
                    <div class="mb-2"><strong>Jenis:</strong> <?php echo htmlspecialchars($matakuliah['jenis_matkul']); ?></div>
                    <div class="mb-2"><strong>Status:</strong> <?php echo htmlspecialchars($matakuliah['status']); ?></div>
                </div>
            </div>
        <?php else : ?>
            <div class="alert alert-warning mb-3" role="alert">
                Data mata kuliah tidak ditemukan.
            </div>
        <?php endif; ?>

        <a href="<?php echo page_url('matakuliah/matakuliah'); ?>" class="btn btn-secondary">Kembali</a>
    </div>
</div>
