<?php
include 'koneksi.php';

// ambil id dari URL
if(isset($_GET['id'])){
    $id = mysqli_real_escape_string($conn, $_GET['id']);
} else {
    echo "ID tidak ditemukan";
    exit;
}

// ambil data pasien
$query = mysqli_query($conn, "SELECT * FROM pasien WHERE id_pasien='$id'");
$data = mysqli_fetch_assoc($query);

if(!$data){
    echo "Data tidak ditemukan";
    exit;
}

// proses simpan
if(isset($_POST['simpan'])){
    $diagnosa = mysqli_real_escape_string($conn, $_POST['diagnosa']);

    $update = mysqli_query($conn, "UPDATE pasien 
        SET diagnosa='$diagnosa', status='selesai'
        WHERE id_pasien='$id'");

    if($update){
        echo "<script>
            alert('Berhasil diperiksa');
            window.location='halaman_dokter.php';
        </script>";
        exit;
    } else {
        echo "Gagal update: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Periksa Pasien</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<nav class="navbar navbar-dark bg-primary shadow">
    <div class="container">
        <span class="navbar-brand">💊 Pemeriksaan Pasien</span>
    </div>
</nav>

<div class="container mt-5">

    <div class="row justify-content-center">
        <div class="col-md-6">

            <div class="card shadow">
                <div class="card-body">

                    <h4 class="text-primary mb-4">Form Pemeriksaan</h4>

                    <!-- Nama -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Pasien</label>
                        <input type="text" class="form-control" 
                               value="<?= htmlspecialchars($data['nama']); ?>" readonly>
                    </div>

                    <!-- Keluhan -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Keluhan</label>
                        <input type="text" class="form-control text-danger" 
                               value="<?= htmlspecialchars($data['keluhan']); ?>" readonly>
                    </div>

                    <!-- Form Diagnosa -->
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Diagnosa</label>
                            <textarea name="diagnosa" 
                                      class="form-control" 
                                      rows="4" 
                                      placeholder="Tulis diagnosa pasien..." 
                                      required></textarea>
                        </div>

                        <!-- Tombol -->
                        <div class="d-flex justify-content-between">
                            <a href="halaman_dokter.php" class="btn btn-secondary">
                                ← Kembali
                            </a>

                            <button type="submit" name="simpan" class="btn btn-primary">
                                Simpan Diagnosa
                            </button>
                        </div>
                    </form>

                </div>
            </div>

        </div>
    </div>

</div>

</body>
</html>
