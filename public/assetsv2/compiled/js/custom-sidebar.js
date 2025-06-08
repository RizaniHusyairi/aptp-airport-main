$(document).ready(function() {
    // Toggle sidebar saat tombol burger diklik
    $('.burger-btn-dekstop').on('click', function(e) {
        e.preventDefault();
        var $sidebar = $('.sidebar-wrapper');
        var $main = $('#main');

        if ($sidebar.hasClass('icon-only')) {
            // Kembalikan ke mode normal
            $sidebar.removeClass('icon-only');
            $main.removeClass('icon-only-margin');
        } else {
            // Ubah ke mode icon-only
            $sidebar.addClass('icon-only');
            $main.addClass('icon-only-margin');
        }
    });

    // Pastikan sidebar tetap terlihat di desktop
    $(window).on('resize', function() {
        var $sidebar = $('.sidebar-wrapper');
        var $main = $('#main');

        if ($(window).width() >= 768) {
            $sidebar.show();
        }else{
            if ($('.sidebar-wrapper').hasClass('icon-only')) {
                $sidebar.removeClass('icon-only');
                $main.removeClass('icon-only-margin');
            }

            
            
        }
    });
});

$(document).ready(function() {
    let tooltipList = [];

    // Fungsi untuk menginisialisasi tooltip
    function initializeTooltips() {
        // Hancurkan tooltip yang sudah ada
        tooltipList.forEach(function(tooltip) {
            tooltip.dispose();
        });
        tooltipList = [];

        // Inisialisasi tooltip hanya jika sidebar icon-only dan layar desktop
        if ($('.sidebar-wrapper').hasClass('icon-only') && $(window).width() >= 768) {
            const tooltipTriggerList = document.querySelectorAll('.sidebar-link[data-bs-toggle="tooltip"]');
            tooltipList = Array.from(tooltipTriggerList).map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl, {
                    trigger: 'hover',
                    placement: 'right',
                    container: 'body'
                });
            });
        }

        

    }

    // Inisialisasi awal
    initializeTooltips();

    // Perbarui tooltip saat sidebar berubah
    $('.burger-btn-dekstop').on('click', function() {
        setTimeout(initializeTooltips, 300); // Tunggu transisi selesai
    });

    // Perbarui tooltip saat ukuran layar berubah
    $(window).on('resize', function() {
        initializeTooltips();
    });
});