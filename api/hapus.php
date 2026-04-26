<?php
include(__DIR__ . '/koneksi.php');

$user = get_session();
if (!$user) {
    header("Location: login.php");
    exit;
}

$id = $_GET['id'] ?? '';
if (!empty($id)) {
    $id = mysqli_real_escape_string($conn, $id);
    mysqli_query($conn, "DELETE FROM pasien WHERE id_pasien='$id'");
}

header("Location: halaman_pasien.php");
exit;