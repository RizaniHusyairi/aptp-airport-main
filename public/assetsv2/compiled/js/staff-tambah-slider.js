$(document).ready(function() {
    // Pratinjau Gambar
    $('#documents').on('change', function() {
        const file = this.files[0];
        const $errorDiv = $('#error-gambar');
        const $previewContainer = $('.preview-container');
        const $previewImage = $('#image-preview');

        // Reset error dan pratinjau
        $errorDiv.text('').removeClass('d-block');
        $previewContainer.hide();

        if (file) {
            // Validasi format
            if (!file.type.match(/image\/(jpeg|png)/)) {
                $errorDiv.text('Hanya file JPG atau PNG yang diizinkan.').addClass('d-block');
                $(this).val('');
                return;
            }

            // Validasi ukuran (maksimum 2MB)
            if (file.size > 2000000) {
                $errorDiv.text('Ukuran file terlalu besar (maksimum 2MB).').addClass('d-block');
                $(this).val('');
                return;
            }

            // Tampilkan pratinjau
            const reader = new FileReader();
            reader.onload = function(e) {
                $previewImage.attr('src', e.target.result);
                $previewContainer.show();
            };
            reader.readAsDataURL(file);
        }
    });

});
