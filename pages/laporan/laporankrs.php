<?php
include 'config/koneksi.php';

$mahasiswaList = $pdo->query("SELECT id_mahasiswa, nim, nama FROM mahasiswa ORDER BY nama ASC")->fetchAll(PDO::FETCH_ASSOC);

$idMahasiswa = $_GET['id_mahasiswa'] ?? '';
$semester = $_GET['semester'] ?? '';
$tahunAjaran = $_GET['tahun_ajaran'] ?? '';

$filters = [];
$params = [];

if ($idMahasiswa) {
    $filters[] = 'krs.id_mahasiswa = :id_mahasiswa';
    $params[':id_mahasiswa'] = $idMahasiswa;
}

if ($semester) {
    $filters[] = 'krs.semester = :semester';
    $params[':semester'] = (int)$semester;
}

if ($tahunAjaran) {
    $filters[] = 'krs.tahun_ajaran = :tahun_ajaran';
    $params[':tahun_ajaran'] = $tahunAjaran;
}

$whereClause = $filters ? 'WHERE ' . implode(' AND ', $filters) : '';

$data = [];
$mahasiswaInfo = null;

if ($idMahasiswa) {
    $stmt = $pdo->prepare(
        "SELECT krs.*, mahasiswa.nim, mahasiswa.nama, mahasiswa.jurusan,
                matakuliah.nama_matkul, matakuliah.kode_matkul, matakuliah.sks
         FROM krs
         LEFT JOIN mahasiswa ON krs.id_mahasiswa = mahasiswa.id_mahasiswa
         LEFT JOIN matakuliah ON krs.id_matkul = matakuliah.id_matkul
         $whereClause
         ORDER BY krs.tanggal_pengisian DESC"
    );
    $stmt->execute($params);
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($data) {
        $mahasiswaInfo = [
            'nim' => $data[0]['nim'] ?? '',
            'nama' => $data[0]['nama'] ?? '',
            'jurusan' => $data[0]['jurusan'] ?? '',
        ];
    } else {
        $stmtInfo = $pdo->prepare("SELECT nim, nama, jurusan FROM mahasiswa WHERE id_mahasiswa = :id");
        $stmtInfo->execute([':id' => $idMahasiswa]);
        $mahasiswaInfo = $stmtInfo->fetch(PDO::FETCH_ASSOC);
    }
}
?>

<h4 class="fw-bold"><span class="text-muted fw-light">Laporan /</span> KRS</h4>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="<?php echo page_url('laporan/laporankrs'); ?>">
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="mahasiswa" class="form-label">Mahasiswa</label>
                    <select class="form-select" id="mahasiswa" name="id_mahasiswa" required>
                        <option value="">Pilih Mahasiswa</option>
                        <?php foreach ($mahasiswaList as $mhs) : ?>
                            <option value="<?php echo $mhs['id_mahasiswa']; ?>" <?php echo ($idMahasiswa == $mhs['id_mahasiswa']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($mhs['nim'] . ' - ' . $mhs['nama']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="semester" class="form-label">Semester</label>
                    <input type="number" class="form-control" id="semester" name="semester" min="1" max="14" value="<?php echo htmlspecialchars($semester); ?>" required>
                </div>
                <div class="col-md-4">
                    <label for="tahun" class="form-label">Tahun Ajaran</label>
                    <input type="text" class="form-control" id="tahun" name="tahun_ajaran" placeholder="2024/2025" value="<?php echo htmlspecialchars($tahunAjaran); ?>" required>
                </div>
            </div>
            <div class="d-flex gap-2 mt-3">
                <button type="submit" class="btn btn-primary">Tampilkan</button>
                <a href="<?php echo page_url('laporan/laporankrs'); ?>" class="btn btn-outline-secondary">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <?php if ($idMahasiswa && $semester && $tahunAjaran) : ?>
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">Data KRS</h5>
                <?php if ($data) : ?>
                    <button type="button" class="btn btn-success btn-sm" id="btn-print-krs">Cetak PDF</button>
                <?php endif; ?>
            </div>
            <div id="laporan-krs">
                <?php if ($mahasiswaInfo) : ?>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div><strong>Nama:</strong> <?php echo htmlspecialchars($mahasiswaInfo['nama'] ?? '-'); ?></div>
                            <div><strong>NIM:</strong> <?php echo htmlspecialchars($mahasiswaInfo['nim'] ?? '-'); ?></div>
                        </div>
                        <div class="col-md-6">
                            <div><strong>Jurusan:</strong> <?php echo htmlspecialchars($mahasiswaInfo['jurusan'] ?? '-'); ?></div>
                            <div><strong>Semester/Tahun:</strong> <?php echo htmlspecialchars($semester . ' / ' . $tahunAjaran); ?></div>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr class="text-nowrap">
                                <th>No</th>
                                <th>Kode MK</th>
                                <th>Nama Mata Kuliah</th>
                                <th>SKS</th>
                                <th>Status</th>
                                <th>Tanggal Input</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($data) : ?>
                                <?php foreach ($data as $index => $row) : ?>
                                    <?php $tanggal = !empty($row['tanggal_pengisian']) ? date('d/m/Y H:i', strtotime($row['tanggal_pengisian'])) : '-'; ?>
                                    <tr>
                                        <td><?php echo $index + 1; ?></td>
                                        <td><?php echo htmlspecialchars($row['kode_matkul'] ?? '-'); ?></td>
                                        <td><?php echo htmlspecialchars($row['nama_matkul'] ?? '-'); ?></td>
                                        <td><?php echo htmlspecialchars($row['sks'] ?? '-'); ?></td>
                                        <td><?php echo htmlspecialchars($row['status']); ?></td>
                                        <td><?php echo htmlspecialchars($tanggal); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="6" class="text-center">Tidak ada data untuk kombinasi filter ini.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php else : ?>
            <div class="alert alert-info mb-0" role="alert">
                Silakan pilih mahasiswa, semester, dan tahun ajaran terlebih dahulu.
            </div>
        <?php endif; ?>
    </div>
</div>

<?php if ($idMahasiswa && $semester && $tahunAjaran && $data) : ?>
    <script>
        document.getElementById('btn-print-krs').addEventListener('click', function() {
            const element = document.getElementById('laporan-krs');
            const opt = {
                margin: 0.5,
                filename: 'laporan-krs-<?php echo htmlspecialchars($semester . '-' . $tahunAjaran); ?>.pdf',
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 2 },
                jsPDF: { unit: 'in', format: 'a4', orientation: 'portrait' }
            };
            html2pdf().set(opt).from(element).save();
        });
    </script>
<?php endif; ?>
