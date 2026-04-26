<?php
// Pastikan session_start() dan include SEBELUM output apapun
session_start();
include(__DIR__ . '/koneksi.php');

if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $result = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");

    if ($result && mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);

        if (password_verify($password, $row['password'])) {
            $_SESSION['login'] = true;
            // Gunakan isset agar tidak error jika kolom tidak ada
            $_SESSION['id']   = isset($row['id']) ? $row['id'] : null;
            $_SESSION['nama'] = isset($row['nama']) ? $row['nama'] : '';
            $_SESSION['role'] = isset($row['role']) ? $row['role'] : '';

            header("Location: halaman_pasien.php");
            exit;
        } else {
            $error = "Password salah!";
        }
    } else {
        $error = "Email tidak terdaftar!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center vh-100">
<div class="container col-md-4">
    <div class="card shadow p-4">
        <h4 class="text-center mb-4">Login</h4>

        <?php if(isset($error)) : ?>
            <div class="alert alert-danger text-center"><?= $error; ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" placeholder="Email" required>
            </div>
            <div class="mb-3">
                <label>Password</label>
                <input type="password" name="password" class="form-control" placeholder="Password" required>
            </div>
            <button name="login" class="btn btn-primary w-100">Login</button>
        </form>
        <p class="text-center mt-3">Belum punya akun? <a href="register.php">Daftar</a></p>
    </div>
</div>
</body>
</html>