<?php
include_once __DIR__ . '/../../config/koneksi.php';

$query = "SELECT * FROM mahasiswa ORDER BY nama ASC";
$stmt = $pdo->query($query);
$mahasiswaData = $stmt->fetchAll(PDO::FETCH_ASSOC);
$no = 1;
?>

<h4 class="fw-bold"><span class="text-muted fw-light">Tables /</span> Data Mahasiswa</h4>

<div class="card">
    <div class="card-body">
        <a href="<?php echo page_url('mahasiswa/tambah-mahasiswa'); ?>" class="btn btn-primary mb-3">+ Tambah Mahasiswa</a>

        <div class="table-responsive text-nowrap">

            <table class="table" id="mahasiswa-table">
                <thead>
                    <tr class="text-nowrap">
                        <th>No</th>
                        <th>NIM</th>
                        <th>Nama</th>
                        <th>Jenis Kelamin</th>
                        <th>Jurusan</th>
                        <th>Tahun Masuk</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($mahasiswaData as $mahasiswa) : ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo htmlspecialchars($mahasiswa['nim']); ?></td>
                            <td><?php echo htmlspecialchars($mahasiswa['nama']); ?></td>
                            <td><?php echo htmlspecialchars($mahasiswa['jenis_kelamin']); ?></td>
                            <td><?php echo htmlspecialchars($mahasiswa['jurusan']); ?></td>
                            <td><?php echo htmlspecialchars($mahasiswa['tahun_masuk']); ?></td>
                            <td><span class="badge bg-label-primary me-1"><?php echo htmlspecialchars($mahasiswa['status']); ?></span></td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <a class="btn btn-icon btn-outline-primary btn-sm" title="View" href="<?php echo page_url('mahasiswa/view-mahasiswa'); ?>?nim=<?php echo urlencode($mahasiswa['nim']); ?>">
                                        <i class="bx bx-show-alt"></i>
                                    </a>
                                    <a class="btn btn-icon btn-outline-success btn-sm" title="Edit" href="<?php echo page_url('mahasiswa/update-mahasiswa'); ?>?nim=<?php echo urlencode($mahasiswa['nim']); ?>">
                                        <i class="bx bx-edit-alt"></i>
                                    </a>
                                    <a class="btn btn-icon btn-outline-danger btn-sm" title="Delete" href="<?php echo page_url('mahasiswa/delete-mahasiswa'); ?>?nim=<?php echo urlencode($mahasiswa['nim']); ?>">
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
