<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once __DIR__ . '/../../config/koneksi.php';

$id_mahasiswa = filter_input(INPUT_GET, 'id_mahasiswa', FILTER_VALIDATE_INT);
$semester = filter_input(INPUT_GET, 'semester', FILTER_VALIDATE_INT);
$tahun_ajaran = filter_input(INPUT_GET, 'tahun_ajaran', FILTER_SANITIZE_STRING);

$redirect_url = '../../index.php?page=krs/krs';

if (!$id_mahasiswa || !$semester || !$tahun_ajaran) {
    $_SESSION['krs_error'] = "Parameter tidak valid untuk menghapus grup KRS.";
    header("Location: $redirect_url");
    exit;
}

try {
    $pdo->beginTransaction();

    $stmt = $pdo->prepare(
        "DELETE FROM krs 
         WHERE id_mahasiswa = :id_mahasiswa 
           AND semester = :semester 
           AND tahun_ajaran = :tahun_ajaran"
    );

    $stmt->execute([
        ':id_mahasiswa' => $id_mahasiswa,
        ':semester' => $semester,
        ':tahun_ajaran' => $tahun_ajaran,
    ]);

    $pdo->commit();

    $_SESSION['krs_success'] = "Grup KRS berhasil dihapus.";

} catch (PDOException $e) {
    $pdo->rollBack();
    $_SESSION['krs_error'] = "Gagal menghapus grup KRS: " . $e->getMessage();
}

header("Location: $redirect_url");
exit;
