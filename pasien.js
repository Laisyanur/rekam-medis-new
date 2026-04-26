$(document).ready(function () {

    const BASE = 'https://emsifa.github.io/api-wilayah-indonesia/api';

    // Load Provinsi
    $.ajax({
        url: `${BASE}/provinces.json`,
        method: 'GET',
        success: function (data) {
            let options = '<option value="">-- Pilih Provinsi --</option>';
            data.forEach(function (prov) {
                options += `<option value="${prov.id}">${prov.name}</option>`;
            });
            $('#provinsi').html(options);
        },
        error: function () {
            $('#provinsi').html('<option value="">Gagal memuat provinsi</option>');
        }
    });

    // Provinsi -> Kabupaten
    $(document).on('change', '#provinsi', function () {
        const id = $(this).val();
        $('#kabupatenkota').html('<option value="">-- Pilih Kabupaten/Kota --</option>');
        $('#kecamatan').html('<option value="">-- Pilih Kecamatan --</option>');
        if (!id) return;

        $('#kabupatenkota').html('<option value="">Memuat...</option>').prop('disabled', true);

        $.ajax({
            url: `${BASE}/regencies/${id}.json`,
            method: 'GET',
            success: function (data) {
                let options = '<option value="">-- Pilih Kabupaten/Kota --</option>';
                data.forEach(function (kab) {
                    options += `<option value="${kab.id}">${kab.name}</option>`;
                });
                $('#kabupatenkota').html(options).prop('disabled', false);
            }
        });
    });

    // Kabupaten -> Kecamatan
    $(document).on('change', '#kabupatenkota', function () {
        const id = $(this).val();
        $('#kecamatan').html('<option value="">-- Pilih Kecamatan --</option>');
        if (!id) return;

        $('#kecamatan').html('<option value="">Memuat...</option>').prop('disabled', true);

        $.ajax({
            url: `${BASE}/districts/${id}.json`,
            method: 'GET',
            success: function (data) {
                let options = '<option value="">-- Pilih Kecamatan --</option>';
                data.forEach(function (kec) {
                    options += `<option value="${kec.id}">${kec.name}</option>`;
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