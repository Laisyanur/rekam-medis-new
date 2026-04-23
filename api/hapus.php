<?php
include "koneksi.php";

$id = $_GET['id'];

mysqli_query($conn, "DELETE FROM pasien WHERE id_pasien='$id'");

header("Location: halaman_pasien.php");