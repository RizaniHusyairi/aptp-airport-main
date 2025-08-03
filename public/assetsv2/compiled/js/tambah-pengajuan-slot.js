$(document).ready(function() {
    // Validasi formulir menggunakan Bootstrap 5
    $('#form-pengajuan-slot').on('submit', function(event) {
        event.preventDefault();
        var form = this;

        // Cek validitas formulir
        if (form.checkValidity() === false) {
            event.stopPropagation();
            $(form).addClass('was-validated');
            return;
        }

        // Validasi khusus untuk input file
        var dokumenInput = $('#dokumen')[0];
        var maxFiles = 10;
        if (dokumenInput.files.length > maxFiles) {
            alert('Maksimal ' + maxFiles + ' file yang dapat diunggah.');
            return;
        }

        // Validasi jadwal keberangkatan dan kedatangan
        var jadwalKeberangkatan = new Date($('#jadwalKeberangkatan').val());
        var jadwalKedatangan = new Date($('#jadwalKedatangan').val());
        if (jadwalKedatangan <= jadwalKeberangkatan) {
            alert('Jadwal kedatangan harus setelah jadwal keberangkatan.');
            $('#jadwalKedatangan').addClass('is-invalid');
            return;
        }

        // Simulasi pengiriman data (ganti dengan AJAX jika diperlukan)
        alert('Pengajuan slot charter berhasil dikirim!');
        form.reset();
        $(form).removeClass('was-validated');
    });

    // Validasi file saat input berubah
    $('#dokumen').on('change', function() {
        var validExtensions = ['pdf', 'doc', 'docx'];
        var files = this.files;
        var valid = true;

        for (var i = 0; i < files.length; i++) {
            var extension = files[i].name.split('.').pop().toLowerCase();
            if (!validExtensions.includes(extension)) {
                valid = false;
                break;
            }
        }

        if (!valid) {
            this.setCustomValidity('Hanya file PDF, DOC, atau DOCX yang diperbolehkan.');
            $(this).addClass('is-invalid');
        } else {
            this.setCustomValidity('');
            $(this).removeClass('is-invalid');
        }
    });

    // Hapus kelas is-invalid saat input jadwal diubah
    $('#jadwalKedatangan, #jadwalKeberangkatan').on('change', function() {
        $(this).removeClass('is-invalid');
    });
});