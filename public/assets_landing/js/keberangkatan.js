$(document).ready(function() {
    // Inisialisasi toast
    const toastElement = document.getElementById('toastNotification');
    const toast = new bootstrap.Toast(toastElement);

    // Inisialisasi DataTables
    const table = $('#departureTable').DataTable({
        info: false,
        ordering: false,
        paging: false,
    scrollCollapse: true,
    scrollY: '50vh',
        scrollX: true,
        columns: [
            { title: 'Kode Penerbangan' },
            { title: 'Maskapai' },
            { title: 'Tujuan Bandara (Kota)' },
            { title: 'Waktu Keberangkatan' },
            { title: 'Gate' },
            { title: 'Status' }
        ],
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
        }
    });

    // Fungsi untuk memuat data keberangkatan
    function loadDepartures() {
        $('#loadingIndicator').show();
        $('#errorMessage').hide();

        $.ajax({
            url: '/api/departures',
            method: 'GET',
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $('#loadingIndicator').hide();

                if (response.success && response.data.length > 0) {
                    // Map data API ke format DataTables
                    const data = response.data.map(item => [
                        item.pesawat.kode_penerbangan,
                        item.maskapai.nama,
                        `${item.bandara_tujuan.nama} (${item.bandara_tujuan.kota_provinsi})`,
                        `${item.tanggal} ${item.jam}`,
                        item.gate.nama,
                        item.remark.status
                    ]);

                    // Perbarui tabel
                    table.clear().rows.add(data).draw();
                } else {
                    $('#errorMessage').text(response.message || 'Tidak ada data keberangkatan tersedia.').show();
                }
            },
            error: function(xhr) {
                $('#loadingIndicator').hide();
                const errorMsg = xhr.responseJSON?.message || 'Gagal memuat data keberangkatan. Silakan coba lagi.';
                $('#errorMessage').text(errorMsg).show();
                
                // Tampilkan toast
                $('#toastNotification').find('.toast-body').text(errorMsg);
                toast.show();
            }
        });
    }

    // Muat data saat halaman dimuat
    loadDepartures();

    // Refresh data setiap 5 menit
    setInterval(loadDepartures, 300000);
});