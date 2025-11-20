<?php
include 'config/koneksi.php';

$query = "SELECT * FROM dosen ORDER BY nama_dosen ASC";
$stmt = $pdo->query($query);
$dosenData = $stmt->fetchAll(PDO::FETCH_ASSOC);
$no = 1;
?>

<h4 class="fw-bold"><span class="text-muted fw-light">Tables /</span> Data Dosen</h4>

<div class="card">
    <div class="card-body">
        <a href="<?php echo page_url('dosen/tambah-dosen'); ?>" class="btn btn-primary mb-3">+ Tambah Dosen</a>

        <div class="table-responsive text-nowrap">

            <table class="table" id="dosen-table">
                <thead>
                    <tr class="text-nowrap">
                        <th>No</th>
                        <th>NIDN</th>
                        <th>Nama Dosen</th>
                        <th>Jenis Kelamin</th>
                        <th>Jabatan Akademik</th>
                        <th>Pendidikan Terakhir</th>
                        <th>Keahlian</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($dosenData as $dosen) : ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo htmlspecialchars($dosen['nidn']); ?></td>
                            <td><?php echo htmlspecialchars($dosen['nama_dosen']); ?></td>
                            <td><?php echo htmlspecialchars($dosen['jenis_kelamin']); ?></td>
                            <td><?php echo htmlspecialchars($dosen['jabatan_akademik']); ?></td>
                            <td><?php echo htmlspecialchars($dosen['pendidikan_terakhir']); ?></td>
                            <td><?php echo htmlspecialchars($dosen['keahlian']); ?></td>
                            <td><span class="badge bg-label-primary me-1"><?php echo htmlspecialchars($dosen['status_dosen']); ?></span></td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <a class="btn btn-icon btn-outline-primary btn-sm" title="View" href="<?php echo page_url('dosen/view-dosen'); ?>?id=<?php echo urlencode($dosen['id_dosen']); ?>">
                                        <i class="bx bx-show-alt"></i>
                                    </a>
                                    <a class="btn btn-icon btn-outline-success btn-sm" title="Edit" href="<?php echo page_url('dosen/edit-dosen'); ?>?id=<?php echo urlencode($dosen['id_dosen']); ?>">
                                        <i class="bx bx-edit-alt"></i>
                                    </a>
                                    <form method="POST" action="<?php echo page_url('dosen/delete-dosen'); ?>" class="d-inline" onsubmit="return confirm('Yakin hapus data ini?');">
                                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($dosen['id_dosen']); ?>">
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
