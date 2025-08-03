document.addEventListener('DOMContentLoaded', function () {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('.btn-tooltip[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    }, false);
    
$(document).ready(function() {


    // Inisialisasi DataTables
    $('#table-berita').DataTable({
        responsive: true,
        autoWidth: false,
        language: {
            decimal: "",
            emptyTable: "Tidak ada data berita",
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
            { targets: 0, responsivePriority: 1 }, // Judul Berita
            { targets: 4, orderable: false, responsivePriority: 1 }, // Aksi
            { targets: 1, responsivePriority: 2 }, // Headline
            { targets: 2, responsivePriority: 3 }, // Publikasi
            { targets: 3, responsivePriority: 4 } // Dibuat
        ],
        order: [[3, 'desc']], // Urutkan berdasarkan Dibuat
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50]
    });

    // Handle switch checkbox untuk Headline
    $(document).on('change', '.headline-switch', function() {
        const isChecked = $(this).is(':checked');
        const row = $(this).closest('tr');
        const title = row.find('td:first').text();
        console.log(`Berita "${title}" headline status: ${isChecked ? 'Aktif' : 'Nonaktif'}`);
        // Tambahkan AJAX ke backend di sini
    });

    // Handle switch checkbox untuk Publikasi
    $(document).on('change', '.publish-switch', function() {
        const isChecked = $(this).is(':checked');
        const row = $(this).closest('tr');
        const title = row.find('td:first').text();
        console.log(`Berita "${title}" publikasi status: ${isChecked ? 'Dipublikasi' : 'Draft'}`);
        // Tambahkan AJAX ke backend di sini
    });
});