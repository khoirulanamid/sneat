<?php
include 'config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;

    if ($id > 0) {
        try {
            $stmt = $pdo->prepare("DELETE FROM krs WHERE id_krs = :id");
            $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            die('Gagal menghapus data: ' . $e->getMessage());
        }
    }
}

header('Location: ' . page_url('krs/krs'));
exit;
