$(document).ready(function() {
    // Inisialisasi Quill.js
    var quill = new Quill('#editor', {
        theme: 'snow',
        modules: {
            toolbar: [
                ['bold', 'italic', 'underline', 'strike'],
                ['blockquote', 'code-block'],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                [{ 'script': 'sub'}, { 'script': 'super' }],
                [{ 'indent': '-1'}, { 'indent': '+1' }],
                [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                [{ 'color': [] }, { 'background': [] }],
                [{ 'font': [] }],
                [{ 'align': [] }],
                ['clean']
            ]
        },
        placeholder: 'Tulis isi berita di sini...'
    });

    // Update hidden input dengan konten Quill
    quill.on('text-change', function() {
        $('#isi').val(quill.root.innerHTML);
    });

    // Pratinjau gambar
    $('#gambar').on('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#gambar-preview').attr('src', e.target.result).removeClass('d-none');
            };
            reader.readAsDataURL(file);
        } else {
            $('#gambar-preview').addClass('d-none').attr('src', '#');
        }
    });

    // Validasi file extension dan ukuran
    Parsley.addValidator('fileextension', {
        validateString: function(value, requirement) {
            const file = $('#gambar')[0].files[0];
            if (!file) return true;
            const extension = file.name.split('.').pop().toLowerCase();
            return requirement.split(',').includes(extension);
        },
        messages: {
            en: 'File harus berupa %s.'
        }
    });

    Parsley.addValidator('maxfilesize', {
        validateString: function(value, maxSizeMB) {
            const file = $('#gambar')[0].files[0];
            if (!file) return true;
            const maxSizeBytes = maxSizeMB * 1024 * 1024;
            return file.size <= maxSizeBytes;
        },
        messages: {
            en: 'Ukuran file tidak boleh melebihi %s MB.'
        }
    });

    // Handle submit form
    $('#form-tambah-berita').on('submit', function(e) {
        e.preventDefault();
        if ($(this).parsley().isValid()) {
            const formData = new FormData();
            formData.append('gambar', $('#gambar')[0].files[0]);
            formData.append('judul', $('#judul').val());
            formData.append('isi', $('#isi').val());

            console.log('Form data:', {
                judul: $('#judul').val(),
                isi: $('#isi').val(),
                gambar: $('#gambar')[0].files[0]?.name
            });

            // Placeholder untuk AJAX
            /*
            $.ajax({
                url: '/api/berita/tambah',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    alert('Berita berhasil disimpan!');
                    window.location.href = 'staff-berita.html';
                },
                error: function() {
                    alert('Gagal menyimpan berita.');
                }
            });
            */
        }
    });
});