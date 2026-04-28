<?php
include "koneksi.php";

if(isset($_POST['register'])){
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Generate id_pasien otomatis dari data terakhir
    $result = mysqli_query($conn, "SELECT MAX(id_pasien) as max_id FROM users");
    $row = mysqli_fetch_assoc($result);
    $id_pasien = ($row['max_id'] ?? 0) + 1;

    $query = "INSERT INTO users (id_pasien, nama, email, password) VALUES ('$id_pasien', '$nama', '$email', '$password')";
    if(mysqli_query($conn, $query)){
        echo "<script>alert('Register Berhasil!'); window.location='login.php';</script>";
    } else {
        echo "<script>alert('Register Gagal: " . mysqli_error($conn) . "');</script>";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center vh-100">
    <div class="container col-md-4">
        <div class="card shadow p-4">
            <h4 class="text-center">Register Sistem</h4>
            <form method="POST">
                <div class="mb-3">
                    <label>Nama Lengkap</label>
                    <input type="text" name="nama" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button type="submit" name="register" class="btn btn-primary w-100">Daftar Sekarang</button>
                <p class="mt-3 text-center">Sudah punya akun? <a href="login.php">Login</a></p>
            </form>
        </div>
    </div>
</body>
</html>