$(document).ready(function() {
    // Inisialisasi DataTable untuk pengajuan slot charter
    $('#table-slot-charter').DataTable({
        responsive: true, // Aktifkan responsivitas
scrollX:true,        
        language: {
            // Sesuaikan bahasa untuk pengalaman pengguna dalam bahasa Indonesia
            "decimal": "",
            "emptyTable": "Tidak ada data pengajuan slot charter",
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
                targets: 5,
                orderable: false
            },
            {
                // Prioritas responsif untuk tampilan mobile
                responsivePriority: 1,
                targets: [0, 5] // Prioritaskan "Nomor Registrasi" dan "Aksi"
            },
            {
                responsivePriority: 2,
                targets: [1, 4] // Prioritaskan "Tipe Pesawat" dan "Jenis Penerbangan"
            }
        ],
        order: [[2, 'desc']], // Urutkan berdasarkan "Jadwal Keberangkatan - Kedatangan" secara menurun
        pageLength: 10, // Jumlah baris default per halaman
        lengthMenu: [5, 10, 25, 50], // Pilihan jumlah baris per halaman
    });
});