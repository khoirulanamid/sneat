<?php
include 'config/koneksi.php';

$query = "SELECT krs.*, mahasiswa.nim, mahasiswa.nama, matakuliah.nama_matkul, matakuliah.kode_matkul, matakuliah.sks
          FROM krs
          LEFT JOIN mahasiswa ON krs.id_mahasiswa = mahasiswa.id_mahasiswa
          LEFT JOIN matakuliah ON krs.id_matkul = matakuliah.id_matkul
          ORDER BY krs.tanggal_pengisian DESC";
$stmt = $pdo->query($query);
$krsData = $stmt->fetchAll(PDO::FETCH_ASSOC);
$no = 1;
?>

<h4 class="fw-bold"><span class="text-muted fw-light">Tables /</span> Data KRS</h4>

<div class="card">
    <div class="card-body">
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
                        <th>Dicatat Pada</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($krsData as $krs) : ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td>
                                <div class="fw-semibold"><?php echo htmlspecialchars($krs['nama'] ?? '-'); ?></div>
                                <small class="text-muted"><?php echo htmlspecialchars($krs['nim'] ?? ''); ?></small>
                            </td>
                            <td>
                                <div class="fw-semibold"><?php echo htmlspecialchars($krs['nama_matkul'] ?? '-'); ?></div>
                                <small class="text-muted"><?php echo htmlspecialchars($krs['kode_matkul'] ?? ''); ?></small>
                            </td>
                            <td><?php echo htmlspecialchars($krs['semester']); ?></td>
                            <td><?php echo htmlspecialchars($krs['tahun_ajaran']); ?></td>
                            <td>
                                <span class="badge bg-label-<?php echo $krs['status'] === 'Disetujui' ? 'success' : ($krs['status'] === 'Ditolak' ? 'danger' : 'warning'); ?>">
                                    <?php echo htmlspecialchars($krs['status']); ?>
                                </span>
                            </td>
                            <?php
                            $recordedAt = !empty($krs['tanggal_pengisian'])
                                ? date('d/m/Y H:i', strtotime($krs['tanggal_pengisian']))
                                : '-';
                            ?>
                            <td><?php echo htmlspecialchars($recordedAt); ?></td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <a class="btn btn-icon btn-outline-primary btn-sm" title="View" href="<?php echo page_url('krs/view-krs'); ?>?id=<?php echo urlencode($krs['id_krs']); ?>">
                                        <i class="bx bx-show-alt"></i>
                                    </a>
                                    <a class="btn btn-icon btn-outline-success btn-sm" title="Edit" href="<?php echo page_url('krs/edit-krs'); ?>?id=<?php echo urlencode($krs['id_krs']); ?>">
                                        <i class="bx bx-edit-alt"></i>
                                    </a>
                                    <form method="POST" action="<?php echo page_url('krs/delete-krs'); ?>" class="d-inline" onsubmit="return confirm('Yakin hapus data ini?');">
                                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($krs['id_krs']); ?>">
                                        <button type="submit" class="btn btn-icon btn-outline-danger btn-sm" title="Delete">
                                            <i class="bx bx-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
