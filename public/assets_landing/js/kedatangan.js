$(document).ready(function() {
    // Inisialisasi toast
    const toastElement = document.getElementById('toastNotification');
    const toast = new bootstrap.Toast(toastElement);

    // Inisialisasi DataTables
    const table = $('#arrivalTable').DataTable({
        info: false,
        ordering: false,
        paging: false,
        scrollX: true,
        columns: [
            { title: 'Kode Penerbangan' },
            { title: 'Maskapai' },
            { title: 'Asal Bandara (Kota)' },
            { title: 'Waktu Kedatangan' },
            { title: 'Status' }
        ],
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
        }
    });

    // Fungsi untuk memuat data kedatangan
    function loadArrivals() {
        $('#loadingIndicator').show();
        $('#errorMessage').hide();

        $.ajax({
            url: '/api/arrivals',
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
                        `${item.bandara_asal.nama} (${item.bandara_asal.kota_provinsi})`,
                        `${item.tanggal} ${item.jam}`,
                        item.remark.status
                    ]);

                    // Perbarui tabel
                    table.clear().rows.add(data).draw();
                } else {
                    $('#errorMessage').text(response.message || 'Tidak ada data kedatangan tersedia.').show();
                }
            },
            error: function(xhr) {
                $('#loadingIndicator').hide();
                const errorMsg = xhr.responseJSON?.message || 'Gagal memuat data kedatangan. Silakan coba lagi.';
                $('#errorMessage').text(errorMsg).show();
                
                // Tampilkan toast
                $('#toastNotification').find('.toast-body').text(errorMsg);
                toast.show();
            }
        });
    }

    // Muat data saat halaman dimuat
    loadArrivals();

    // Refresh data setiap 5 menit
    setInterval(loadArrivals, 300000);
});