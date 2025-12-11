<?php
include 'config/koneksi.php';

$query = "SELECT ktm.*, mahasiswa.nim, mahasiswa.nama, mahasiswa.jurusan
          FROM ktm
          LEFT JOIN mahasiswa ON ktm.id_mahasiswa = mahasiswa.id_mahasiswa
          ORDER BY ktm.tgl_terbit DESC";
$stmt = $pdo->query($query);
$ktmData = $stmt->fetchAll(PDO::FETCH_ASSOC);
$no = 1;
?>

<h4 class="fw-bold"><span class="text-muted fw-light">Tables /</span> Data KTM</h4>

<div class="card">
    <div class="card-body">
        <a href="<?php echo page_url('ktm/tambah-ktm'); ?>" class="btn btn-primary mb-3">+ Tambah KTM</a>

        <div class="table-responsive text-nowrap">
            <table class="table" id="ktm-table">
                <thead>
                    <tr class="text-nowrap">
                        <th>No</th>
                        <th>Nomor KTM</th>
                        <th>Mahasiswa</th>
                        <th>Tanggal Terbit</th>
                        <th>Masa Berlaku</th>
                        <th>Status</th>
                        <th>Keterangan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($ktmData as $ktm) : ?>
                        <?php
                        $terbit = !empty($ktm['tgl_terbit']) ? date('d/m/Y', strtotime($ktm['tgl_terbit'])) : '-';
                        $berlaku = !empty($ktm['masa_berlaku']) ? date('d/m/Y', strtotime($ktm['masa_berlaku'])) : '-';
                        ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td>
                                <div class="fw-semibold"><?php echo htmlspecialchars($ktm['nomor_kartu']); ?></div>
                                <?php if (!empty($ktm['foto_kartu'])) : ?>
                                    <a href="<?php echo htmlspecialchars($ktm['foto_kartu']); ?>" class="badge bg-label-info" target="_blank" rel="noopener">Lihat Foto</a>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="fw-semibold"><?php echo htmlspecialchars($ktm['nama'] ?? '-'); ?></div>
                                <small class="text-muted"><?php echo htmlspecialchars($ktm['nim'] ?? ''); ?></small>
                            </td>
                            <td><?php echo htmlspecialchars($terbit); ?></td>
                            <td><?php echo htmlspecialchars($berlaku); ?></td>
                            <td>
                                <?php
                                $statusClass = 'primary';
                                if ($ktm['status'] === 'Tidak Aktif') {
                                    $statusClass = 'secondary';
                                } elseif ($ktm['status'] === 'Hilang') {
                                    $statusClass = 'warning';
                                } elseif ($ktm['status'] === 'Rusak') {
                                    $statusClass = 'danger';
                                }
                                ?>
                                <span class="badge bg-label-<?php echo $statusClass; ?>"><?php echo htmlspecialchars($ktm['status']); ?></span>
                            </td>
                            <td><?php echo htmlspecialchars($ktm['keterangan'] ?? '-'); ?></td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <a class="btn btn-icon btn-outline-primary btn-sm" title="View" href="<?php echo page_url('ktm/view-ktm'); ?>?id=<?php echo urlencode($ktm['id_ktm']); ?>">
                                        <i class="bx bx-show-alt"></i>
                                    </a>
                                    <a class="btn btn-icon btn-outline-success btn-sm" title="Edit" href="<?php echo page_url('ktm/update-ktm'); ?>?id=<?php echo urlencode($ktm['id_ktm']); ?>">
                                        <i class="bx bx-edit-alt"></i>
                                    </a>
                                    <a class="btn btn-icon btn-outline-danger btn-sm" title="Delete" href="<?php echo page_url('ktm/delete-ktm'); ?>?id=<?php echo urlencode($ktm['id_ktm']); ?>">
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
