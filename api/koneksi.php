<?php

$host     = "gateway01.ap-southeast-1.prod.alicloud.tidbcloud.com";  // ganti dengan host kamu
$port     = 4000;                        // ganti jika berbeda
$username = "3k3W85Za9nCEk6D.root";             // ganti dengan user kamu
$password = "w880r34TZm7jjqmd";             // ganti dengan password kamu
$database = "db_rekam_medis";

$conn = mysqli_init();

// TiDB Cloud Serverless WAJIB pakai SSL
mysqli_ssl_set($conn, NULL, NULL, NULL, NULL, NULL);
mysqli_real_connect(
    $conn,
    $host,
    $username,
    $password,
    $database,
    $port,
    NULL,
    MYSQLI_CLIENT_SSL
);

if (mysqli_connect_errno()) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

function get_session() {
    if (!isset($_COOKIE['user_session'])) return null;
    $data = json_decode(base64_decode($_COOKIE['user_session']), true);
    if (!$data || $data['exp'] < time()) return null; // expired
    return $data;
}
?>