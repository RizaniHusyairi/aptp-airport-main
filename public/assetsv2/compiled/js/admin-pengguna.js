$(document).ready(function() {
    $('#table2').DataTable({
        responsive: true,
        autoWidth: false,
        language: {
            decimal: "",
            emptyTable: "Tidak ada data pengguna",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
            infoEmpty: "Menampilkan 0 sampai 0 dari 0 entri",
            infoFiltered: "(disaring dari _MAX_ total entri)",
            infoPostFix: "",
            thousands: ",",
            lengthMenu: "Tampilkan _MENU_ entri",
            loadingRecords: "Memuat...",
            processing: "Memproses...",
            search: "Cari:",
            zeroRecords: "Tidak ada data yang cocok ditemukan",
            paginate: {
                first: "Pertama",
                last: "Terakhir",
                next: "Selanjutnya",
                previous: "Sebelumnya"
            },
            aria: {
                sortAscending: ": aktifkan untuk mengurutkan kolom secara naik",
                sortDescending: ": aktifkan untuk mengurutkan kolom secara turun"
            }
        },
        columnDefs: [
            { targets: 0, responsivePriority: 1 }, // Kolom '#'
            { targets: 7, orderable: false, responsivePriority: 1 }, // Kolom 'Aksi'
            { targets: 1, responsivePriority: 2 } // Kolom 'Nama'
        ],
        order: [[5, 'desc']], // Urutkan berdasarkan 'Dibuat Pada'
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50]
    });
});