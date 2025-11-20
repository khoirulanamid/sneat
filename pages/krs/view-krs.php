<?php
include 'config/koneksi.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$data = null;

if ($id > 0) {
    $stmt = $pdo->prepare(
        "SELECT krs.*, mahasiswa.nim, mahasiswa.nama, mahasiswa.jurusan, matakuliah.kode_matkul, matakuliah.nama_matkul, matakuliah.sks
         FROM krs
         LEFT JOIN mahasiswa ON krs.id_mahasiswa = mahasiswa.id_mahasiswa
         LEFT JOIN matakuliah ON krs.id_matkul = matakuliah.id_matkul
         WHERE krs.id_krs = :id"
    );
    $stmt->execute([':id' => $id]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<h4 class="fw-bold"><span class="text-muted fw-light">KRS /</span> Detail KRS</h4>

<div class="card">
    <div class="card-body">
        <?php if ($data) : ?>
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="mb-2"><strong>Mahasiswa:</strong> <?php echo htmlspecialchars($data['nama'] ?? '-'); ?></div>
                    <div class="mb-2"><strong>NIM:</strong> <?php echo htmlspecialchars($data['nim'] ?? '-'); ?></div>
                    <div class="mb-2"><strong>Jurusan:</strong> <?php echo htmlspecialchars($data['jurusan'] ?? '-'); ?></div>
                    <div class="mb-2"><strong>Semester:</strong> <?php echo htmlspecialchars($data['semester']); ?></div>
                </div>
                <div class="col-md-6">
                    <div class="mb-2"><strong>Mata Kuliah:</strong> <?php echo htmlspecialchars($data['nama_matkul'] ?? '-'); ?></div>
                    <div class="mb-2"><strong>Kode MK:</strong> <?php echo htmlspecialchars($data['kode_matkul'] ?? '-'); ?></div>
                    <div class="mb-2"><strong>SKS:</strong> <?php echo htmlspecialchars($data['sks'] ?? '-'); ?></div>
                    <div class="mb-2"><strong>Tahun Ajaran:</strong> <?php echo htmlspecialchars($data['tahun_ajaran']); ?></div>
                </div>
            </div>
            <?php $recordedAt = !empty($data['tanggal_pengisian']) ? date('d/m/Y H:i', strtotime($data['tanggal_pengisian'])) : '-'; ?>
            <div class="mb-2"><strong>Status:</strong> <?php echo htmlspecialchars($data['status']); ?></div>
            <div class="mb-4"><strong>Tanggal Pengisian:</strong> <?php echo htmlspecialchars($recordedAt); ?></div>
        <?php else : ?>
            <div class="alert alert-warning" role="alert">
                Data KRS tidak ditemukan.
            </div>
        <?php endif; ?>

        <a href="<?php echo page_url('krs/krs'); ?>" class="btn btn-secondary">Kembali</a>
    </div>
</div>
