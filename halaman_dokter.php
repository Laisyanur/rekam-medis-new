<?php
session_start();
include 'koneksi.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Halaman Dokter</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icon -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background: #f4f7fb;
        }

        .navbar {
            background: linear-gradient(90deg, #0d6efd, #0a58ca);
        }

        .card {
            border-radius: 15px;
        }

        .table thead {
            background-color: #0d6efd;
            color: white;
        }

        .badge-keluhan {
            background-color: #e7f1ff;
            color: #0d6efd;
            padding: 6px 10px;
            border-radius: 8px;
            font-weight: 500;
        }

        .btn-periksa {
            background-color: #0d6efd;
            color: white;
            border-radius: 8px;
        }

        .btn-periksa:hover {
            background-color: #0b5ed7;
            color: white;
        }
    </style>
</head>

<body>

<!-- 🔷 Navbar -->
<nav class="navbar navbar-dark shadow p-3">
    <div class="container d-flex justify-content-between">
        <span class="navbar-brand fw-bold">
            <i class="bi bi-hospital"></i> Sistem Rekam Medis
        </span>

        <span class="text-white">
            <i class="bi bi-person-circle"></i> Halo, Dokter
        </span>
    </div>
</nav>

<div class="container mt-5">

    <div class="card shadow border-0">
        <div class="card-body">

            <!-- Judul -->
            <h4 class="mb-4 text-primary fw-bold">
                <i class="bi bi-clipboard2-pulse"></i> Pasien Menunggu Pemeriksaan
            </h4>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    
                    <thead class="text-center">
                        <tr>
                            <th>No</th>
                            <th>Nama Pasien</th>
                            <th>Keluhan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="text-center">
                    <?php
                    $res = mysqli_query($conn, "SELECT * FROM pasien WHERE status='menunggu'");
                    $no = 1;

                    while($d = mysqli_fetch_assoc($res)){
                    ?>
                        <tr>
                            <td><?= $no++; ?></td>

                            <td class="fw-semibold">
                                <?= $d['nama']; ?>
                            </td>

                            <td>
                                <span class="badge-keluhan">
                                    <?= $d['keluhan']; ?>
                                </span>
                            </td>

                            <td>
                                <a href="halaman_periksa.php?id=<?= $d['id_pasien']; ?>" 
                                   class="btn btn-sm btn-periksa">
                                   <i class="bi bi-search"></i> Periksa
                                </a>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>

                </table>
            </div>

            <!-- Kalau kosong -->
            <?php if(mysqli_num_rows($res) == 0): ?>
                <div class="alert alert-light border text-center mt-4">
                    <i class="bi bi-info-circle text-primary"></i><br>
                    Tidak ada pasien yang menunggu pemeriksaan
                </div>
            <?php endif; ?>

        </div>
    </div>

</div>

</body>
</html>