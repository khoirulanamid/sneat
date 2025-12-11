<?php
include 'config/koneksi.php';

$query = "SELECT krs.*, mahasiswa.nim, mahasiswa.nama, matakuliah.nama_matkul, matakuliah.kode_matkul, matakuliah.sks
          FROM krs
          LEFT JOIN mahasiswa ON krs.id_mahasiswa = mahasiswa.id_mahasiswa
          LEFT JOIN matakuliah ON krs.id_matkul = matakuliah.id_matkul
          ORDER BY krs.tanggal_pengisian DESC";
$stmt = $pdo->query($query);
$krsData = $stmt->fetchAll(PDO::FETCH_ASSOC);

$groupedKrs = [];
foreach ($krsData as $krs) {
    $key = $krs['id_mahasiswa'] . '-' . $krs['semester'] . '-' . $krs['tahun_ajaran'];
    if (!isset($groupedKrs[$key])) {
        $groupedKrs[$key] = [
            'id_mahasiswa' => $krs['id_mahasiswa'],
            'mahasiswa' => [
                'nama' => $krs['nama'],
                'nim' => $krs['nim'],
            ],
            'semester' => $krs['semester'],
            'tahun_ajaran' => $krs['tahun_ajaran'],
            'status' => $krs['status'],
            'tanggal_pengisian' => $krs['tanggal_pengisian'],
            'matakuliah' => [],
            'krs_entries' => []
        ];
    }
    $groupedKrs[$key]['matakuliah'][] = [
        'nama_matkul' => $krs['nama_matkul'],
        'kode_matkul' => $krs['kode_matkul'],
    ];
    $groupedKrs[$key]['krs_entries'][] = $krs;
}
$no = 1;
?>

<h4 class="fw-bold"><span class="text-muted fw-light">Tables /</span> Data KRS</h4>

<div class="card">
    <div class="card-body">
        <?php if (!empty($_SESSION['krs_success'])) : ?>
            <div class="alert alert-success">
                <?php echo $_SESSION['krs_success']; unset($_SESSION['krs_success']); ?>
            </div>
        <?php endif; ?>
        <?php if (!empty($_SESSION['krs_error'])) : ?>
            <div class="alert alert-danger">
                <?php echo $_SESSION['krs_error']; unset($_SESSION['krs_error']); ?>
            </div>
        <?php endif; ?>
        <a href="<?php echo page_url('krs/tambah-krs'); ?>" class="btn btn-primary mb-3">+ Tambah KRS</a>

        <div class="table-responsive text-nowrap">
            <table class="table" id="krs-table">
                <thead>
                    <tr class="text-nowrap">
                        <th>No</th>
                        <th>Mahasiswa</th>
                        <th>Mata Kuliah</th>
                        <th>Semester</th>
                        <th>Tahun Ajaran</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($groupedKrs as $groupKey => $group) : ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td>
                                <div class="fw-semibold"><?php echo htmlspecialchars($group['mahasiswa']['nama'] ?? '-'); ?></div>
                                <small class="text-muted"><?php echo htmlspecialchars($group['mahasiswa']['nim'] ?? ''); ?></small>
                            </td>
                            <td>
                                <ul class="list-unstyled">
                                    <?php foreach ($group['matakuliah'] as $matkul) : ?>
                                        <li>
                                            <div class="fw-semibold"><?php echo htmlspecialchars($matkul['nama_matkul'] ?? '-'); ?></div>
                                            <small class="text-muted"><?php echo htmlspecialchars($matkul['kode_matkul'] ?? ''); ?></small>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </td>
                            <td><?php echo htmlspecialchars($group['semester']); ?></td>
                            <td><?php echo htmlspecialchars($group['tahun_ajaran']); ?></td>
                            <td>
                                <span class="badge bg-label-<?php echo $group['status'] === 'Disetujui' ? 'success' : ($group['status'] === 'Ditolak' ? 'danger' : 'warning'); ?>">
                                    <?php echo htmlspecialchars($group['status']); ?>
                                </span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <!-- Details button removed: 'View' now shows all entries for the group -->
                                    <?php
                                    $id_mahasiswa = $group['id_mahasiswa'];
                                    $semester = $group['semester'];
                                    $tahun_ajaran = $group['tahun_ajaran'];
                                    // Link to combined view which will handle group display/edit/delete
                                    $baseView = page_url('krs/view-krs');
                                    $query = '?id_mahasiswa=' . urlencode($id_mahasiswa) . '&semester=' . urlencode($semester) . '&tahun_ajaran=' . urlencode($tahun_ajaran);
                                    $view_url = $baseView . $query;
                                    // For simplicity, Edit and Delete also go to the group view where actions are available
                                    $edit_url = $baseView . $query;
                                    $delete_url = $baseView . $query;
                                    ?>
                                    <a class="btn btn-icon btn-outline-primary btn-sm" title="View Group" href="<?php echo $view_url; ?>">
                                        <i class="bx bx-show-alt"></i>
                                    </a>
                                    <a class="btn btn-icon btn-outline-success btn-sm" title="Edit Group" href="<?php echo $edit_url; ?>">
                                        <i class="bx bx-edit-alt"></i>
                                    </a>
                                    <a class="btn btn-icon btn-outline-danger btn-sm" title="Delete Group" href="<?php echo $delete_url; ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus semua entri KRS untuk mahasiswa ini pada semester dan tahun ajaran ini?');">
                                        <i class="bx bx-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    // Ensure bootstrap JS is loaded
    // You might need to include this in your main layout file if it's not already there
</script>