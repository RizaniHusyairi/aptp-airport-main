{{-- Inisialisasi TinyMCE untuk kolom jawaban, mengikuti pola Pengaturan Profil Bandara. --}}
<script src="{{ asset('assetsv2/extensions/tinymce/tinymce.min.js') }}"></script>
<script>
    document.addEventListener("DOMContentLoaded", () => {
        tinymce.init({
            selector: '.tinymce-editor',
            height: 320,
            menubar: false,
            plugins: [
                'advlist autolink lists link charmap preview anchor',
                'searchreplace visualblocks code fullscreen',
                'insertdatetime table paste code help wordcount'
            ],
            toolbar: 'undo redo | formatselect | bold italic | ' +
                'bullist numlist | link | removeformat | code | help',
            content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }',
            setup: function (editor) {
                // Penting: tanpa ini isi editor tidak ikut ter-submit ke server.
                editor.on('change', function () {
                    editor.save();
                });
            }
        });
    });
</script>
