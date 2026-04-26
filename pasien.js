$(document).ready(function () {

    // ====== LOAD PROVINSI ======
    function loadProvinsi() {
        $('#provinsi').html('<option value="">Memuat provinsi...</option>');
        $('#kabupatenkota').html('<option value="">-- Pilih Kabupaten/Kota --</option>').prop('disabled', true);
    
        $.ajax({
            url: '/api/api.php?action=provinsi',
            method: 'GET',
            success: function (res) {
                let options = '<option value="">-- Pilih Provinsi --</option>';
                if (res.data && res.data.length > 0) {
                    res.data.forEach(function (prov) {
                        options += `<option value="${prov.domain_id}">${prov.domain_name}</option>`;
                    });
                }
                $('#provinsi').html(options);
            },
            error: function () {
                Swal.fire('Error', 'Gagal memuat data provinsi dari BPS', 'error');
            }
        });
    }

    loadProvinsi();

    // ====== PROVINSI -> KABUPATEN ======
    $(document).on('change', '#provinsi', function () {
        const kode_prov = $(this).val();
        $('#kabupatenkota').html('<option value="">-- Pilih Kabupaten/Kota --</option>').prop('disabled', true);
        $('#alamat_lengkap').val('');

        if (!kode_prov) return;

        $('#kabupatenkota').html('<option value="">Memuat kabupaten/kota...</option>').prop('disabled', true);

        $.ajax({
            url: `/api/api.php?action=kabupatenkota&kode=${kode_prov}`,
            method: 'GET',
            success: function (res) {
                let options = '<option value="">-- Pilih Kabupaten/Kota --</option>';
                if (res.data && res.data.length > 0) {
                    res.data.forEach(function (kab) {
                        options += `<option value="${kab.domain_id}">${kab.domain_name}</option>`;
                    });
                }
                $('#kabupatenkota').html(options).prop('disabled', false);
            },
            error: function () {
                Swal.fire('Error', 'Gagal memuat data kabupaten/kota dari BPS', 'error');
            }
        });
    });


    // ====== GABUNGKAN JADI ALAMAT ======
    $(document).on('change', '#provinsi, #kabupatenkota', function () {
        const prov = $('#provinsi option:selected').text();
        const kab  = $('#kabupatenkota option:selected').text()

        if ($('#provinsi').val() && $('#kabupatenkota').val()) {
            $('#alamat_lengkap').val(`$${kab}, ${prov}`);
        }
    });

});