$(document).ready(function() {
    // Inisialisasi Tooltip
    $('[data-bs-toggle="tooltip"]').tooltip();

    // Format untuk child row
    function formatChildRow(message) {
        return '<div class="p-3">' +
               '<p>' + message + '</p>' +
               '</div>';
    }

    // Inisialisasi DataTables
    const table = $('#table-pengaduan').DataTable({
        responsive: true,
        language: {
            // Sesuaikan bahasa untuk pengalaman pengguna yang lebih baik
            "decimal": "",
            "emptyTable": "Tidak ada data Pengaduan",
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
            { orderable: false, targets: [4, 5] } // Nonaktifkan pengurutan pada kolom Aksi dan Pesan
        ]
    });

    // Loader untuk tabel
    const showLoader = () => {
        $('#table-pengaduan').addClass('d-none');
        $('#table-pengaduan').before('<div class="text-center" id="table-loader"><div class="spinner-border" role="status"><span class="visually-hidden">Memuat...</span></div></div>');
    };
    const hideLoader = () => {
        $('#table-loader').remove();
        $('#table-pengaduan').removeClass('d-none');
    };

    // Tampilkan/sembunyikan child row untuk Pesan
    $(document).on('click', '.show-message', function() {
        const tr = $(this).closest('tr');
        const row = table.row(tr);
        const message = $(this).data('message');

        if (row.child.isShown()) {
            // Sembunyikan child row
            row.child.hide();
            $(this).find('i').removeClass('bi-chevron-up').addClass('bi-chevron-down');
        } else {
            // Tampilkan child row
            row.child(formatChildRow(message)).show();
            $(this).find('i').removeClass('bi-chevron-down').addClass('bi-chevron-up');
        }
    });

    // Ubah Status
    $(document).on('click', '.change-status', function() {
        const row = $(this).closest('tr');
        const id = row.data('id');
        const nama = row.find('td:nth-child(1)').text();
        const email = row.find('td:nth-child(2)').text();
        const telepon = row.data('telepon');
        const kategori = row.find('td:nth-child(3)').text();
        const pesan = row.find('.show-message').data('message');
        const status = row.find('td:nth-child(4) .badge').text();
        const url = row.data('url');
        // Isi data ke modal
        $('#complaint-id').val(id);
        $('#modal-nama').text(nama);
        $('#modal-email').text(email);
        $('#modal-telepon').text(telepon || '-');
        $('#modal-kategori').text(kategori);
        $('#modal-pesan').text(pesan);
        $('#status').val(status);
        $('#form-change-status').attr('action', url);

        $('#modal-change-status').modal('show');
    });

    
    // Hapus Pengaduan
    
    // Fokus Modal
    $('#modal-change-status').on('shown.bs.modal', function() {
        $('#status').focus();
    });

    
});