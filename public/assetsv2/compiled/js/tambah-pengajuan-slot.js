$(document).ready(function() {
    // Validasi formulir menggunakan Bootstrap 5
       const jenisPenerbanganSelect = document.getElementById('jenisPenerbangan');
    const jenisLainnyaContainer = document.getElementById('jenisLainnya');
    const jenisLainnyaInput = document.getElementById('jenislainnya');

    if (jenisPenerbanganSelect) {
        // Fungsi untuk menampilkan/menyembunyikan input "Jenis Lainnya"
        const toggleJenisLainnya = () => {
            if (jenisPenerbanganSelect.value === 'lainnya') {
                jenisLainnyaContainer.style.display = 'block';
                jenisLainnyaInput.setAttribute('required', 'required');
            } else {
                jenisLainnyaContainer.style.display = 'none';
                jenisLainnyaInput.removeAttribute('required');
                jenisLainnyaInput.value = ''; // Kosongkan nilainya saat disembunyikan
            }
        };

        // Jalankan fungsi saat halaman dimuat dan saat pilihan berubah
        toggleJenisLainnya();
        jenisPenerbanganSelect.addEventListener('change', toggleJenisLainnya);
    }

});