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
        columnDefs: [
            { targets: 0, responsivePriority: 1 }, // Tanggal
            { targets: 4, orderable: false, responsivePriority: 2 }, // Aksi
            { targets: 2, responsivePriority: 3 }, // Jumlah
            { targets: 1, responsivePriority: 4 }, // Jenis
            { targets: 3, responsivePriority: 5 } // Catatan
        ],
        order: [[0, 'desc']], // Urutkan berdasarkan Tanggal
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50]
    });

    // Isi dropdown tahun
    const years = [...new Set(table.column(0).data().toArray().map(date => {
        return date ? date.split('/')[2] : null;
    }).filter(Boolean))].sort((a, b) => b - a);
    years.forEach(year => {
        $('#filter-tahun').append(`<option value="${year}">${year}</option>`);
    });

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
        
        const year = tanggal ? tanggal.split('/')[2] : '';
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

    // Data dummy untuk pengeluaran (ganti dengan AJAX di produksi)
    const dataPengeluaran = {
        2: [
            { nomor: 1, deskripsi: "Pembelian peralatan kantor", jumlah: "Rp 1.500.000" },
            { nomor: 2, deskripsi: "Biaya listrik", jumlah: "Rp 1.000.000" },
            { nomor: 3, deskripsi: "Biaya internet", jumlah: "Rp 1.000.000" }
        ],
        4: [
            { nomor: 1, deskripsi: "Perbaikan AC", jumlah: "Rp 2.000.000" },
            { nomor: 2, deskripsi: "Pembersihan gedung", jumlah: "Rp 1.200.000" },
            { nomor: 3, deskripsi: "Pembelian lampu", jumlah: "Rp 1.000.000" }
        ]
    };

    // Handle klik tombol Lihat Pengeluaran
    $(document).on('click', '.btn-lihat-pengeluaran', function() {
        const transaksiId = $(this).closest('tr').data('id');
        const pengeluaran = dataPengeluaran[transaksiId] || [];

        // Kosongkan tbody
        $('#table-detail-pengeluaran tbody').empty();

        // Isi tabel detail pengeluaran
        if (pengeluaran.length > 0) {
            pengeluaran.forEach(item => {
                $('#table-detail-pengeluaran tbody').append(`
                    <tr>
                        <td>${item.nomor}</td>
                        <td>${item.deskripsi}</td>
                        <td>${item.jumlah}</td>
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

        // Tampilkan modal
        $('#modal-pengeluaran').modal('show');

        // Placeholder untuk AJAX
        /*
        $.ajax({
            url: `/api/laporan-keuangan/${transaksiId}/pengeluaran`,
            method: 'GET',
            success: function(data) {
                $('#table-detail-pengeluaran tbody').empty();
                if (data.length > 0) {
                    data.forEach((item, index) => {
                        $('#table-detail-pengeluaran tbody').append(`
                            <tr>
                                <td>${index + 1}</td>
                                <td>${item.deskripsi}</td>
                                <td>${item.jumlah}</td>
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
            },
            error: function() {
                alert('Gagal memuat data pengeluaran.');
            }
        });
        */
    });
});