<?php
include 'config/koneksi.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$dosen = null;

if ($id > 0) {
    $stmt = $pdo->prepare("SELECT * FROM dosen WHERE id_dosen = :id");
    $stmt->execute([':id' => $id]);
    $dosen = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<h4 class="fw-bold"><span class="text-muted fw-light">Dosen /</span> Detail Dosen</h4>

<div class="card">
    <div class="card-body">
        <?php if ($dosen) : ?>
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="mb-2"><strong>NIDN:</strong> <?php echo htmlspecialchars($dosen['nidn']); ?></div>
                    <div class="mb-2"><strong>Nama Dosen:</strong> <?php echo htmlspecialchars($dosen['nama_dosen']); ?></div>
                    <div class="mb-2"><strong>Jenis Kelamin:</strong> <?php echo htmlspecialchars($dosen['jenis_kelamin']); ?></div>
                    <div class="mb-2"><strong>Jabatan Akademik:</strong> <?php echo htmlspecialchars($dosen['jabatan_akademik']); ?></div>
                </div>
                <div class="col-md-6">
                    <div class="mb-2"><strong>Pendidikan Terakhir:</strong> <?php echo htmlspecialchars($dosen['pendidikan_terakhir']); ?></div>
                    <div class="mb-2"><strong>Keahlian:</strong> <?php echo htmlspecialchars($dosen['keahlian']); ?></div>
                    <div class="mb-2"><strong>Status:</strong> <?php echo htmlspecialchars($dosen['status_dosen']); ?></div>
                </div>
            </div>
        <?php else : ?>
            <div class="alert alert-warning mb-3" role="alert">
                Data dosen tidak ditemukan.
            </div>
        <?php endif; ?>

        <a href="<?php echo page_url('dosen/dosen'); ?>" class="btn btn-secondary">Kembali</a>
    </div>
</div>
