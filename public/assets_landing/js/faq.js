/**
 * FAQ — animasi masuk bertingkat, pencarian, dan filter kategori.
 *
 * Catatan desain: daftar FAQ sengaja TIDAK memakai AOS. AOS di situs ini
 * diinisialisasi dengan once:true pada event load (lihat main.js), sehingga
 * item yang belum pernah masuk viewport akan tetap transparan ketika hasil
 * filter memindahkannya ke atas. Di sini animasi dijalankan lewat kelas
 * .faq-animate yang dipasang ulang setiap kali daftar berubah.
 *
 * Skrip ini aman dimuat di halaman mana pun:
 * - setiap .faq-accordion mendapat animasi masuk (beranda, halaman layanan)
 * - pencarian & filter hanya aktif bila toolbar-nya ada (halaman /faq)
 */
(function () {
    'use strict';

    /**
     * Pasang ulang animasi bertingkat. Kelas dilepas dulu lalu dipaksa reflow
     * agar animasi benar-benar diputar ulang, bukan diabaikan browser.
     */
    function replayStagger(container, visible) {
        container.querySelectorAll('.faq-item').forEach(function (item) {
            item.classList.remove('faq-animate');
        });

        void container.offsetWidth; // paksa reflow

        visible.forEach(function (item, index) {
            item.style.setProperty('--i', index);
            item.classList.add('faq-animate');
        });
    }

    function closeOpenPanels(container) {
        // Tutup jawaban yang terbuka agar tinggi halaman tidak melompat saat difilter
        container.querySelectorAll('.accordion-collapse.show').forEach(function (panel) {
            panel.classList.remove('show');
            var btn = container.querySelector('[data-bs-target="#' + panel.id + '"]');
            if (btn) {
                btn.classList.add('collapsed');
                btn.setAttribute('aria-expanded', 'false');
            }
        });
    }

    function initFilter(accordion) {
        var searchInput = document.getElementById('faq-search');
        var pills = Array.prototype.slice.call(document.querySelectorAll('.faq-pill'));
        var noResults = document.getElementById('faq-no-results');
        var counter = document.getElementById('faq-count');

        if (!searchInput && pills.length === 0) {
            return; // halaman ini tidak punya toolbar filter
        }

        var items = Array.prototype.slice.call(accordion.querySelectorAll('.faq-item'));
        var activeCategory = 'all';
        var searchTerm = '';
        var debounceTimer = null;

        function applyFilter() {
            var visible = [];

            items.forEach(function (item) {
                var matchCategory = activeCategory === 'all' || item.dataset.category === activeCategory;
                var matchSearch = searchTerm === '' || (item.dataset.search || '').indexOf(searchTerm) !== -1;

                if (matchCategory && matchSearch) {
                    item.style.display = '';
                    visible.push(item);
                } else {
                    item.style.display = 'none';
                }
            });

            if (noResults) {
                noResults.style.display = visible.length === 0 ? 'block' : 'none';
            }

            if (counter) {
                counter.textContent = visible.length === 0
                    ? 'Tidak ada pertanyaan yang cocok'
                    : 'Menampilkan ' + visible.length + ' dari ' + items.length + ' pertanyaan';
            }

            closeOpenPanels(accordion);
            replayStagger(accordion, visible);
        }

        if (searchInput) {
            searchInput.addEventListener('input', function () {
                var value = this.value.toLowerCase().trim();
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(function () {
                    searchTerm = value;
                    applyFilter();
                }, 150);
            });
        }

        pills.forEach(function (pill) {
            pill.addEventListener('click', function () {
                pills.forEach(function (p) { p.classList.remove('active'); });
                this.classList.add('active');
                activeCategory = this.dataset.category;
                applyFilter();
            });
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        var accordions = Array.prototype.slice.call(document.querySelectorAll('.faq-accordion'));
        if (accordions.length === 0) {
            return;
        }

        // Animasi masuk untuk semua daftar FAQ di halaman ini
        accordions.forEach(function (accordion) {
            var items = Array.prototype.slice.call(accordion.querySelectorAll('.faq-item'));
            replayStagger(accordion, items);
        });

        // Pencarian & filter hanya untuk daftar utama di halaman /faq
        var main = document.getElementById('faq-accordion');
        if (main) {
            initFilter(main);
        }
    });
})();
