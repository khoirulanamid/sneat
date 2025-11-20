<?php
include 'config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;

    if ($id > 0) {
        try {
            $stmt = $pdo->prepare("DELETE FROM dosen WHERE id_dosen = :id");
            $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            // Jika gagal, tampilkan pesan lalu berhenti agar terlihat
            die('Gagal menghapus data: ' . $e->getMessage());
        }
    }
}

header("Location: " . page_url('dosen/dosen'));
exit;
