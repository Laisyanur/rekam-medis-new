<?php
session_start();
if(!isset($_SESSION['login'])){
    header("Location: login.php");
    exit;
}

include "koneksi.php";

$id = $_GET['id'];
$data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM pasien WHERE id_pasien='$id'"));

if(isset($_POST['update'])){
    $nama = $_POST['nama'];
    $umur = $_POST['umur'];
    $alamat = $_POST['alamat'];
    $keluhan = $_POST['keluhan'];
    $status = $_POST['status'];

    mysqli_query($conn, "UPDATE pasien SET 
        nama='$nama',
        umur='$umur',
        alamat='$alamat',
        keluhan='$keluhan',
        status='$status'
        WHERE id_pasien='$id'
    ");

    header("Location: halaman_pasien.php");
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Edit Pasien</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<!-- NAVBAR -->
<nav class="navbar navbar-dark bg-dark p-3">
    <div class="container">
        <span class="navbar-brand">Edit Data Pasien</span>
        <a href="halaman_pasien.php" class="btn btn-outline-light">Kembali</a>
    </div>
</nav>

<!-- CONTENT -->
<div class="container mt-5">
<div class="card shadow p-4">

<h4 class="mb-4">Form Edit Pasien</h4>

<form method="POST" class="row g-3">

<div class="col-md-6">
    <label class="form-label">Nama</label>
    <input type="text" name="nama" value="<?= $data['nama']; ?>" class="form-control" required>
</div>

<div class="col-md-6">
    <label class="form-label">Umur</label>
    <input type="number" name="umur" value="<?= $data['umur']; ?>" class="form-control" required>
</div>

<div class="col-md-6">
    <label class="form-label">Alamat</label>
    <input type="text" name="alamat" value="<?= $data['alamat']; ?>" class="form-control" required>
</div>

<div class="col-md-6">
    <label class="form-label">Keluhan</label>
    <input type="text" name="keluhan" value="<?= $data['keluhan']; ?>" class="form-control" required>
</div>

<div class="col-md-6">
    <label class="form-label">Status</label>
    <select name="status" class="form-select">
        <option value="menunggu" <?= $data['status']=='menunggu'?'selected':''; ?>>Menunggu</option>
        <option value="selesai" <?= $data['status']=='selesai'?'selected':''; ?>>Selesai</option>
    </select>
</div>

<div class="col-12 d-flex justify-content-end gap-2 mt-3">
    <a href="halaman_pasien.php" class="btn btn-secondary">Batal</a>
    <button type="submit" name="update" class="btn btn-primary">
        Update Data
    </button>
</div>

</form>

</div>
</div>

</body>
</html>