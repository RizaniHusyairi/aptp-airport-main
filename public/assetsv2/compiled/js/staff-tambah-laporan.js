$(document).ready(function() {
    // Format Rupiah untuk input jumlah
    $('.jumlah-rupiah').each(function() {
        if (typeof Cleave !== 'undefined') {
            new Cleave(this, {
                numeral: true,
                numeralThousandsGroupStyle: 'thousand',
                prefix: 'Rp ',
                noImmediatePrefix: true,
                rawValueTrimPrefix: true
            });
        } else {
            console.warn('Cleave.js tidak dimuat. Format Rupiah tidak diterapkan.');
            $(this).attr('type', 'number');
        }
    });

    // Tampilkan/sembunyikan tabel detail pengeluaran
    $('#flow_type').on('change', function() {
        if ($(this).val() === 'budget') {
            $('#detail-pengeluaran-container').show();
            if ($('#budget-expenses-table tbody tr').length === 0) {
                tambahBaris();
            }
        } else {
            $('#detail-pengeluaran-container').hide();
            $('#budget-expenses-table tbody').empty();
            updateTotalPengeluaran();
        }
    });

    // Tambah baris baru ke tabel detail pengeluaran
    $('#tambah-baris').on('click', function() {
        tambahBaris();
    });

    // Hapus baris dari tabel
    $(document).on('click', '.btn-hapus-baris', function() {
        $(this).closest('tr').remove();
        updateTotalPengeluaran();
    });

    // Update total pengeluaran saat input jumlah berubah
    $(document).on('input', '.jumlah-rupiah', function() {
        updateTotalPengeluaran();
    });

    // Update total pengeluaran saat jumlah anggaran berubah
    $('#amount').on('input', function() {
        updateTotalPengeluaran();
    });

    // Fungsi untuk menambah baris
    function tambahBaris() {
        const rowCount = $('#budget-expenses-table tbody tr').length + 1;
        const row = `
            <tr>
                <td><input type="text" name="budget_expenses[${rowCount}][description]" class="form-control" required></td>
                <td><input type="text" name="budget_expenses[${rowCount}][amount]" class="form-control jumlah-rupiah" required></td>
                <td><button type="button" class="btn btn-sm btn-danger btn-hapus-baris" data-bs-toggle="tooltip" title="Hapus Baris"><i class="bi bi-trash"></i></button></td>
            </tr>
        `;
        $('#budget-expenses-table tbody').append(row);

        // Terapkan Cleave.js pada input jumlah baru
        $('#budget-expenses-table tbody tr:last .jumlah-rupiah').each(function() {
            if (typeof Cleave !== 'undefined') {
                new Cleave(this, {
                    numeral: true,
                    numeralThousandsGroupStyle: 'thousand',
                    prefix: 'Rp ',
                    noImmediatePrefix: true,
                    rawValueTrimPrefix: true
                });
            } else {
                console.warn('Cleave.js tidak dimuat. Format Rupiah tidak diterapkan.');
                $(this).attr('type', 'number');
            }
        });

        // Inisialisasi ulang tooltip
        $('[data-bs-toggle="tooltip"]').tooltip();

        updateTotalPengeluaran();
    }

    // Fungsi untuk menghitung dan memperbarui total pengeluaran
    function updateTotalPengeluaran() {
        let total = 0;
        $('#budget-expenses-table tbody tr').each(function() {
            const jumlah = $(this).find('input[name$="[amount]"]').val().replace(/[^0-9]/g, '');
            if (jumlah && !isNaN(parseInt(jumlah))) {
                total += parseInt(jumlah);
            }
        });

        const formattedTotal = typeof Cleave !== 'undefined' ?
            'Rp ' + total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',') :
            'Rp ' + total;
        $('#total-pengeluaran').text(formattedTotal);

        const jumlahAnggaran = $('#amount').val().replace(/[^0-9]/g, '');
        const errorElement = $('#error-pengeluaran');
        const simpanButton = $('#btn-simpan');

        if ($('#flow_type').val() === 'budget' && jumlahAnggaran && total > parseInt(jumlahAnggaran)) {
            const formattedAnggaran = typeof Cleave !== 'undefined' ?
                'Rp ' + jumlahAnggaran.replace(/\B(?=(\d{3})+(?!\d))/g, ',') :
                'Rp ' + jumlahAnggaran;
            errorElement.text(`Total pengeluaran (${formattedTotal}) melebihi jumlah anggaran (${formattedAnggaran})!`);
            errorElement.show();
            simpanButton.prop('disabled', true);
        } else {
            errorElement.hide();
            simpanButton.prop('disabled', false);
        }
    }

    // Inisialisasi tooltip saat halaman dimuat
    $('[data-bs-toggle="tooltip"]').tooltip();
});