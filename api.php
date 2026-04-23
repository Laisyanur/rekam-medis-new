<?php
session_start();

// 1. SINKRONKAN DENGAN LOGIN: Gunakan $_SESSION['login']
if (!isset($_SESSION['login'])) {
    header('Content-Type: application/json');
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized - Silahkan login dulu']);
    exit;
}

header('Content-Type: application/json');

$API_KEY = '6c367498619087b19fe2a82b1076baa6';
$action  = $_GET['action'] ?? '';

// 2. FIX SSL ISSUE: Gunakan context agar localhost bisa ambil data HTTPS
$opts = [
    "ssl" => [
        "verify_peer" => false,
        "verify_peer_name" => false,
    ],
];
$context = stream_context_create($opts);

$url = "https://webapi.bps.go.id/v1/api/domain/type/all/prov/00000/key/6c367498619087b19fe2a82b1076baa6/";

// Ambil data dari BPS
$response = @file_get_contents($url, false, $context);

if ($response === FALSE) {
    echo json_encode(['error' => 'Gagal terhubung ke API BPS']);
    exit;
}

$decoded = json_decode($response, true);
$allData = $decoded['data'][1] ?? [];

// LOGIKA FILTER PROVINSI
if ($action === 'provinsi') {
    $provinsi = array_values(array_filter($allData, function($item) {
        // Domain ID BPS: Provinsi biasanya berakhiran '00' (misal 3500) 
        // dan bukan '0000' (Pusat)
        return substr($item['domain_id'], -2) === '00' && $item['domain_id'] !== '0000';
    }));
    echo json_encode(['status' => 'OK', 'data' => $provinsi]);
    exit;
}

// LOGIKA FILTER KABUPATEN/KOTA
if ($action === 'kabupatenkota') {
    $kode_prov = $_GET['kode'] ?? '';
    if (empty($kode_prov)) {
        echo json_encode(['error' => 'Kode provinsi diperlukan']);
        exit;
    }

    $prefix = substr($kode_prov, 0, 2); // Ambil 2 digit depan (contoh '35')
    $kabkota = array_values(array_filter($allData, function($item) use ($prefix) {
        // Cari yang 2 digit depannya sama, tapi bukan kode provinsi itu sendiri
        return substr($item['domain_id'], 0, 2) === $prefix 
               && substr($item['domain_id'], -2) !== '00';
    }));

    echo json_encode(['status' => 'OK', 'data' => $kabkota]);
    exit;
}

if ($action === 'kecamatan') {
    $kode_kab = $_GET['kode'] ?? '';
    if (empty($kode_kab)) {
        echo json_encode(['error' => 'Kode kabupaten diperlukan']);
        exit;
    }

    // Catatan: Di API BPS Domain, level terendah biasanya hanya sampai Kab/Kota.
    // Jika Anda ingin data Kecamatan, biasanya menggunakan endpoint 'get wilayah'
    // Namun untuk simulasi agar dropdown mengalir, kita buat filternya:
    
    $prefix = substr($kode_kab, 0, 4); // Ambil 4 digit (kode Kab)
    
    $kecamatan = array_values(array_filter($allData, function($item) use ($prefix, $kode_kab) {
        // Cari yang kode depannya sama dengan Kab, tapi ID-nya lebih panjang/berbeda
        return strpos($item['domain_id'], $prefix) === 0 && $item['domain_id'] !== $kode_kab;
    }));

    echo json_encode(['status' => 'OK', 'data' => $kecamatan]);
    exit;
}

echo json_encode(['error' => 'Action tidak valid']);

