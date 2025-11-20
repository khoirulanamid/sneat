<?php
include 'config/koneksi.php';

$query = "SELECT matakuliah.*, dosen.nama_dosen 
          FROM matakuliah 
          LEFT JOIN dosen ON matakuliah.id_dosen = dosen.id_dosen
          ORDER BY matakuliah.nama_matkul ASC";
$stmt = $pdo->query($query);
$matakuliahData = $stmt->fetchAll(PDO::FETCH_ASSOC);
$no = 1;
?>

<h4 class="fw-bold"><span class="text-muted fw-light">Tables /</span> Data Mata Kuliah</h4>

<div class="card">
    <div class="card-body">
        <a href="<?php echo page_url('matakuliah/tambah-matakuliah'); ?>" class="btn btn-primary mb-3">+ Tambah Mata Kuliah</a>

        <div class="table-responsive text-nowrap">

            <table class="table" id="matakuliah-table">
                <thead>
                    <tr class="text-nowrap">
                        <th>No</th>
                        <th>Kode</th>
                        <th>Nama Mata Kuliah</th>
                        <th>SKS</th>
                        <th>Semester</th>
                        <th>Dosen Pengampu</th>
                        <th>Jenis</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($matakuliahData as $matkul) : ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo htmlspecialchars($matkul['kode_matkul']); ?></td>
                            <td><?php echo htmlspecialchars($matkul['nama_matkul']); ?></td>
                            <td><?php echo htmlspecialchars($matkul['sks']); ?></td>
                            <td><?php echo htmlspecialchars($matkul['semester']); ?></td>
                            <td><?php echo htmlspecialchars($matkul['nama_dosen'] ?? 'Belum Ditentukan'); ?></td>
                            <td><?php echo htmlspecialchars($matkul['jenis_matkul']); ?></td>
                            <td><span class="badge bg-label-primary me-1"><?php echo htmlspecialchars($matkul['status']); ?></span></td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <a class="btn btn-icon btn-outline-primary btn-sm" title="View" href="<?php echo page_url('matakuliah/view-matakuliah'); ?>?kode=<?php echo urlencode($matkul['kode_matkul']); ?>">
                                        <i class="bx bx-show-alt"></i>
                                    </a>
                                    <a class="btn btn-icon btn-outline-success btn-sm" title="Edit" href="<?php echo page_url('matakuliah/edit-matakuliah'); ?>?kode=<?php echo urlencode($matkul['kode_matkul']); ?>">
                                        <i class="bx bx-edit-alt"></i>
                                    </a>
                                    <form method="POST" action="<?php echo page_url('matakuliah/delete-matakuliah'); ?>" class="d-inline" onsubmit="return confirm('Yakin hapus data ini?');">
                                        <input type="hidden" name="kode" value="<?php echo htmlspecialchars($matkul['kode_matkul']); ?>">
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
