/* =========================
   REGISTER USER
   ========================= */
function registerUser() {
    let nama = document.getElementById("nama").value;
    let email = document.getElementById("email").value;
    let password = document.getElementById("password").value;

    if (nama === "" || email === "" || password === "") {
        alert("Semua data harus diisi");
        return;
    }

    let user = {
        nama: nama,
        email: email,
        password: password
    };

    localStorage.setItem("user", JSON.stringify(user));
    alert("Register berhasil! Silahkan login.");
    window.location = "login.php";
}

/* =========================
   LOGIN USER
   ========================= */
function loginUser() {
    let email = document.getElementById("email").value;
    let password = document.getElementById("password").value;

    // Ambil data user dari localstorage
    let dataUser = JSON.parse(localStorage.getItem("user"));

    if (dataUser == null) {
        alert("Akun belum terdaftar!");
        return;
    }

    if (email === dataUser.email && password === dataUser.password) {
        localStorage.setItem("login", "true");
        alert("Login berhasil!");
        
        // DISINI PERBAIKANNYA: diarahkan ke rekam_medis.php
        window.location = "rekam_medis.php"; 
    } else {
        alert("Email atau password salah!");
    }
}

/* =========================
   CEK LOGIN (Taruh di halaman rekam_medis.php)
   ========================= */
function cekLogin() {
    let status = localStorage.getItem("login");
    if (status !== "true") {
        window.location = "login.php";
    }
}

/* =========================
   LOGOUT
   ========================= */
function logout() {
    localStorage.removeItem("login");
    window.location = "login.php";
}

// ... Fungsi tambahRekamMedis dan tampilRekamMedis tetap sama ...

/* =========================
   HAPUS DATA (Perbaikan Syntax)
   ========================= */
function hapusData(index) {
    let data = JSON.parse(localStorage.getItem("rekamMedis"));
    data.splice(index, 1);
    localStorage.setItem("rekamMedis", JSON.stringify(data));
    tampilRekamMedis();
} // Koma di sini sudah dihapus