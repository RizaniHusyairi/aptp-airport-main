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

    var initialContent = $('#content').val();
    if (initialContent) {
        quill.root.innerHTML = initialContent;
    }

    // Update hidden input dengan konten Quill
    quill.on('text-change', function() {
        $('#content').val(quill.root.innerHTML);
    });

    // Pratinjau gambar
    $('#image').on('change', function(e) {
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
            const file = $('#image')[0].files[0];
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
            const file = $('#image')[0].files[0];
            if (!file) return true;
            const maxSizeBytes = maxSizeMB * 1024 * 1024;
            return file.size <= maxSizeBytes;
        },
        messages: {
            en: 'Ukuran file tidak boleh melebihi %s MB.'
        }
    });

    
});