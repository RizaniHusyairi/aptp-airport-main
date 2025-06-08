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
            $(this).attr('type', 'number'); // Fallback ke input number
        }
    });

    // Tampilkan/sembunyikan tabel detail pengeluaran
    $('#aliran-dana').on('change', function() {
        if ($(this).val() === 'Anggaran') {
            $('#detail-pengeluaran-container').show();
            // Tambah baris awal jika tabel kosong
            if ($('#table-detail-pengeluaran tbody tr').length === 0) {
                tambahBaris();
            }
        } else {
            $('#detail-pengeluaran-container').hide();
            $('#table-detail-pengeluaran tbody').empty();
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
    $('#jumlah').on('input', function() {
        updateTotalPengeluaran();
    });

    // Fungsi untuk menambah baris
    function tambahBaris() {
        const rowCount = $('#table-detail-pengeluaran tbody tr').length + 1;
        const row = `
            <tr>
                <td><input type="text" name="pengeluaran[${rowCount}][deskripsi]" class="form-control" required></td>
                <td><input type="text" name="pengeluaran[${rowCount}][jumlah]" class="form-control jumlah-rupiah" required></td>
                <td><button type="button" class="btn btn-sm btn-danger btn-hapus-baris" data-bs-toggle="tooltip" title="Hapus Baris"><i class="bi bi-trash"></i></button></td>
            </tr>
        `;
        $('#table-detail-pengeluaran tbody').append(row);

        // Terapkan Cleave.js pada input jumlah baru
        $('#table-detail-pengeluaran tbody tr:last .jumlah-rupiah').each(function() {
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
                $(this).attr('type', 'number'); // Fallback ke input number
            }
        });

        // Inisialisasi ulang tooltip
        $('[data-bs-toggle="tooltip"]').tooltip();

        updateTotalPengeluaran();
    }

    // Fungsi untuk menghitung dan memperbarui total pengeluaran
    function updateTotalPengeluaran() {
        let total = 0;
        $('#table-detail-pengeluaran tbody tr').each(function() {
            const jumlah = $(this).find('input[name$="[jumlah]"]').val().replace(/[^0-9]/g, '');
            if (jumlah && !isNaN(parseInt(jumlah))) {
                total += parseInt(jumlah);
            }
        });

        // Format total ke Rupiah
        const formattedTotal = typeof Cleave !== 'undefined' ?
            'Rp ' + total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',') :
            'Rp ' + total;
        $('#total-pengeluaran').text(formattedTotal);

        // Validasi real-time: cek total pengeluaran vs jumlah anggaran
        const jumlahAnggaran = $('#jumlah').val().replace(/[^0-9]/g, '');
        const errorElement = $('#error-pengeluaran');
        const simpanButton = $('#btn-simpan');

        if ($('#aliran-dana').val() === 'Anggaran' && jumlahAnggaran && total > parseInt(jumlahAnggaran)) {
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

    // Validasi dan submit form
    $('#form-tambah-laporan').on('submit', function(e) {
        e.preventDefault();

        // Ambil data form
        const aliranDana = $('#aliran-dana').val();
        const jumlah = $('#jumlah').val().replace(/[^0-9]/g, ''); // Hapus format Rupiah
        const periode = $('#periode').val();
        const catatan = $('#catatan').val();
        const pengeluaran = [];

        // Validasi dasar
        if (!aliranDana || !jumlah || !periode || !catatan) {
            alert('Semua field wajib diisi!');
            return;
        }

        if (parseInt(jumlah) <= 0) {
            alert('Jumlah harus lebih dari 0!');
            return;
        }

        // Validasi detail pengeluaran jika Aliran Dana = Anggaran
        if (aliranDana === 'Anggaran') {
            let totalPengeluaran = 0;
            let valid = true;

            $('#table-detail-pengeluaran tbody tr').each(function() {
                const deskripsi = $(this).find('input[name$="[deskripsi]"]').val();
                const jumlahPengeluaran = $(this).find('input[name$="[jumlah]"]').val().replace(/[^0-9]/g, '');

                if (!deskripsi || !jumlahPengeluaran) {
                    alert('Semua field detail pengeluaran wajib diisi!');
                    valid = false;
                    return false;
                }

                if (parseInt(jumlahPengeluaran) <= 0) {
                    alert('Jumlah pengeluaran harus lebih dari 0!');
                    valid = false;
                    return false;
                }

                totalPengeluaran += parseInt(jumlahPengeluaran);

                pengeluaran.push({
                    deskripsi: deskripsi,
                    jumlah: parseInt(jumlahPengeluaran)
                });
            });

            if (!valid) return;

            if (pengeluaran.length === 0) {
                alert('Minimal satu detail pengeluaran diperlukan untuk Anggaran!');
                return;
            }

            // Validasi total pengeluaran (redundan karena validasi real-time, tapi untuk keamanan)
            if (totalPengeluaran > parseInt(jumlah)) {
                alert('Total pengeluaran (Rp ' + totalPengeluaran.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',') + ') tidak boleh melebihi jumlah anggaran (Rp ' + jumlah.replace(/\B(?=(\d{3})+(?!\d))/g, ',') + ')!');
                return;
            }
        }

        // Data untuk dikirim
        const data = {
            aliran_dana: aliranDana,
            jumlah: parseInt(jumlah),
            periode: periode,
            catatan: catatan,
            pengeluaran: aliranDana === 'Anggaran' ? pengeluaran : []
        };

        console.log('Data yang akan dikirim:', data); // Debugging

        // Placeholder untuk AJAX
        /*
        $.ajax({
            url: '/api/laporan-keuangan',
            method: 'POST',
            data: JSON.stringify(data),
            contentType: 'application/json',
            success: function(response) {
                alert('Laporan keuangan berhasil disimpan!');
                window.location.href = 'staff-laporan-keuangan.html';
            },
            error: function() {
                alert('Gagal menyimpan laporan keuangan.');
            }
        });
        */

        // Simulasi sukses
        alert('Laporan keuangan berhasil disimpan (simulasi)!');
        window.location.href = 'staff-laporan-keuangan.html';
    });
});