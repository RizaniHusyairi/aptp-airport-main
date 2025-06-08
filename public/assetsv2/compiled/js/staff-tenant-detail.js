$(document).ready(function() {
    // Ambil ID dari URL
    const urlParams = new URLSearchParams(window.location.search);
    const pengajuanId = urlParams.get('id');
    console.log('ID Pengajuan:', pengajuanId);

    // Handle klik tombol Lihat Dokumen
    $('#lihat-dokumen').on('click', function(e) {
        e.preventDefault();
        console.log('Melihat dokumen untuk pengajuan ID:', pengajuanId);
        // Placeholder untuk membuka dokumen
        // window.open(`/path/to/documents/pengajuan_${pengajuanId}.pdf`, '_blank');
    });

    // Handle klik tombol Setujui Pengajuan
    $('#setujui-pengajuan').on('click', function() {
        console.log('Menyetujui pengajuan ID:', pengajuanId);
        // Placeholder untuk AJAX
        /*
        $.ajax({
            url: `/api/tenant/${pengajuanId}/setujui`,
            method: 'POST',
            success: function(response) {
                alert('Pengajuan disetujui!');
                window.location.href = 'staff-tenant.html';
            },
            error: function() {
                alert('Gagal menyetujui pengajuan.');
            }
        });
        */
    });

    // Handle klik tombol Tolak Pengajuan
    $('#tolak-pengajuan').on('click', function() {
        console.log('Menolak pengajuan ID:', pengajuanId);
        // Placeholder untuk AJAX
        /*
        $.ajax({
            url: `/api/tenant/${pengajuanId}/tolak`,
            method: 'POST',
            success: function(response) {
                alert('Pengajuan ditolak!');
                window.location.href = 'staff-tenant.html';
            },
            error: function() {
                alert('Gagal menolak pengajuan.');
            }
        });
        */
    });
});