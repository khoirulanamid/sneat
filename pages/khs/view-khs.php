<?php
include 'config/koneksi.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$data = null;

if ($id > 0) {
    $stmt = $pdo->prepare(
        "SELECT khs.*, mahasiswa.nim, mahasiswa.nama, mahasiswa.jurusan,
                matakuliah.kode_matkul, matakuliah.nama_matkul, matakuliah.sks
         FROM khs
         LEFT JOIN mahasiswa ON khs.id_mahasiswa = mahasiswa.id_mahasiswa
         LEFT JOIN matakuliah ON khs.id_matkul = matakuliah.id_matkul
         WHERE khs.id_khs = :id"
    );
    $stmt->execute([':id' => $id]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<h4 class="fw-bold"><span class="text-muted fw-light">KHS /</span> Detail Nilai</h4>

<div class="card">
    <div class="card-body">
        <?php if ($data) : ?>
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="mb-2"><strong>Mahasiswa:</strong> <?php echo htmlspecialchars($data['nama'] ?? '-'); ?></div>
                    <div class="mb-2"><strong>NIM:</strong> <?php echo htmlspecialchars($data['nim'] ?? '-'); ?></div>
                    <div class="mb-2"><strong>Jurusan:</strong> <?php echo htmlspecialchars($data['jurusan'] ?? '-'); ?></div>
                    <div class="mb-2"><strong>Semester:</strong> <?php echo htmlspecialchars($data['semester']); ?></div>
                    <div class="mb-2"><strong>Tahun Ajaran:</strong> <?php echo htmlspecialchars($data['tahun_ajaran']); ?></div>
                </div>
                <div class="col-md-6">
                    <div class="mb-2"><strong>Mata Kuliah:</strong> <?php echo htmlspecialchars($data['nama_matkul'] ?? '-'); ?></div>
                    <div class="mb-2"><strong>Kode MK:</strong> <?php echo htmlspecialchars($data['kode_matkul'] ?? '-'); ?></div>
                    <div class="mb-2"><strong>SKS:</strong> <?php echo htmlspecialchars($data['sks'] ?? '-'); ?></div>
                    <div class="mb-2"><strong>Nilai Angka:</strong> <?php echo htmlspecialchars(number_format((float)$data['nilai_angka'], 2)); ?></div>
                    <div class="mb-2"><strong>Nilai Huruf:</strong> <?php echo htmlspecialchars($data['nilai_huruf']); ?></div>
                </div>
            </div>
            <div class="mb-2"><strong>Status:</strong> <?php echo htmlspecialchars($data['status']); ?></div>
            <div class="mb-3"><strong>Catatan:</strong> <?php echo htmlspecialchars($data['catatan'] ?? '-'); ?></div>
            <div class="mb-3">
                <?php $updatedAt = !empty($data['updated_at']) ? date('d/m/Y H:i', strtotime($data['updated_at'])) : '-'; ?>
                <strong>Diperbarui pada:</strong> <?php echo htmlspecialchars($updatedAt); ?>
            </div>
        <?php else : ?>
            <div class="alert alert-warning" role="alert">
                Data KHS tidak ditemukan.
            </div>
        <?php endif; ?>

        <a href="<?php echo page_url('khs/khs'); ?>" class="btn btn-secondary">Kembali</a>
    </div>
</div>
