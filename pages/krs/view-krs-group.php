<?php
include '../../config/koneksi.php';

$id_mahasiswa = isset($_GET['id_mahasiswa']) ? (int)$_GET['id_mahasiswa'] : 0;
$semester = isset($_GET['semester']) ? (int)$_GET['semester'] : 0;
$tahun_ajaran = isset($_GET['tahun_ajaran']) ? $_GET['tahun_ajaran'] : '';

$krsEntries = [];
$student = null;

if ($id_mahasiswa && $semester && $tahun_ajaran) {
    $stmt = $pdo->prepare(
        "SELECT krs.*, m.nim, m.nama, m.jurusan, mk.kode_matkul, mk.nama_matkul, mk.sks
         FROM krs
         LEFT JOIN mahasiswa m ON krs.id_mahasiswa = m.id_mahasiswa
         LEFT JOIN matakuliah mk ON krs.id_matkul = mk.id_matkul
         WHERE krs.id_mahasiswa = :id_mahasiswa
           AND krs.semester = :semester
           AND krs.tahun_ajaran = :tahun_ajaran
         ORDER BY mk.nama_matkul ASC"
    );
    $stmt->execute([
        ':id_mahasiswa' => $id_mahasiswa,
        ':semester' => $semester,
        ':tahun_ajaran' => $tahun_ajaran,
    ]);
    $krsEntries = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!empty($krsEntries)) {
        $student = [
            'nama' => $krsEntries[0]['nama'],
            'nim' => $krsEntries[0]['nim'],
            'jurusan' => $krsEntries[0]['jurusan']
        ];
    }
}

$totalSks = 0;
foreach ($krsEntries as $e) { $totalSks += (int)($e['sks'] ?? 0); }

$backUrl = '../../index.php?page=krs/krs';
?>

<h4 class="fw-bold"><span class="text-muted fw-light">KRS /</span> Detail Grup KRS</h4>

<div class="card">
    <div class="card-body">
        <?php if (empty($krsEntries)) : ?>
            <div class="alert alert-warning">Data KRS tidak ditemukan untuk parameter yang diberikan.</div>
            <a href="<?php echo $backUrl; ?>" class="btn btn-secondary">Kembali</a>
        <?php else : ?>
            <div class="mb-3">
                <h5><?php echo htmlspecialchars($student['nama'] ?? '-'); ?></h5>
                <div class="text-muted">NIM: <?php echo htmlspecialchars($student['nim'] ?? '-'); ?> &middot; Jurusan: <?php echo htmlspecialchars($student['jurusan'] ?? '-'); ?></div>
                <div class="mt-2">Semester: <strong><?php echo htmlspecialchars($semester); ?></strong> &middot; Tahun Ajaran: <strong><?php echo htmlspecialchars($tahun_ajaran); ?></strong></div>
            </div>

            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode MK</th>
                            <th>Mata Kuliah</th>
                            <th>SKS</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; foreach ($krsEntries as $entry) : ?>
                            <tr>
                                <td><?php echo $i++; ?></td>
                                <td><?php echo htmlspecialchars($entry['kode_matkul'] ?? '-'); ?></td>
                                <td><?php echo htmlspecialchars($entry['nama_matkul'] ?? '-'); ?></td>
                                <td><?php echo htmlspecialchars($entry['sks'] ?? '-'); ?></td>
                                <td><?php echo htmlspecialchars($entry['status'] ?? '-'); ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <tr>
                            <td colspan="3" class="text-end"><strong>Total SKS</strong></td>
                            <td><strong><?php echo $totalSks; ?></strong></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                <a href="<?php echo $backUrl; ?>" class="btn btn-secondary">Kembali</a>
                <?php
                $editUrl = '../../index.php?page=krs/update-krs-group&id_mahasiswa=' . urlencode($id_mahasiswa) . '&semester=' . urlencode($semester) . '&tahun_ajaran=' . urlencode($tahun_ajaran);
                $deleteUrl = '../../index.php?page=krs/delete-krs-group&id_mahasiswa=' . urlencode($id_mahasiswa) . '&semester=' . urlencode($semester) . '&tahun_ajaran=' . urlencode($tahun_ajaran);
                ?>
                <a href="<?php echo $editUrl; ?>" class="btn btn-success">Edit Group</a>
                <a href="<?php echo $deleteUrl; ?>" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus semua entri KRS untuk mahasiswa ini pada semester dan tahun ajaran ini?');">Hapus Grup</a>
            </div>
        <?php endif; ?>
    </div>
</div>
