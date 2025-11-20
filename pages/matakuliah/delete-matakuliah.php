<?php
include 'config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kodeMatkul = isset($_POST['kode']) ? trim($_POST['kode']) : '';

    if ($kodeMatkul !== '') {
        try {
            $stmt = $pdo->prepare("DELETE FROM matakuliah WHERE kode_matkul = :kode_matkul");
            $stmt->execute([':kode_matkul' => $kodeMatkul]);
        } catch (PDOException $e) {
            die('Gagal menghapus data: ' . $e->getMessage());
        }
    }
}

header("Location: " . page_url('matakuliah/matakuliah'));
exit;
