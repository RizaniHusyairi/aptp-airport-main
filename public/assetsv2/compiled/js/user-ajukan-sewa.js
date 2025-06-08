$(document).ready(function() {
    // Inisialisasi DataTable untuk pengajuan sewa
    $('#table-sewa').DataTable({
        responsive: true, // Aktifkan responsivitas
        autoWidth: false, // Nonaktifkan lebar otomatis untuk responsivitas
        language: {
            // Sesuaikan bahasa untuk pengalaman pengguna yang lebih baik
            "decimal": "",
            "emptyTable": "Tidak ada data pengajuan sewa",
            "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
            "infoEmpty": "Menampilkan 0 sampai 0 dari 0 entri",
            "infoFiltered": "(disaring dari _MAX_ total entri)",
            "infoPostFix": "",
            "thousands": ",",
            "lengthMenu": "Tampilkan _MENU_ entri",
            "loadingRecords": "Memuat...",
            "processing": "Memproses...",
            "search": "Cari:",
            "zeroRecords": "Tidak ada data yang cocok ditemukan",
            "paginate": {
                "first": "Pertama",
                "last": "Terakhir",
                "next": "Selanjutnya",
                "previous": "Sebelumnya"
            },
            "aria": {
                "sortAscending": ": aktifkan untuk mengurutkan kolom secara naik",
                "sortDescending": ": aktifkan untuk mengurutkan kolom secara turun"
            }
        },
        columnDefs: [
            {
                // Kolom "Aksi" tidak dapat diurutkan
                targets: 3,
                orderable: false
            },
            {
                // Prioritas responsif untuk tampilan mobile
                responsivePriority: 1,
                targets: [0, 3] // Prioritaskan kolom "Nama File Pengajuan" dan "Aksi"
            },
            {
                responsivePriority: 2,
                targets: 2 // Kolom "Status" memiliki prioritas kedua
            }
        ],
        order: [[1, 'desc']], // Urutkan berdasarkan kolom "Dibuat" (tanggal) secara menurun
        pageLength: 10, // Jumlah baris default per halaman
        lengthMenu: [5, 10, 25, 50], // Pilihan jumlah baris per halaman
    });
});