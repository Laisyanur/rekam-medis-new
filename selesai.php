<?php
session_start();
include 'koneksi.php';

if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);

    $query = mysqli_query($conn, "UPDATE pasien SET status='selesai' WHERE id_pasien='$id'");

    if ($query) {
        header("Location: halaman_dokter.php");
        exit;
    } else {
        echo "Gagal mengupdate status: " . mysqli_error($conn);
    }
} else {
    echo "ID Pasien tidak ditemukan!";
}
?>
