$(document).ready(function() {
    // Inisialisasi Tooltip
    $('[data-bs-toggle="tooltip"]').tooltip();

    // Inisialisasi DataTables
    const table = $('#table-slider').DataTable({
        responsive: true,
        autoWidth: false,
        language: {
            decimal: "",
            emptyTable: "Tidak ada data slider",
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
            { orderable: false, targets: [2, 4] } // Nonaktifkan pengurutan pada kolom Tampilkan dan Aksi
        ],
        order: [[1, 'desc']], // Urutkan berdasarkan Dibuat
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50]
    });

    // Loader untuk tabel
    const showLoader = () => {
        $('#table-slider').addClass('d-none');
        $('#table-slider').before('<div class="text-center" id="table-loader"><div class="spinner-border" role="status"><span class="visually-hidden">Memuat...</span></div></div>');
    };
    const hideLoader = () => {
        $('#table-loader').remove();
        $('#table-slider').removeClass('d-none');
    };

    // Toggle Tampilkan
    // $(document).on('change', '.toggle-display', function() {
    //     const row = $(this).closest('tr');
    //     const id = row.data('id');
    //     const isDisplayed = $(this).is(':checked');
    //     showLoader();
        // Simulasi AJAX (ganti dengan endpoint API sebenarnya)
        // $.ajax({
        //     url: '/api/slider/update-display',
        //     method: 'POST',
        //     data: {
        //         id: id,
        //         display: isDisplayed ? 1 : 0,
        //         _csrf: 'YOUR_CSRF_TOKEN'
        //     },
        //     success: function() {
        //         hideLoader();
        //         alert(`Slider berhasil ${isDisplayed ? 'ditampilkan' : 'disembunyikan'}!`);
        //     },
        //     error: function() {
        //         hideLoader();
        //         $(this).prop('checked', !isDisplayed); // Kembalikan status jika gagal
        //         alert('Gagal memperbarui status slider.');
        //     }
        // });
    // });

    // Hapus Slider
    $(document).on('click', '.delete-slider', function() {
        const sliderId = $(this).data('id');
        Swal.fire({
            title: 'Konfirmasi Hapus',
            text: 'Yakin ingin menghapus slide ini? ',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/dashboard/staff/slider/${sliderId}/destroy`,
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: 'Slide berhasil dihapus.',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                
                                table.row($(`tr[data-id="${sliderId}"]`)).remove().draw();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: response.message || 'Terjadi kesalahan saat menghapus data.',
                                confirmButtonText: 'OK'
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Terjadi kesalahan saat menghapus data.',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            }
        });
    });
   
    
});