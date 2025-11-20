<?php
include 'config/koneksi.php';

$mahasiswaList = $pdo->query("SELECT id_mahasiswa, nim, nama FROM mahasiswa ORDER BY nama ASC")->fetchAll(PDO::FETCH_ASSOC);

$idMahasiswa = $_GET['id_mahasiswa'] ?? '';
$semester = $_GET['semester'] ?? '';
$tahunAjaran = $_GET['tahun_ajaran'] ?? '';

$filters = [];
$params = [];

if ($idMahasiswa) {
    $filters[] = 'khs.id_mahasiswa = :id_mahasiswa';
    $params[':id_mahasiswa'] = $idMahasiswa;
}

if ($semester) {
    $filters[] = 'khs.semester = :semester';
    $params[':semester'] = (int)$semester;
}

if ($tahunAjaran) {
    $filters[] = 'khs.tahun_ajaran = :tahun_ajaran';
    $params[':tahun_ajaran'] = $tahunAjaran;
}

$whereClause = $filters ? 'WHERE ' . implode(' AND ', $filters) : '';

$data = [];
$mahasiswaInfo = null;

if ($idMahasiswa) {
    $stmt = $pdo->prepare(
        "SELECT khs.*, mahasiswa.nim, mahasiswa.nama, mahasiswa.jurusan,
                matakuliah.nama_matkul, matakuliah.kode_matkul, matakuliah.sks
         FROM khs
         LEFT JOIN mahasiswa ON khs.id_mahasiswa = mahasiswa.id_mahasiswa
         LEFT JOIN matakuliah ON khs.id_matkul = matakuliah.id_matkul
         $whereClause
         ORDER BY khs.updated_at DESC"
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

function hitungIP(array $records): float
{
    if (!$records) {
        return 0.0;
    }
    $totalBobot = 0;
    $totalSks = 0;
    foreach ($records as $item) {
        $sks = (int)($item['sks'] ?? 0);
        $grade = $item['nilai_huruf'] ?? 'E';
        $bobot = match ($grade) {
            'A' => 4,
            'B' => 3,
            'C' => 2,
            'D' => 1,
            default => 0,
        };
        $totalBobot += $bobot * $sks;
        $totalSks += $sks;
    }
    return $totalSks > 0 ? round($totalBobot / $totalSks, 2) : 0.0;
}

$ipSemester = hitungIP($data);
?>

<h4 class="fw-bold"><span class="text-muted fw-light">Laporan /</span> KHS</h4>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="<?php echo page_url('laporan/laporankhs'); ?>">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label" for="mahasiswa">Mahasiswa</label>
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
                    <label class="form-label" for="semester">Semester</label>
                    <input type="number" class="form-control" id="semester" name="semester" min="1" max="14" value="<?php echo htmlspecialchars($semester); ?>" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label" for="tahun">Tahun Ajaran</label>
                    <input type="text" class="form-control" id="tahun" name="tahun_ajaran" placeholder="2024/2025" value="<?php echo htmlspecialchars($tahunAjaran); ?>" required>
                </div>
            </div>
            <div class="d-flex gap-2 mt-3">
                <button type="submit" class="btn btn-primary">Tampilkan</button>
                <a href="<?php echo page_url('laporan/laporankhs'); ?>" class="btn btn-outline-secondary">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <?php if ($idMahasiswa && $semester && $tahunAjaran) : ?>
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">Data KHS</h5>
                <?php if ($data) : ?>
                    <button type="button" class="btn btn-success btn-sm" id="btn-print-khs-laporan">Cetak PDF</button>
                <?php endif; ?>
            </div>
            <div id="laporan-khs">
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
                                <th>Nilai Angka</th>
                                <th>Nilai Huruf</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($data) : ?>
                                <?php foreach ($data as $index => $row) : ?>
                                    <tr>
                                        <td><?php echo $index + 1; ?></td>
                                        <td><?php echo htmlspecialchars($row['kode_matkul'] ?? '-'); ?></td>
                                        <td><?php echo htmlspecialchars($row['nama_matkul'] ?? '-'); ?></td>
                                        <td><?php echo htmlspecialchars($row['sks'] ?? '-'); ?></td>
                                        <td><?php echo htmlspecialchars(number_format((float)$row['nilai_angka'], 2)); ?></td>
                                        <td><?php echo htmlspecialchars($row['nilai_huruf']); ?></td>
                                        <td><?php echo htmlspecialchars($row['status']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                <tr>
                                    <td colspan="6" class="text-end"><strong>IP Semester</strong></td>
                                    <td><strong><?php echo htmlspecialchars(number_format($ipSemester, 2)); ?></strong></td>
                                </tr>
                            <?php else : ?>
                                <tr>
                                    <td colspan="7" class="text-center">Tidak ada data untuk kombinasi filter ini.</td>
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
        document.getElementById('btn-print-khs-laporan').addEventListener('click', function() {
            const element = document.getElementById('laporan-khs');
            const opt = {
                margin: 0.5,
                filename: 'laporan-khs-<?php echo htmlspecialchars($semester . '-' . $tahunAjaran); ?>.pdf',
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 2 },
                jsPDF: { unit: 'in', format: 'a4', orientation: 'portrait' }
            };
            html2pdf().set(opt).from(element).save();
        });
    </script>
<?php endif; ?>
