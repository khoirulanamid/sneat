<?php
include_once __DIR__ . '/../../config/koneksi.php';

$query = "SELECT khs.*, mahasiswa.nim, mahasiswa.nama, matakuliah.nama_matkul, matakuliah.kode_matkul, matakuliah.sks
          FROM khs
          LEFT JOIN mahasiswa ON khs.id_mahasiswa = mahasiswa.id_mahasiswa
          LEFT JOIN matakuliah ON khs.id_matkul = matakuliah.id_matkul
          ORDER BY khs.updated_at DESC";
$stmt = $pdo->query($query);
$khsData = $stmt->fetchAll(PDO::FETCH_ASSOC);
$no = 1;
?>

<h4 class="fw-bold"><span class="text-muted fw-light">Tables /</span> Data KHS</h4>

<div class="card">
    <div class="card-body">
        <a href="<?php echo page_url('khs/tambah-khs'); ?>" class="btn btn-primary mb-3">+ Tambah KHS</a>

        <div class="table-responsive text-nowrap">
            <table class="table" id="khs-table">
                <thead>
                    <tr class="text-nowrap">
                        <th>No</th>
                        <th>Mahasiswa</th>
                        <th>Mata Kuliah</th>
                        <th>Semester</th>
                        <th>Tahun Ajaran</th>
                        <th>Nilai</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($khsData as $khs) : ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td>
                                <div class="fw-semibold"><?php echo htmlspecialchars($khs['nama'] ?? '-'); ?></div>
                                <small class="text-muted"><?php echo htmlspecialchars($khs['nim'] ?? ''); ?></small>
                            </td>
                            <td>
                                <div class="fw-semibold"><?php echo htmlspecialchars($khs['nama_matkul'] ?? '-'); ?></div>
                                <small class="text-muted"><?php echo htmlspecialchars($khs['kode_matkul'] ?? ''); ?></small>
                            </td>
                            <td><?php echo htmlspecialchars($khs['semester']); ?></td>
                            <td><?php echo htmlspecialchars($khs['tahun_ajaran']); ?></td>
                            <td>
                                <div class="fw-semibold"><?php echo htmlspecialchars(number_format((float)$khs['nilai_angka'], 2)); ?></div>
                                <small class="text-muted">Grade: <?php echo htmlspecialchars($khs['nilai_huruf']); ?></small>
                            </td>
                            <?php
                            $statusClass = 'primary';
                            if ($khs['status'] === 'Tidak Lulus') {
                                $statusClass = 'danger';
                            } elseif ($khs['status'] === 'Remedial') {
                                $statusClass = 'warning';
                            }
                            ?>
                            <td><span class="badge bg-label-<?php echo $statusClass; ?>"><?php echo htmlspecialchars($khs['status']); ?></span></td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <a class="btn btn-icon btn-outline-primary btn-sm" title="View" href="<?php echo page_url('khs/view-khs'); ?>?id=<?php echo urlencode($khs['id_khs']); ?>">
                                        <i class="bx bx-show-alt"></i>
                                    </a>
                                    <a class="btn btn-icon btn-outline-success btn-sm" title="Edit" href="<?php echo page_url('khs/update-khs'); ?>?id=<?php echo urlencode($khs['id_khs']); ?>">
                                        <i class="bx bx-edit-alt"></i>
                                    </a>
                                    <a class="btn btn-icon btn-outline-danger btn-sm" title="Delete" href="<?php echo page_url('khs/delete-khs'); ?>?id=<?php echo urlencode($khs['id_khs']); ?>">
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
