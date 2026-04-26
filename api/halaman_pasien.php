<?php

// include DULU sebelum memanggil get_session()
include(__DIR__ . '/koneksi.php');

$user = get_session();
if (!$user) {
    header("Location: login.php");
    exit;
}

//  Generate ID Otomatis
$query_id = mysqli_query($conn, "SELECT id_pasien FROM pasien ORDER BY id_pasien DESC LIMIT 1");

if ($query_id) {
    $data_id = mysqli_fetch_assoc($query_id);
    if($data_id){
        $last_id = $data_id['id_pasien'];
        $number = (int) substr($last_id, 3); 
        $number++;
        $id_pasien = "PSN" . str_pad($number, 3, "0", STR_PAD_LEFT);
    } else {
        $id_pasien = "PSN001";
    }
} else {
    $id_pasien = "PSN001";
}

if(isset($_POST['tambah'])){
    $nama    = mysqli_real_escape_string($conn, $_POST['nama']);
    $umur    = mysqli_real_escape_string($conn, $_POST['umur']);
    $keluhan = mysqli_real_escape_string($conn, $_POST['keluhan']);

    // AMBIL DARI INPUT HIDDEN TADI
    // Jika input hidden kosong, gunakan default "-" agar tidak NULL
    $alamat_final = isset($_POST['alamat_lengkap']) ? $_POST['alamat_lengkap'] : '-';
    $alamat = mysqli_real_escape_string($conn, $alamat_final);

    $insert = mysqli_query($conn, "INSERT INTO pasien 
    (id_pasien, nama, umur, alamat, keluhan, status) 
    VALUES 
    ('$id_pasien', '$nama', '$umur', '$alamat', '$keluhan', 'menunggu')");

    if($insert){
        header("Location: halaman_pasien.php");
        exit;
    }
}

// ====== BPS API ======
$bps_url = "https://webapi.bps.go.id/v1/api/list/model/data/lang/ind/domain/3500/var/206/th/118/key/6c367498619087b19fe2a82b1076baa6";
$bps_json = @file_get_contents($bps_url);
$bps_data = json_decode($bps_json, true);

$tabel_bps = [];

if ($bps_data && isset($bps_data['status']) && $bps_data['status'] == 'OK') {

    $wilayah = [];
    foreach ($bps_data['vervar'] as $v) {
        $wilayah[(string)$v['val']] = $v['label'];
    }

    $fasilitas = [];
    foreach ($bps_data['turvar'] as $t) {
        $fasilitas[(string)$t['val']] = $t['label'];
    }

    $tahun = $bps_data['tahun'][0]['label'];

    foreach ($bps_data['datacontent'] as $key => $val) {

        $kode_wilayah = substr($key, 0, 2);
        $kode_fasilitas = substr($key, 5, 3);

        if (isset($wilayah[$kode_wilayah]) && isset($fasilitas[$kode_fasilitas])) {
            $tabel_bps[] = [
                'wilayah' => $wilayah[$kode_wilayah],
                'fasilitas' => $fasilitas[$kode_fasilitas],
                'tahun' => $tahun,
                'nilai' => $val
            ];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pasien - Rekam Medis</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="../pasien.js"></script>
    <style>
        /* Custom styling agar badge seragam lebarnya */
        .status-badge {
            width: 120px;
            padding: 8px;
            font-size: 0.85rem;
            border-radius: 50px;
        }

        /* ===== SCROLL TABEL BPS ===== */
        .bps-scroll {
            max-height: 300px;      /* tinggi container */
            overflow-y: auto;       /* aktifkan scroll */
            border: 1px solid #dee1e6;
            border-radius: 10px;
        }

        /* HEADER STICKY */
        .bps-scroll thead th {
            position: sticky;
            top: 0;
            background-color: #8dc2ef;
            z-index: 2;
        }
    </style>
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-primary p-3 shadow-sm">
    <div class="container">
        <span class="navbar-brand fw-bold"><i class="bi bi-hospital me-2"></i>Halo, <?= htmlspecialchars($user['nama']); ?></span>
        <a href="logout.php" class="btn btn-outline-light btn-sm px-4">Logout</a>
    </div>
</nav>

<div class="container mt-5 mb-5">
    <div class="card p-4 shadow-sm border-0 rounded-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="text-primary fw-bold mb-0">Input Data Pasien</h4>
            <a href="halaman_dokter.php" class="btn btn-primary shadow-sm rounded-pill">
                <i class="bi bi-person-badge me-1"></i> Halaman Dokter
            </a>
        </div>

        <form method="POST" class="row g-3 bg-white p-3 rounded-3 border">
            <div class="col-md-3">
                <label class="form-label small fw-bold">Nama Pasien</label>
                <input type="text" name="nama" placeholder="Nama Lengkap" class="form-control" required>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-bold">Umur</label>
                <input type="number" name="umur" placeholder="Umur" class="form-control" required>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-bold">Provinsi</label>
                <select id="provinsi" class="form-control">
                    <option value="">-- Pilih Provinsi --</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-bold">Kabupaten/Kota</label>
                <select id="kabupatenkota" class="form-control">
                    <option value="">-- Pilih Kabupaten --</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-bold">Kecamatan</label>
                <select id="kecamatan" class="form-control">
                    <option value="">-- Pilih Kecamatan --</option>
                </select>
            </div>
            <input type="hidden" name="alamat_lengkap" id="alamat_lengkap">
            <div class="col-md-3">
                <label class="form-label small fw-bold">Keluhan</label>
                <input type="text" name="keluhan" placeholder="Keluhan Pasien" class="form-control" required>
            </div>
            <div class="col-md-1 d-grid align-items-end">
                <button type="submit" name="tambah" class="btn btn-primary shadow-sm mt-md-0 mt-2">
                    <i class="bi bi-plus-lg"></i>
                </button>
            </div>
        </form>

        <hr class="my-4">

        <div class="table-responsive">
            <table class="table table-hover align-middle text-center border-top">
                <thead class="table-primary">
                    <tr>
                        <th class="py-3">No</th>
                        <th class="py-3">ID Pasien</th>
                        <th class="py-3">Nama</th>
                        <th class="py-3">Keluhan</th>
                        <th class="py-3">Status</th>
                        <th class="py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                <?php
                $sql = "SELECT * FROM pasien ORDER BY id_pasien DESC";
                if(isset($_GET['filter']) && $_GET['filter'] != ''){
                    $filter = mysqli_real_escape_string($conn, $_GET['filter']);
                    $sql = "SELECT * FROM pasien WHERE status='$filter' ORDER BY id_pasien DESC";
                }
                
                $res = mysqli_query($conn, $sql);
                $no = 1;

                if($res && mysqli_num_rows($res) > 0) {
                    while($d = mysqli_fetch_assoc($res)){
                ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><span class="badge bg-light text-primary border border-primary px-3"><?= $d['id_pasien']; ?></span></td>
                        <td class="fw-bold text-dark"><?= htmlspecialchars($d['nama']); ?></td>
                        <td class="text-muted italic"><?= htmlspecialchars($d['keluhan']); ?></td>
                        <td>
                            <?php if($d['status'] == 'menunggu'): ?>
                                <span class="badge bg-secondary status-badge shadow-sm">Menunggu</span>
                            <?php elseif($d['status'] == 'sudah diperiksa'): ?>
                                <span class="badge bg-info text-white status-badge shadow-sm border border-info">Sudah Diperiksa</span>
                            <?php else: ?>
                                <span class="badge bg-primary status-badge shadow-sm">Selesai</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="btn-group shadow-sm">
                                <a href="edit.php?id=<?= $d['id_pasien']; ?>" class="btn btn-sm btn-outline-warning border-end-0"><i class="bi bi-pencil"></i></a>
                                <a href="hapus.php?id=<?= $d['id_pasien']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Yakin mau hapus?')"><i class="bi bi-trash"></i></a>
                            </div>
                        </td>
                    </tr>
                <?php 
                    } 
                } else {
                    echo "<tr><td colspan='6' class='text-muted py-5'>Belum ada data pasien atau tabel belum siap.</td></tr>";
                }
                ?>
                </tbody>
            </table>
        </div>
        
    <!-- ===== TABEL BPS (SCROLL) ===== -->
    <h5 class="text-success fw-bold mb-3">Data Fasilitas Kesehatan</h5>

    <div class="bps-scroll">
        <table class="table table-striped table-hover border mb-0">
            <thead class="table-success">
                <tr>
                    <th>No</th>
                    <th>Kab/Kota</th>
                    <th>Fasilitas</th>
                    <th>Tahun</th>
                    <th>Jumlah</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($tabel_bps)): ?>
                    <?php $no_bps = 1; foreach ($tabel_bps as $row): ?>
                        <tr>
                            <td><?= $no_bps++; ?></td>
                            <td><?= htmlspecialchars($row['wilayah']); ?></td>
                            <td><?= htmlspecialchars($row['fasilitas']); ?></td>
                            <td><?= $row['tahun']; ?></td>
                            <td><?= number_format($row['nilai'], 0, ',', '.'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center text-muted">Gagal memuat data</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>

        </div>
    </div>
</div>
</body>
</html>