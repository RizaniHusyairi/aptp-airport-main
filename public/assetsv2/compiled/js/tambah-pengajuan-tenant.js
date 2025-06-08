$(document).ready(function() {
    // Validasi formulir menggunakan Bootstrap 5
    $('#form-pengajuan-tenant').on('submit', function(event) {
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

        // Simulasi pengiriman data (ganti dengan AJAX jika diperlukan)
        alert('Pengajuan tenant berhasil dikirim!');
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
});