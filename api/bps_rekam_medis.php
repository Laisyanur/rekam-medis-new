<?php
header('Content-Type: application/json'); 

// URL API BPS spesifik yang sudah dites di Postman
$url = "https://webapi.bps.go.id/v1/api/list/model/data/lang/ind/domain/3500/var/206/th/118/key/6c367498619087b19fe2a82b1076baa6";

// ambil data
$response = file_get_contents($url);

// cek error
if ($response === FALSE) {
    echo json_encode(["error" => "Gagal mengambil data"]);
    exit;
}

// kirim ke frontend
echo $response;
?>