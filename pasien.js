$(document).ready(function () {

    // Load Provinsi dari API publik (tidak perlu backend)
    $.ajax({
        url: 'https://wilayah.id/api/provinces.json',
        method: 'GET',
        success: function (res) {
            let options = '<option value="">-- Pilih Provinsi --</option>';
            res.data.forEach(function (prov) {
                options += `<option value="${prov.code}">${prov.name}</option>`;
            });
            $('#provinsi').html(options);
        },
        error: function() {
            $('#provinsi').html('<option value="">Gagal memuat provinsi</option>');
        }
    });

    // Provinsi -> Kabupaten
    $(document).on('change', '#provinsi', function () {
        const code = $(this).val();
        $('#kabupatenkota').html('<option value="">-- Pilih Kabupaten/Kota --</option>');
        $('#kecamatan').html('<option value="">-- Pilih Kecamatan --</option>');
        if (!code) return;

        $('#kabupatenkota').html('<option value="">Memuat...</option>').prop('disabled', true);

        $.ajax({
            url: `https://wilayah.id/api/regencies/${code}.json`,
            method: 'GET',
            success: function (res) {
                let options = '<option value="">-- Pilih Kabupaten/Kota --</option>';
                res.data.forEach(function (kab) {
                    options += `<option value="${kab.code}">${kab.name}</option>`;
                });
                $('#kabupatenkota').html(options).prop('disabled', false);
            }
        });
    });

    // Kabupaten -> Kecamatan
    $(document).on('change', '#kabupatenkota', function () {
        const code = $(this).val();
        $('#kecamatan').html('<option value="">-- Pilih Kecamatan --</option>');
        if (!code) return;

        $('#kecamatan').html('<option value="">Memuat...</option>').prop('disabled', true);

        $.ajax({
            url: `https://wilayah.id/api/districts/${code}.json`,
            method: 'GET',
            success: function (res) {
                let options = '<option value="">-- Pilih Kecamatan --</option>';
                res.data.forEach(function (kec) {
                    options += `<option value="${kec.code}">${kec.name}</option>`;
                });
                $('#kecamatan').html(options).prop('disabled', false);
            }
        });
    });

    // Gabungkan jadi alamat_lengkap
    $(document).on('change', '#provinsi, #kabupatenkota, #kecamatan', function () {
        const prov = $('#provinsi option:selected').text();
        const kab  = $('#kabupatenkota option:selected').text();
        const kec  = $('#kecamatan option:selected').text();

        if ($('#provinsi').val() && $('#kabupatenkota').val() && $('#kecamatan').val()) {
            $('#alamat_lengkap').val(`${kec}, ${kab}, ${prov}`);
        }
    });
});