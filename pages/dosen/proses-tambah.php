<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once __DIR__ . '/../../config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $redir = page_url('dosen/tambah-dosen');
    if (!headers_sent()) {
        header('Location: ' . $redir);
        exit;
    }
    echo '<script>window.location.href = ' . json_encode($redir) . ';</script>';
    exit;
}

$nidn = trim($_POST['nidn'] ?? '');
$nip = trim($_POST['nip'] ?? '');
$namaDosen = trim($_POST['nama_dosen'] ?? '');
$jenisKelamin = $_POST['jenis_kelamin'] ?? '';
$tempatLahir = trim($_POST['tempat_lahir'] ?? '');
$tanggalLahir = trim($_POST['tanggal_lahir'] ?? '');
$keahlian = trim($_POST['keahlian'] ?? '');
$jabatanAkademik = trim($_POST['jabatan_akademik'] ?? '');
$pendidikanTerakhir = trim($_POST['pendidikan_terakhir'] ?? '');
$email = trim($_POST['email'] ?? '');
$noHp = trim($_POST['no_hp'] ?? '');
$alamat = trim($_POST['alamat'] ?? '');
$statusDosen = $_POST['status_dosen'] ?? '';
$fotoPath = '';

$allowedJabatan = ['Asisten Ahli', 'Lektor', 'Lektor Kepala', 'Guru Besar'];
if (!in_array($jabatanAkademik, $allowedJabatan, true)) {
    $jabatanAkademik = 'Asisten Ahli';
}
$allowedPendidikan = ['S1', 'S2', 'S3'];
if (!in_array($pendidikanTerakhir, $allowedPendidikan, true)) {
    $pendidikanTerakhir = 'S1';
}
$allowedStatus = ['Tetap', 'Kontrak', 'Luar'];
if (!in_array($statusDosen, $allowedStatus, true)) {
    $statusDosen = 'Tetap';
}

$dosen = [
    'nidn' => $nidn,
    'nip' => $nip,
    'nama_dosen' => $namaDosen,
    'jenis_kelamin' => $jenisKelamin,
    'tempat_lahir' => $tempatLahir,
    'tanggal_lahir' => $tanggalLahir,
    'keahlian' => $keahlian,
    'jabatan_akademik' => $jabatanAkademik,
    'pendidikan_terakhir' => $pendidikanTerakhir,
    'email' => $email,
    'no_hp' => $noHp,
    'alamat' => $alamat,
    'status_dosen' => $statusDosen,
    'foto' => '',
];

if ($nidn && $namaDosen && $jenisKelamin && $pendidikanTerakhir && $keahlian && $statusDosen) {
    try {
        // Upload foto jika ada
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] !== UPLOAD_ERR_NO_FILE) {
            $file = $_FILES['foto'];
            if ($file['error'] !== UPLOAD_ERR_OK) {
                throw new RuntimeException('Upload foto gagal. Silakan coba lagi.');
            }
            $allowedExt = ['jpg', 'jpeg', 'png', 'webp'];
            $maxSize = 2 * 1024 * 1024;
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            if (!in_array($ext, $allowedExt, true)) {
                throw new RuntimeException('Format foto harus jpg, jpeg, png, atau webp.');
            }
            if ($file['size'] > $maxSize) {
                throw new RuntimeException('Ukuran foto maksimal 2MB.');
            }
            $uploadDir = __DIR__ . '/../../public/uploads/dosen/';
            if (!is_dir($uploadDir) && !mkdir($uploadDir, 0777, true)) {
                throw new RuntimeException('Gagal membuat folder upload.');
            }
            $newName = 'dosen-' . time() . '-' . bin2hex(random_bytes(4)) . '.' . $ext;
            $targetPath = $uploadDir . $newName;
            if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
                throw new RuntimeException('Gagal menyimpan foto dosen.');
            }
            $fotoPath = 'public/uploads/dosen/' . $newName;
        }

        $insert = $pdo->prepare(
            "INSERT INTO dosen (nidn, nip, nama_dosen, jenis_kelamin, tempat_lahir, tanggal_lahir, keahlian, jabatan_akademik, pendidikan_terakhir, email, no_hp, alamat, status_dosen, foto)
             VALUES (:nidn, :nip, :nama_dosen, :jenis_kelamin, :tempat_lahir, :tanggal_lahir, :keahlian, :jabatan_akademik, :pendidikan_terakhir, :email, :no_hp, :alamat, :status_dosen, :foto)"
        );

        $insert->execute([
            ':nidn' => $nidn,
            ':nip' => $nip ?: null,
            ':nama_dosen' => $namaDosen,
            ':jenis_kelamin' => $jenisKelamin,
            ':tempat_lahir' => $tempatLahir ?: null,
            ':tanggal_lahir' => $tanggalLahir ?: null,
            ':keahlian' => $keahlian,
            ':jabatan_akademik' => $jabatanAkademik,
            ':pendidikan_terakhir' => $pendidikanTerakhir,
            ':email' => $email ?: null,
            ':no_hp' => $noHp ?: null,
            ':alamat' => $alamat ?: null,
            ':status_dosen' => $statusDosen,
            ':foto' => $fotoPath,
        ]);

        unset($_SESSION['tambah_dosen_error'], $_SESSION['tambah_dosen_old']);
        $redir = page_url('dosen/dosen');
        if (!headers_sent()) {
            header("Location: " . $redir);
            exit;
        }
        echo '<script>window.location.href = ' . json_encode($redir) . ';</script>';
        exit;
    } catch (PDOException $e) {
        $errorMessage = 'Gagal menambah data: ' . $e->getMessage();
    }
} else {
    $errorMessage = 'Semua field wajib diisi.';
}

$_SESSION['tambah_dosen_error'] = $errorMessage ?? '';
$_SESSION['tambah_dosen_old'] = $dosen;

$redir = page_url('dosen/tambah-dosen');
if (!headers_sent()) {
    header("Location: " . $redir);
    exit;
}
echo '<script>window.location.href = ' . json_encode($redir) . ';</script>';
exit;
