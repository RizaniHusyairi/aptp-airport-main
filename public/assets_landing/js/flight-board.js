/**
 * Papan jadwal penerbangan — pengambilan data dan render kartu.
 *
 * Dipakai bersama halaman Keberangkatan dan Kedatangan. Konfigurasi dibaca
 * dari atribut data pada elemen .fb-board:
 *   data-endpoint : URL API (mis. /api/departures)
 *   data-mode     : "departure" atau "arrival"
 *
 * Bentuk respons API dipertahankan apa adanya seperti implementasi sebelumnya:
 *   { success: bool, message: string, data: [ {
 *       pesawat: { kode_penerbangan }, maskapai: { nama },
 *       bandara_tujuan|bandara_asal: { nama, kota_provinsi },
 *       tanggal, jam, gate: { nama }, remark: { status }
 *   } ] }
 */
(function () {
    'use strict';

    var REFRESH_MS = 300000; // 5 menit, sama seperti sebelumnya

    /**
     * Petakan teks status dari API ke gaya pil dan ikonnya.
     * Nilai yang benar-benar dikirim API saat ini: "Scheduled", "Check-in Open",
     * "Departed". Kata kunci lain disiapkan untuk kemungkinan nilai lain.
     */
    function statusStyle(raw) {
        var s = (raw || '').toString().toLowerCase();

        if (s.indexOf('cancel') !== -1 || s.indexOf('batal') !== -1) {
            return { cls: 'fb-status--danger', icon: 'bi-x-octagon-fill' };
        }
        if (s.indexOf('delay') !== -1 || s.indexOf('tunda') !== -1 || s.indexOf('lambat') !== -1) {
            return { cls: 'fb-status--warn', icon: 'bi-exclamation-triangle-fill' };
        }
        if (s.indexOf('check-in') !== -1 || s.indexOf('check in') !== -1 || s.indexOf('lapor') !== -1) {
            return { cls: 'fb-status--info', icon: 'bi-person-badge-fill' };
        }
        if (s.indexOf('board') !== -1 || s.indexOf('gate') !== -1) {
            return { cls: 'fb-status--info', icon: 'bi-door-open-fill' };
        }
        if (s.indexOf('depart') !== -1 || s.indexOf('land') !== -1 || s.indexOf('arriv') !== -1 ||
            s.indexOf('tiba') !== -1 || s.indexOf('berangkat') !== -1) {
            return { cls: 'fb-status--ok', icon: 'bi-check-circle-fill' };
        }
        if (s.indexOf('time') !== -1 || s.indexOf('schedule') !== -1 || s.indexOf('jadwal') !== -1) {
            return { cls: 'fb-status--ok', icon: 'bi-clock-fill' };
        }
        return { cls: 'fb-status--neutral', icon: 'bi-info-circle-fill' };
    }

    /** Warna merek maskapai dari API, hanya diterima bila berupa hex yang sah. */
    function brandColor(raw) {
        return /^#[0-9a-f]{3,8}$/i.test(String(raw || '')) ? raw : '#0d2c4a';
    }

    /**
     * Informasi posisi layanan penumpang.
     *
     * Kedatangan  : `conveyor` berupa skalar ("1"/"2"), tersedia di semua data.
     * Keberangkatan: `gate` berupa objek {id, nama} dan hanya ada pada sebagian
     *                penerbangan; `konter`/`konter2`/`konter3` berupa angka
     *                dengan 0 berarti belum ditetapkan. Keduanya ditampilkan
     *                karena konter (lapor diri) dan gate (ruang tunggu) adalah
     *                dua tahap yang berbeda.
     */
    function deskInfo(item, isArrival) {
        if (isArrival) {
            var conveyor = get(item, 'conveyor', null);
            return {
                label: 'Conveyor',
                html: conveyor
                    ? '<span class="fb-gate"><i class="bi bi-suitcase-lg-fill me-1"></i>' + esc(conveyor) + '</span>'
                    : '<span class="fb-gate fb-gate--empty">—</span>'
            };
        }

        var gate = get(item, 'gate.nama', null);
        var counters = [item.konter, item.konter2, item.konter3]
            .filter(function (v) { return Number(v) > 0; })
            .join(', ');

        var parts = [];
        if (gate) {
            parts.push('<span class="fb-gate"><i class="bi bi-door-open-fill me-1"></i>' + esc(gate) + '</span>');
        }
        if (counters) {
            parts.push('<span class="fb-gate fb-gate--counter"><i class="bi bi-person-badge-fill me-1"></i>' + esc(counters) + '</span>');
        }

        return {
            label: 'Gate / Konter',
            html: parts.length ? parts.join(' ') : '<span class="fb-gate fb-gate--empty">—</span>'
        };
    }

    function esc(value) {
        return String(value === null || value === undefined ? '' : value)
            .replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;').replace(/'/g, '&#39;');
    }

    /** Ambil nilai bersarang dengan aman, mis. get(item, 'maskapai.nama'). */
    function get(obj, path, fallback) {
        var parts = path.split('.');
        var cur = obj;
        for (var i = 0; i < parts.length; i++) {
            if (cur === null || cur === undefined) return fallback;
            cur = cur[parts[i]];
        }
        return (cur === null || cur === undefined || cur === '') ? fallback : cur;
    }

    function skeleton(rows) {
        var html = '';
        for (var i = 0; i < rows; i++) {
            html += '<div class="fb-skeleton"></div>';
        }
        return html;
    }

    function stateHtml(icon, title, message, withRetry) {
        return '<div class="fb-state">' +
            '<i class="bi ' + icon + '"></i>' +
            '<p class="fb-state-title">' + esc(title) + '</p>' +
            '<p class="mb-0">' + esc(message) + '</p>' +
            (withRetry ? '<button type="button" class="fb-retry">Coba Lagi</button>' : '') +
            '</div>';
    }

    function rowHtml(item, index, mode) {
        var isArrival = mode === 'arrival';
        var routeKey = isArrival ? 'bandara_asal' : 'bandara_tujuan';

        var code = get(item, 'pesawat.kode_penerbangan', '—');
        var airline = get(item, 'maskapai.nama', '—');
        var airlineCode = get(item, 'maskapai.kode', '');
        var color = brandColor(get(item, 'maskapai.kode_warna', ''));
        var iata = get(item, routeKey + '.iata', '');
        var city = get(item, routeKey + '.kota_provinsi', '');
        var airport = get(item, routeKey + '.nama', '—');
        var date = get(item, 'tanggal', '');
        var time = get(item, 'jam', '—');
        var status = get(item, 'remark.status', 'Tidak diketahui');
        var st = statusStyle(status);

        var desk = deskInfo(item, isArrival);
        var routeLabel = isArrival ? 'Asal' : 'Tujuan';
        var timeLabel = isArrival ? 'Waktu Kedatangan' : 'Waktu Keberangkatan';

        var html = '<div class="fb-row" style="--i: ' + index + '; --brand: ' + esc(color) + '">' +
            '<div class="fb-cell fb-cell--code">' +
                '<span class="fb-cell-label">Registrasi Pesawat</span>' +
                '<span class="fb-code"><i class="bi ' + (isArrival ? 'bi-airplane-fill' : 'bi-airplane-engines-fill') + '"></i>' + esc(code) + '</span>' +
            '</div>' +
            '<div class="fb-cell fb-cell--airline">' +
                '<span class="fb-cell-label">Maskapai</span>' +
                '<span class="fb-airline">' +
                    (airlineCode ? '<span class="fb-airline-badge">' + esc(airlineCode) + '</span>' : '') +
                    esc(airline) +
                '</span>' +
            '</div>' +
            '<div class="fb-cell fb-cell--route">' +
                '<span class="fb-cell-label">' + routeLabel + '</span>' +
                '<span class="fb-city">' +
                    (iata ? '<span class="fb-iata">' + esc(iata) + '</span>' : '') +
                    esc(city || airport) +
                    '<span class="fb-airport">' + esc(airport) + '</span>' +
                '</span>' +
            '</div>';

        // Kolom posisi layanan: Conveyor untuk kedatangan, Gate/Konter untuk keberangkatan
        html += '<div class="fb-cell fb-cell--gate">' +
            '<span class="fb-cell-label">' + desk.label + '</span>' +
            '<span class="fb-desk-group">' + desk.html + '</span>' +
        '</div>';

        html += '<div class="fb-cell fb-cell--time">' +
                '<span class="fb-cell-label">' + timeLabel + '</span>' +
                '<span class="fb-time">' + esc(time) +
                    (date ? '<span class="fb-date">' + esc(date) + '</span>' : '') +
                '</span>' +
            '</div>' +
            '<div class="fb-cell fb-cell--status">' +
                '<span class="fb-cell-label">Status</span>' +
                '<span class="fb-status ' + st.cls + '"><i class="bi ' + st.icon + '"></i>' + esc(status) + '</span>' +
            '</div>' +
        '</div>';

        return html;
    }

    function initBoard(board) {
        var endpoint = board.dataset.endpoint;
        var mode = board.dataset.mode === 'arrival' ? 'arrival' : 'departure';
        var list = board.querySelector('.fb-list');
        var updated = board.querySelector('.fb-updated');
        // Halaman gabungan memuat dua papan sekaligus, jadi penghitungnya
        // dipilih per mode agar keduanya tidak saling menimpa.
        var countEl = document.querySelector('[data-fb-count="' + mode + '"]')
            || document.querySelector('[data-fb-count]');

        if (!endpoint || !list) return;

        var noun = mode === 'arrival' ? 'kedatangan' : 'keberangkatan';

        function setUpdated() {
            if (!updated) return;
            var now = new Date();
            updated.textContent = 'Diperbarui ' + now.toLocaleTimeString('id-ID', {
                hour: '2-digit', minute: '2-digit'
            }) + ' WITA';
        }

        function setCount(n) {
            if (countEl) countEl.textContent = n;
        }

        function load(showSkeleton) {
            if (showSkeleton) {
                list.innerHTML = skeleton(5);
            }

            fetch(endpoint, {
                headers: { 'Accept': 'application/json' },
                credentials: 'same-origin'
            })
                .then(function (res) {
                    return res.json().then(function (body) {
                        return { ok: res.ok, body: body };
                    });
                })
                .then(function (res) {
                    var body = res.body || {};
                    var rows = Array.isArray(body.data) ? body.data : [];

                    if (body.success && rows.length > 0) {
                        var html = '';
                        for (var i = 0; i < rows.length; i++) {
                            html += rowHtml(rows[i], i, mode);
                        }
                        list.innerHTML = html;
                        setCount(rows.length);
                        setUpdated();
                        return;
                    }

                    // Respons berhasil dibaca tapi tidak ada jadwal
                    if (res.ok && body.success) {
                        list.innerHTML = stateHtml('bi-calendar-x',
                            'Belum ada jadwal',
                            'Tidak ada jadwal ' + noun + ' yang tersedia saat ini.', false);
                        setCount(0);
                        setUpdated();
                        return;
                    }

                    throw new Error(body.message || 'Gagal memuat data ' + noun + '.');
                })
                .catch(function (err) {
                    list.innerHTML = stateHtml('bi-wifi-off',
                        'Gagal memuat data',
                        err.message || ('Gagal memuat data ' + noun + '. Silakan coba lagi.'), true);
                    setCount(0);
                });
        }

        // Tombol "Coba Lagi" dipasang lewat delegasi karena elemennya dibuat ulang
        list.addEventListener('click', function (e) {
            if (e.target.classList.contains('fb-retry')) {
                load(true);
            }
        });

        load(true);
        setInterval(function () { load(false); }, REFRESH_MS);
    }

    /** Jam berjalan di hero. */
    function initClock() {
        var el = document.querySelector('[data-fb-clock]');
        if (!el) return;

        function tick() {
            el.textContent = new Date().toLocaleTimeString('id-ID', {
                hour: '2-digit', minute: '2-digit', second: '2-digit'
            });
        }
        tick();
        setInterval(tick, 1000);
    }

    /**
     * Buka tab sesuai parameter ?tab= pada URL.
     * Dipakai oleh pengalih dari /keberangkatan dan /kedatangan agar pengunjung
     * langsung mendarat pada papan yang mereka tuju.
     */
    function initTabFromUrl() {
        var params = new URLSearchParams(window.location.search);
        var tab = (params.get('tab') || '').toLowerCase();
        if (!tab) return;

        var id = null;
        if (tab === 'kedatangan' || tab === 'arrival') id = 'tab-arrival';
        if (tab === 'keberangkatan' || tab === 'departure') id = 'tab-departure';
        if (!id) return;

        var btn = document.getElementById(id);
        if (btn && window.bootstrap && bootstrap.Tab) {
            bootstrap.Tab.getOrCreateInstance(btn).show();
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.fb-board').forEach(initBoard);
        initClock();
        initTabFromUrl();
    });
})();
