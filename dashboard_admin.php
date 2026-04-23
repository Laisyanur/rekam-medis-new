<?php
session_start();
if(!isset($_SESSION['login'])){
    header("Location: login.php");
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<nav class="navbar navbar-dark bg-dark">
<div class="container">
<span class="navbar-brand">Dashboard Admin</span>
<a href="logout.php" class="btn btn-danger">Logout</a>
</div>
</nav>

<div class="container mt-4">

<h3>Halo, <?php echo $_SESSION['nama']; ?> 👋</h3>

<div class="row mt-4">

<div class="col-md-4">
<div class="card shadow text-center p-3">
<h5>Kelola Rekam Medis</h5>
<a href="rekam_medis.php" class="btn btn-primary mt-2">Masuk</a>
</div>
</div>

<div class="col-md-4">
<div class="card shadow text-center p-3">
<h5>Kelola User</h5>
<a href="#" class="btn btn-success mt-2">Masuk</a>
</div>
</div>

</div>

</div>

</body>
</html>