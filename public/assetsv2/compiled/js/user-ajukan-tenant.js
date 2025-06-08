$(document).ready(function() {
    // Initialize DataTable for tenant applications
    $('#table-tenant').DataTable({
        responsive: true, // Enable responsive behavior
        autoWidth: false, // Disable auto width to ensure responsiveness
        language: {
            // Customize language for better user experience
            "decimal": "",
            "emptyTable": "Tidak ada data pengajuan tenant",
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
                // Make the "Aksi" column non-sortable
                targets: 3,
                orderable: false
            },
            {
                // Ensure responsive priority for better mobile view
                responsivePriority: 1,
                targets: [0, 3] // Prioritize "Nama File Pengajuan" and "Aksi" columns
            },
            {
                responsivePriority: 2,
                targets: 2 // "Status" column has secondary priority
            }
        ],
        order: [[1, 'desc']], // Default sort by "Dibuat" column (date) in descending order
        pageLength: 10, // Default number of rows per page
        lengthMenu: [5, 10, 25, 50], // Options for rows per page
    });
});