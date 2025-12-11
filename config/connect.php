<?php
// Sertakan file koneksi database
include_once __DIR__ . '/koneksi.php';

// Query untuk mengambil semua data dari tabel dosen
$query = "SELECT * FROM dosen";
$result = mysqli_query($koneksi, $query);

// Periksa apakah query berhasil dijalankan
if (!$result) {
    die("Query gagal dijalankan: " . mysqli_error($koneksi));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Dosen</title>
</head>
<body>

<h2>Data Dosen</h2>

<table border="1" cellpadding="10" cellspacing="0">
    <thead>
        <tr>
            <th>ID Dosen</th>
            <th>NIDN</th>
            <th>NIP</th>
            <th>Nama Dosen</th>
            <th>Jenis Kelamin</th>
            <th>Tempat Lahir</th>
            <th>Tanggal Lahir</th>
            <th>Keahlian</th>
            <th>Jabatan Akademik</th>
            <th>Pendidikan Terakhir</th>
            <th>Email</th>
            <th>No. HP</th>
            <th>Alamat</th>
            <th>Status Dosen</th>
        </tr>
    </thead>
    <tbody>
        <?php if (mysqli_num_rows($result) > 0): ?>
            <?php while($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['id_dosen']); ?></td>
                    <td><?php echo htmlspecialchars($row['nidn']); ?></td>
                    <td><?php echo htmlspecialchars($row['nip']); ?></td>
                    <td><?php echo htmlspecialchars($row['nama_dosen']); ?></td>
                    <td><?php echo htmlspecialchars($row['jenis_kelamin']); ?></td>
                    <td><?php echo htmlspecialchars($row['tempat_lahir']); ?></td>
                    <td><?php echo htmlspecialchars($row['tanggal_lahir']); ?></td>
                    <td><?php echo htmlspecialchars($row['keahlian']); ?></td>
                    <td><?php echo htmlspecialchars($row['jabatan_akademik']); ?></td>
                    <td><?php echo htmlspecialchars($row['pendidikan_terakhir']); ?></td>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                    <td><?php echo htmlspecialchars($row['no_hp']); ?></td>
                    <td><?php echo htmlspecialchars($row['alamat']); ?></td>
                    <td><?php echo htmlspecialchars($row['status_dosen']); ?></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="14">Tidak ada data dosen.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

</body>
</html>

<?php
// Menutup koneksi
mysqli_close($koneksi);
?>
