<?php
// api.php - hapus seluruh auth check, ganti jadi ini saja:
// include(__DIR__ . '/koneksi.php');

header('Content-Type: application/json');

$API_KEY = '6c367498619087b19fe2a82b1076baa6';
$action  = $_GET['action'] ?? '';

$opts = [
    "ssl" => [
        "verify_peer" => false,
        "verify_peer_name" => false,
    ],
];
$context = stream_context_create($opts);

$url = "https://webapi.bps.go.id/v1/api/domain/type/all/prov/00000/key/6c367498619087b19fe2a82b1076baa6/";

$response = @file_get_contents($url, false, $context);

if ($response === FALSE) {
    echo json_encode(['error' => 'Gagal terhubung ke API BPS']);
    exit;
}

$decoded = json_decode($response, true);
$allData = $decoded['data'][1] ?? [];

if ($action === 'provinsi') {
    $provinsi = array_values(array_filter($allData, function($item) {
        return substr($item['domain_id'], -2) === '00' && $item['domain_id'] !== '0000';
    }));
    echo json_encode(['status' => 'OK', 'data' => $provinsi]);
    exit;
}

if ($action === 'kabupatenkota') {
    $kode_prov = $_GET['kode'] ?? '';
    if (empty($kode_prov)) {
        echo json_encode(['error' => 'Kode provinsi diperlukan']);
        exit;
    }
    $prefix = substr($kode_prov, 0, 2);
    $kabkota = array_values(array_filter($allData, function($item) use ($prefix) {
        return substr($item['domain_id'], 0, 2) === $prefix 
               && substr($item['domain_id'], -2) !== '00';
    }));
    echo json_encode(['status' => 'OK', 'data' => $kabkota]);
    exit;
}



echo json_encode(['error' => 'Action tidak valid']);