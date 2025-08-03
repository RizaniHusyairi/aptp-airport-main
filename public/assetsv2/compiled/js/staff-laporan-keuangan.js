$(document).ready(function() {
    // Inisialisasi DataTables
    const table = $('#table-laporan-keuangan').DataTable({
        responsive: true,
        autoWidth: false,
        language: {
            decimal: "",
            emptyTable: "Tidak ada data transaksi",
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
        order: [[0, 'desc']],
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50]
    });

    // Isi dropdown tahun
    const years = [...new Set(table.column(0).data().toArray().map(date => {
        return date ? date.split(' ')[1] : null;
    }).filter(Boolean))].sort((a, b) => b - a);
    years.forEach(year => {
        $('#filter-tahun').append(`<option value="${year}">${year}</option>`);
    });

    // Filter tahun dan arus dana
    // $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
    //     const selectedYear = $('#filter-tahun').val();
    //     const selectedArusDana = $('#filter-arus-dana').val();
    //     const tanggal = data[0]; // Kolom Tanggal
    //     const jenis = table.row(dataIndex).node().querySelector('td[data-jenis]').getAttribute('data-jenis');

    //     const year = tanggal ? tanggal.split(' ')[1] : '';
    //     const yearMatch = !selectedYear || year === selectedYear;
    //     const arusDanaMatch = !selectedArusDana || jenis === selectedArusDana;

    //     return yearMatch && arusDanaMatch;
    // });
    // Filter tahun dan arus dana
    $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
        const selectedYear = $('#filter-tahun').val();
        const selectedArusDana = $('#filter-arus-dana').val();
        const tanggal = data[0]; // Kolom Tanggal
        let jenis = '';

        // Ambil teks dari kolom Jenis dengan penanganan error
        if (data[1] && typeof data[1] === 'string') {
            jenis = data[1];
        }

        console.log('Data[1]:', data[1], 'Jenis:', jenis); // Debugging
        
        const year = tanggal ? tanggal.split(' ')[1] : '';
        const yearMatch = !selectedYear || year === selectedYear;
        const arusDanaMatch = !selectedArusDana || jenis === selectedArusDana;
        console.log('year', yearMatch); // Debugging
        console.log('arusDana', arusDanaMatch); // Debugging

        return yearMatch && arusDanaMatch;
    });

    // Event listener untuk filter
    $('#filter-tahun, #filter-arus-dana').on('change', function() {
        table.draw();
    });

    // Handle klik tombol Lihat Pengeluaran
    $(document).on('click', '.btn-lihat-pengeluaran', function() {
        const row = $(this).closest('tr');
        const transaksiId = row.data('id');
        const expenses = row.find('.expense');

        $('#table-detail-pengeluaran tbody').empty();

        if (expenses.length > 0) {
            expenses.each(function() {
                const nomor = $(this).find('.nomor').text();
                const deskripsi = $(this).find('.deskripsi').text();
                const jumlah = $(this).find('.jumlah').text();
                $('#table-detail-pengeluaran tbody').append(`
                    <tr>
                        <td>${nomor}</td>
                        <td>${deskripsi}</td>
                        <td>${jumlah}</td>
                    </tr>
                `);
            });
        } else {
            $('#table-detail-pengeluaran tbody').append(`
                <tr>
                    <td colspan="3" class="text-center">Tidak ada data pengeluaran</td>
                </tr>
            `);
        }

        $('#modal-pengeluaran').modal('show');
    });

    // Handle klik tombol Hapus
    $(document).on('click', '.btn-hapus', function() {
        const financeId = $(this).data('id');
        Swal.fire({
            title: 'Konfirmasi Hapus',
            text: 'Yakin ingin menghapus laporan keuangan ini? Data pengeluaran terkait juga akan dihapus.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/dashboard/staff/keuangan/${financeId}`,
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: 'Laporan keuangan berhasil dihapus.',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                
                                table.row($(`tr[data-id="${financeId}"]`)).remove().draw();
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

    // Inisialisasi tooltip
    $('[data-bs-toggle="tooltip"]').tooltip();
});