<?php
// Variabel $pdo dan $base_url sudah tersedia dari file index.php
// Ambil data dosen dari database
$errorMessage = '';
try {
    $sql = "SELECT id_dosen, nidn, nama_dosen, jenis_kelamin, keahlian, jabatan_akademik, pendidikan_terakhir, status_dosen FROM dosen ORDER BY nama_dosen ASC";
    $stmt = $pdo->query($sql);
    $dosens = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $errorMessage = "ERROR: Tidak bisa mengambil data. " . $e->getMessage();
}
?>
<h4 class="fw-bold"><span class="text-muted fw-light">Data Master /</span> Data Dosen</h4>

<div class="card">
    <div class="card-body">
        <a href="<?= $base_url ?>dosen/tambah-dosen" class="btn btn-primary mb-3">+ Tambah Dosen</a>

        <div class="card">
            <div class="table-responsive text-nowrap">
                <?php if ($errorMessage) : ?>
                    <div class="alert alert-danger mb-3"><?= htmlspecialchars($errorMessage); ?></div>
                <?php endif; ?>
                <table class="table" id="dosen-table">
                    <thead>
                        <tr class="text-nowrap">
                            <th>No</th>
                            <th>NIDN</th>
                            <th>Nama Dosen</th>
                            <th>Jenis Kelamin</th>
                            <th>Keahlian</th>
                            <th>Jabatan</th>
                            <th>Pendidikan</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($dosens)) : ?>
                            <?php $no = 1; ?>
                            <?php foreach ($dosens as $dosen) : ?>
                                <tr>
                                    <th scope="row"><?= $no++; ?></th>
                                    <td><?= htmlspecialchars($dosen['nidn']); ?></td>
                                    <td><?= htmlspecialchars($dosen['nama_dosen']); ?></td>
                                    <td><?= htmlspecialchars($dosen['jenis_kelamin']); ?></td>
                                    <td><?= htmlspecialchars($dosen['keahlian']); ?></td>
                                    <td><?= htmlspecialchars($dosen['jabatan_akademik']); ?></td>
                                    <td><span class="badge bg-label-primary me-1"><?= htmlspecialchars($dosen['pendidikan_terakhir']); ?></span></td>
                                    <td><span class="badge bg-label-info me-1"><?= htmlspecialchars($dosen['status_dosen']); ?></span></td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <a class="btn btn-icon btn-outline-primary btn-sm" title="View" href="<?= page_url('dosen/view-dosen'); ?>?id=<?= urlencode($dosen['id_dosen']); ?>">
                                                <i class="bx bx-show-alt"></i>
                                            </a>
                                            <a class="btn btn-icon btn-outline-success btn-sm" title="Edit" href="<?= page_url('dosen/edit-dosen'); ?>?id=<?= urlencode($dosen['id_dosen']); ?>">
                                                <i class="bx bx-edit-alt"></i>
                                            </a>
                                            <a class="btn btn-icon btn-outline-danger btn-sm" title="Delete" href="<?= page_url('dosen/delete-dosen'); ?>?id=<?= urlencode($dosen['id_dosen']); ?>">
                                                <i class="bx bx-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="10" class="text-center">Tidak ada data dosen.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
