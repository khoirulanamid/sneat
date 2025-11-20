<?php
include 'config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nim = isset($_POST['nim']) ? trim($_POST['nim']) : '';

    if ($nim !== '') {
        try {
            $stmt = $pdo->prepare("DELETE FROM mahasiswa WHERE nim = :nim");
            $stmt->execute([':nim' => $nim]);
        } catch (PDOException $e) {
            die('Gagal menghapus data: ' . $e->getMessage());
        }
    }
}

header("Location: " . page_url('mahasiswa/mahasiswa'));
exit;
