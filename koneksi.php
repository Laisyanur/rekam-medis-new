<?php
$conn = mysqli_connect("localhost", "root", "", "db_rekam_medis");

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>