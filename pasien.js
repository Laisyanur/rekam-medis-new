$(document).ready(function () {
    // 1. MUAT PROVINSI (BPS)
    function loadProvinsi() {
        $.ajax({
            url: 'api/api.php?action=provinsi',
            method: 'GET',
            success: function (res) {
                if (res.status === 'OK') {
                    let options = '<option value="">-- Pilih Provinsi --</option>';
                    res.data.forEach(function (prov) {
                        options += `<option value="${prov.domain_id}">${prov.domain_name}</option>`;
                    });
                    $('#provinsi').html(options);
                }
            }
        });
    }

    loadProvinsi();

    // 2. PROVINSI -> KABUPATEN (BPS)
    $(document).on('change', '#provinsi', function () {
        const kode_prov = $(this).val();
        if (!kode_prov) {
            $('#kabupatenkota').html('<option value="">-- Pilih Kabupaten/Kota --</option>').prop('disabled', true);
            $('#kecamatan').html('<option value="">-- Pilih Kecamatan --</option>').prop('disabled', true);
            return;
        }

        $('#kabupatenkota').html('<option value="">Memuat kabupaten...</option>').prop('disabled', true);

        $.ajax({
            url: `api/api.php?action=kabupatenkota&kode=${kode_prov}`,
            method: 'GET',
            success: function (res) {
                let options = '<option value="">-- Pilih Kabupaten/Kota --</option>';
                if (res.status === 'OK' && res.data.length > 0) {
                    res.data.forEach(function (kab) {
                        options += `<option value="${kab.domain_id}">${kab.domain_name}</option>`;
                    });
                    $('#kabupatenkota').html(options).prop('disabled', false);
                }
            }
        });
    });

    // 3. KABUPATEN -> KECAMATAN (Menggunakan API Wilayah Luar agar pasti ada datanya)
    $(document).on('change', '#kabupatenkota', function () {
        const kode_kab = $(this).val(); // Contoh: 3578 (Surabaya)
        
        if (!kode_kab) {
            $('#kecamatan').html('<option value="">-- Pilih Kecamatan --</option>').prop('disabled', true);
            return;
        }

        $('#kecamatan').html('<option value="">Memuat kecamatan...</option>').prop('disabled', true);

        // Kita gunakan API emsifa karena API BPS Domain tidak menyediakan level kecamatan
        $.ajax({
            url: `https://www.emsifa.com/api-wilayah-indonesia/api/districts/${kode_kab}.json`,
            method: 'GET',
            success: function (data) {
                let options = '<option value="">-- Pilih Kecamatan --</option>';
                if (data && data.length > 0) {
                    data.forEach(function (kec) {
                        options += `<option value="${kec.id}">${kec.name}</option>`;
                    });
                    $('#kecamatan').html(options).prop('disabled', false);
                } else {
                    $('#kecamatan').html('<option value="">Kecamatan tidak ditemukan</option>');
                }
            },
            error: function() {
                $('#kecamatan').html('<option value="">Gagal memuat data</option>');
            }
        });
    });
});

$(document).on('change', '#provinsi, #kabupatenkota, #kecamatan', function() {
    // Ambil teks yang tampil (bukan value angka/ID-nya)
    let prov = $('#provinsi option:selected').text();
    let kab  = $('#kabupatenkota option:selected').text();
    let kec  = $('#kecamatan option:selected').text();
    
    // Jangan gabungkan kalau masih tulisan "-- Pilih --"
    if($('#provinsi').val() !== "" && $('#kabupatenkota').val() !== "" && $('#kecamatan').val() !== "") {
        let gabung = kec + ", " + kab + ", " + prov;
        $('#alamat_lengkap').val(gabung);
    }
});