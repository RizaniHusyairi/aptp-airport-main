$(document).ready(function() {
    const table = $('#table-laporan-keuangan').DataTable({
        "language": { "url": "https://cdn.datatables.net/plug-ins/1.13.1/i18n/id.json" },
        "initComplete": function () {
            // Mengisi filter tahun secara dinamis
            const yearColumn = this.api().column(0); // Kolom tanggal
            const yearSelect = $('#filter-tahun');
            const years = new Set();
            yearColumn.data().each(function (value) {
                const year = value.split(' ')[1]; // Ambil tahun dari format "M Y"
                if(year) years.add(year);
            });
            Array.from(years).sort().reverse().forEach(year => {
                yearSelect.append(new Option(year, year));
            });
        }
    });

    // Event listeners untuk filter
    $('#filter-tahun, #filter-arus-dana, #filter-sumber-dana').on('change', function() {
        table.draw();
    });

    // Logika filter kustom
    $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
        const selectedTahun = $('#filter-tahun').val();
        const selectedArus = $('#filter-arus-dana').val();
        const selectedSumber = $('#filter-sumber-dana').val();

        const tanggal = data[0] || '';
        const jenis = data[1] || '';
        const sumber = data[2] || '';
        
        const tahun = tanggal.split(' ')[1];

        // Cek filter
        if ((selectedTahun && tahun !== selectedTahun) ||
            (selectedArus && !jenis.includes(selectedArus)) ||
            (selectedSumber && sumber !== selectedSumber && !(selectedSumber === '-' && sumber === '-'))) {
            return false;
        }
        return true;
    });

    // Menampilkan detail pengeluaran di modal
    $('#table-laporan-keuangan tbody').on('click', '.btn-lihat-pengeluaran', function() {
        const row = $(this).closest('tr');
        const modalTableBody = $('#table-detail-pengeluaran tbody');
        modalTableBody.empty();

        // === PERUBAHAN DI SINI: Ambil data Periode dan Total Anggaran ===
        const periode = row.find('td:eq(0)').text(); // Kolom pertama (Tanggal)
        const totalAnggaran = row.find('td:eq(3)').text(); // Kolom keempat (Jumlah)
        // Tampilkan data ke modal
        $('#modal-periode').text(periode);
        $('#modal-total-anggaran').text(totalAnggaran);
        row.find('.expense').each(function() {
            const nomor = $(this).find('.nomor').text();
            const deskripsi = $(this).find('.deskripsi').text();
            const jumlah = $(this).find('.jumlah').text();
            
            modalTableBody.append(`<tr><td>${nomor}</td><td>${deskripsi}</td><td>${jumlah}</td></tr>`);
        });

        $('#modal-pengeluaran').modal('show');
    });

    // Logika hapus data
    $('#table-laporan-keuangan tbody').on('click', '.btn-hapus', function() {
        const id = $(this).data('id');
        const row = $(this).closest('tr');

        Swal.fire({
            title: 'Anda yakin?',
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `${id}`,
                    type: 'POST',
                    data: {
                        '_method': 'DELETE',
                        '_token': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            table.row(row).remove().draw();
                            Swal.fire('Terhapus!', response.message, 'success');
                        }
                    },
                    error: function() {
                        Swal.fire('Gagal!', 'Terjadi kesalahan saat menghapus data.', 'error');
                    }
                });
            }
        });
    });

    // Inisialisasi tooltip
    $('[data-bs-toggle="tooltip"]').tooltip();
});



