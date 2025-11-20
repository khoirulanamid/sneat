<?php
include 'config/koneksi.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$data = null;

if ($id > 0) {
    $stmt = $pdo->prepare(
        "SELECT ktm.*, mahasiswa.nim, mahasiswa.nama, mahasiswa.jurusan
         FROM ktm
         LEFT JOIN mahasiswa ON ktm.id_mahasiswa = mahasiswa.id_mahasiswa
         WHERE id_ktm = :id"
    );
    $stmt->execute([':id' => $id]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<h4 class="fw-bold"><span class="text-muted fw-light">KTM /</span> Detail KTM</h4>

<div class="card">
    <div class="card-body">
        <?php if ($data) : ?>
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="mb-2"><strong>Nomor KTM:</strong> <?php echo htmlspecialchars($data['nomor_kartu']); ?></div>
                    <div class="mb-2"><strong>Mahasiswa:</strong> <?php echo htmlspecialchars($data['nama'] ?? '-'); ?></div>
                    <div class="mb-2"><strong>NIM:</strong> <?php echo htmlspecialchars($data['nim'] ?? '-'); ?></div>
                    <div class="mb-2"><strong>Jurusan:</strong> <?php echo htmlspecialchars($data['jurusan'] ?? '-'); ?></div>
                </div>
                <div class="col-md-6">
                    <?php
                    $terbit = !empty($data['tgl_terbit']) ? date('d/m/Y', strtotime($data['tgl_terbit'])) : '-';
                    $berlaku = !empty($data['masa_berlaku']) ? date('d/m/Y', strtotime($data['masa_berlaku'])) : '-';
                    ?>
                    <div class="mb-2"><strong>Tanggal Terbit:</strong> <?php echo htmlspecialchars($terbit); ?></div>
                    <div class="mb-2"><strong>Masa Berlaku:</strong> <?php echo htmlspecialchars($berlaku); ?></div>
                    <div class="mb-2"><strong>Status:</strong> <?php echo htmlspecialchars($data['status']); ?></div>
                </div>
            </div>
            <div class="mb-2"><strong>Keterangan:</strong> <?php echo htmlspecialchars($data['keterangan'] ?? '-'); ?></div>
            <?php if (!empty($data['foto_kartu'])) : ?>
                <div class="mb-4">
                    <strong>Foto KTM:</strong><br>
                    <img src="<?php echo htmlspecialchars($data['foto_kartu']); ?>" alt="Foto KTM" class="img-fluid rounded border mt-2" style="max-width: 320px;">
                </div>
            <?php endif; ?>
        <?php else : ?>
            <div class="alert alert-warning" role="alert">
                Data KTM tidak ditemukan.
            </div>
        <?php endif; ?>

        <a href="<?php echo page_url('ktm/ktm'); ?>" class="btn btn-secondary">Kembali</a>
    </div>
</div>
