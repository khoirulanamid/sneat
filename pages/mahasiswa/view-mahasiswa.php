<?php
include 'config/koneksi.php';

$nim = isset($_GET['nim']) ? trim($_GET['nim']) : '';
$mahasiswa = null;

if ($nim !== '') {
    $stmt = $pdo->prepare("SELECT * FROM mahasiswa WHERE nim = :nim");
    $stmt->execute([':nim' => $nim]);
    $mahasiswa = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<h4 class="fw-bold"><span class="text-muted fw-light">Mahasiswa /</span> Detail Mahasiswa</h4>

<div class="card">
    <div class="card-body">
        <?php if ($mahasiswa) : ?>
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="mb-2"><strong>NIM:</strong> <?php echo htmlspecialchars($mahasiswa['nim']); ?></div>
                    <div class="mb-2"><strong>Nama:</strong> <?php echo htmlspecialchars($mahasiswa['nama']); ?></div>
                    <div class="mb-2"><strong>Jenis Kelamin:</strong> <?php echo htmlspecialchars($mahasiswa['jenis_kelamin']); ?></div>
                </div>
                <div class="col-md-6">
                    <div class="mb-2"><strong>Jurusan:</strong> <?php echo htmlspecialchars($mahasiswa['jurusan']); ?></div>
                    <div class="mb-2"><strong>Tahun Masuk:</strong> <?php echo htmlspecialchars($mahasiswa['tahun_masuk']); ?></div>
                    <div class="mb-2"><strong>Status:</strong> <?php echo htmlspecialchars($mahasiswa['status']); ?></div>
                </div>
            </div>
        <?php else : ?>
            <div class="alert alert-warning mb-3" role="alert">
                Data mahasiswa tidak ditemukan.
            </div>
        <?php endif; ?>

        <a href="<?php echo page_url('mahasiswa/mahasiswa'); ?>" class="btn btn-secondary">Kembali</a>
    </div>
</div>
