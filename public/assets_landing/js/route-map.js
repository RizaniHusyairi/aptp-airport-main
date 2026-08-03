/**
 * Peta jaringan rute Bandar Udara A.P.T. Pranoto.
 *
 * Menggantikan peta gambar statis dengan peta geografis Leaflet:
 * - ubin gelap agar senada dengan nuansa papan penerbangan
 * - busur melengkung antara Samarinda dan tiap kota tujuan
 * - pesawat kecil yang bergerak menyusuri tiap busur
 * - penanda kode bandara yang bisa disorot
 *
 * Data lintang/bujur berasal dari /api/routes/domestic.
 */
(function () {
    'use strict';

    var WARNA = {
        hub: '#f0a500',
        utama: '#8ec8ff',
        perintis: '#3ddc84'
    };

    /** Titik-titik busur antara dua koordinat (bezier kuadratik sederhana). */
    function busur(a, b, kelengkungan) {
        var titik = [];
        var midLat = (a[0] + b[0]) / 2;
        var midLng = (a[1] + b[1]) / 2;

        // Geser titik kendali tegak lurus terhadap garis lurus a-b
        var dLat = b[0] - a[0];
        var dLng = b[1] - a[1];
        var ctrlLat = midLat - dLng * kelengkungan;
        var ctrlLng = midLng + dLat * kelengkungan;

        for (var t = 0; t <= 1.0001; t += 0.02) {
            var m = 1 - t;
            titik.push([
                m * m * a[0] + 2 * m * t * ctrlLat + t * t * b[0],
                m * m * a[1] + 2 * m * t * ctrlLng + t * t * b[1]
            ]);
        }
        return titik;
    }

    /**
     * Sudut putar ikon pesawat, dalam derajat searah jarum jam.
     *
     * Titik berformat [lat, lng]. Di layar, bujur bertambah ke kanan dan
     * lintang bertambah ke ATAS — jadi sumbu tegaknya terbalik dibanding
     * koordinat layar biasa.
     *
     * Ikon bi-airplane-fill menghadap ke atas saat rotasi 0. Untuk arah gerak
     * (dLng, dLat), rotasi searah jarum jam dari "atas" adalah atan2(dLng, dLat).
     *
     * Catatan: atan2(dLat, dLng) - 90 TIDAK sama dengan ini, melainkan
     * negatifnya — itulah yang membuat pesawat menghadap arah tercermin.
     */
    function sudutDerajat(p1, p2) {
        var dLat = p2[0] - p1[0];
        var dLng = p2[1] - p1[1];
        return Math.atan2(dLng, dLat) * 180 / Math.PI;
    }

    function penandaKota(rute) {
        var warna = rute.jenis === 'perintis' ? WARNA.perintis : WARNA.utama;
        var label = rute.kode || rute.kota;

        return L.divIcon({
            className: '',
            html: '<span class="rm-pin" style="--pin:' + warna + '">'
                + '<i class="rm-pin-dot"></i>'
                + '<b class="rm-pin-code">' + label + '</b>'
                + '</span>',
            iconSize: [0, 0],
            iconAnchor: [0, 0]
        });
    }

    function penandaHub(hub) {
        return L.divIcon({
            className: '',
            html: '<span class="rm-pin rm-pin--hub" style="--pin:' + WARNA.hub + '">'
                + '<i class="rm-pin-ping"></i>'
                + '<i class="rm-pin-dot"></i>'
                + '<b class="rm-pin-code">' + (hub.kode || 'AAP') + '</b>'
                + '</span>',
            iconSize: [0, 0],
            iconAnchor: [0, 0]
        });
    }

    function isiPopup(rute) {
        var judul = rute.kota + (rute.kode ? ' (' + rute.kode + ')' : '');
        var html = '<div class="rm-pop-title">' + judul + '</div>'
            + '<div class="rm-pop-sub">' + (rute.provinsi || '') + ' · Rute ' + (rute.jenis || '') + '</div>';

        var maskapai = rute.maskapai || [];
        if (maskapai.length) {
            html += '<div class="rm-pop-airlines">';
            maskapai.forEach(function (m) {
                html += '<img src="' + m.logo + '" alt="' + m.nama + '" title="' + m.nama + '">';
            });
            html += '</div>';
        }
        return html;
    }

    function init() {
        var wadah = document.getElementById('route-map-canvas');
        if (!wadah) return;

        // Leaflet dimuat dari CDN; bila gagal, tampilkan pesan alih-alih peta kosong
        if (typeof L === 'undefined') {
            wadah.innerHTML = '<div class="rm-fallback">'
                + '<i class="bi bi-wifi-off"></i>'
                + '<p class="mb-0">Peta tidak dapat dimuat. Periksa koneksi internet Anda.</p>'
                + '</div>';
            return;
        }

        fetch('/api/routes/domestic', { headers: { Accept: 'application/json' } })
            .then(function (r) { return r.json(); })
            .then(function (res) {
                if (!res.success || !res.data || !res.data.length) return;
                gambarPeta(wadah, res.hub, res.data);
            })
            .catch(function (e) {
                console.error('Gagal memuat data rute:', e);
                wadah.innerHTML = '<div class="rm-fallback">'
                    + '<i class="bi bi-exclamation-triangle"></i>'
                    + '<p class="mb-0">Data rute belum dapat ditampilkan.</p>'
                    + '</div>';
            });
    }

    /**
     * Isi ringkasan jaringan (jumlah kota tujuan dan maskapai) dari data
     * yang sama dengan yang digambar di peta, bukan angka yang ditulis manual.
     */
    function isiRingkasan(rutes) {
        var kota = document.querySelector('[data-rm-destinations]');
        var maskapai = document.querySelector('[data-rm-airlines]');

        if (kota) {
            kota.textContent = rutes.length;
        }

        if (maskapai) {
            var unik = {};
            rutes.forEach(function (r) {
                (r.maskapai || []).forEach(function (m) {
                    if (m && m.nama) unik[m.nama] = true;
                });
            });
            maskapai.textContent = Object.keys(unik).length;
        }
    }

    function gambarPeta(wadah, hub, rutes) {
        var pusat = [hub.lat, hub.lng];

        isiRingkasan(rutes);

        var map = L.map(wadah, {
            zoomControl: true,
            scrollWheelZoom: false,   // agar gulir halaman tidak "tertangkap" peta
            attributionControl: true
        }).setView([-2.2, 114.5], 5);

        L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; OpenStreetMap &copy; CARTO',
            subdomains: 'abcd',
            maxZoom: 10,
            minZoom: 4
        }).addTo(map);

        var semuaTitik = [pusat];

        rutes.forEach(function (rute, i) {
            var tujuan = [rute.lat, rute.lng];
            semuaTitik.push(tujuan);

            var warna = rute.jenis === 'perintis' ? WARNA.perintis : WARNA.utama;
            var titik = busur(pusat, tujuan, 0.18);

            // Garis dasar yang redup
            L.polyline(titik, {
                color: warna, weight: 1.4, opacity: 0.45, interactive: false
            }).addTo(map);

            // Garis putus-putus beranimasi di atasnya
            var jalur = L.polyline(titik, {
                color: warna, weight: 2, opacity: 0.9,
                dashArray: '6 10', className: 'rm-arc', interactive: false
            }).addTo(map);

            // Pesawat yang menyusuri busur
            var pesawat = L.marker(titik[0], {
                icon: L.divIcon({
                    className: '',
                    html: '<span class="rm-plane-ico" style="--pin:' + warna + '"><i class="bi bi-airplane-fill"></i></span>',
                    iconSize: [0, 0], iconAnchor: [0, 0]
                }),
                interactive: false,
                keyboard: false
            }).addTo(map);

            terbangkan(pesawat, titik, 9000 + i * 700, i * 900);

            var penanda = L.marker(tujuan, { icon: penandaKota(rute), riseOnHover: true }).addTo(map);
            penanda.bindPopup(isiPopup(rute), { className: 'rm-popup', closeButton: false });
            penanda.on('mouseover', function () { jalur.setStyle({ weight: 4, opacity: 1 }); });
            penanda.on('mouseout', function () { jalur.setStyle({ weight: 2, opacity: 0.9 }); });
        });

        L.marker(pusat, { icon: penandaHub(hub), zIndexOffset: 1000 })
            .addTo(map)
            .bindPopup('<div class="rm-pop-title">' + hub.kota + ' (' + hub.kode + ')</div>'
                + '<div class="rm-pop-sub">Pusat jaringan rute</div>', { className: 'rm-popup', closeButton: false });

        // Pastikan seluruh jaringan terlihat
        map.fitBounds(L.latLngBounds(semuaTitik).pad(0.15));

        // Gulir halaman tetap lancar; peta baru bisa di-zoom setelah diklik
        map.on('click', function () { map.scrollWheelZoom.enable(); });
        map.on('mouseout', function () { map.scrollWheelZoom.disable(); });

        // Ukuran ulang setelah tata letak stabil
        setTimeout(function () { map.invalidateSize(); }, 250);
    }

    /** Gerakkan penanda pesawat menyusuri titik-titik busur, berulang. */
    function terbangkan(marker, titik, durasi, jeda) {
        if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
            marker.remove();
            return;
        }

        var mulai = null;
        var el = null;

        function langkah(waktu) {
            if (mulai === null) mulai = waktu + jeda;
            var lewat = waktu - mulai;

            if (lewat >= 0) {
                var p = (lewat % durasi) / durasi;

                /*
                 * Posisi disisipkan (interpolasi) di antara dua titik sampel,
                 * bukan meloncat dari titik ke titik. Karena busur digambar
                 * sebagai garis lurus antar titik sampel yang sama, hasil
                 * interpolasi ini jatuh PERSIS di atas garis yang terlihat.
                 */
                var posisi = p * (titik.length - 1);
                var idx = Math.min(titik.length - 2, Math.floor(posisi));
                var sisa = posisi - idx;
                var a = titik[idx];
                var b = titik[idx + 1];

                marker.setLatLng([
                    a[0] + (b[0] - a[0]) * sisa,
                    a[1] + (b[1] - a[1]) * sisa
                ]);

                if (!el) el = marker.getElement();
                if (el) {
                    var ikon = el.querySelector('.rm-plane-ico');
                    if (ikon) {
                        /*
                         * translate(-50%,-50%) WAJIB ikut ditulis di sini.
                         * Menulis rotate() sendirian akan menimpa properti
                         * transform dari CSS, sehingga ikon kehilangan
                         * pemusatannya dan tampak melenceng dari jalur.
                         *
                         * Tanpa offset sudut tambahan: sudutDerajat() sudah
                         * menghasilkan rotasi searah jarum jam untuk ikon yang
                         * menghadap ke atas.
                         */
                        ikon.style.transform = 'translate(-50%, -50%) rotate('
                            + sudutDerajat(titik[idx], titik[idx + 1]) + 'deg)';
                    }
                }
            }
            requestAnimationFrame(langkah);
        }
        requestAnimationFrame(langkah);
    }

    document.addEventListener('DOMContentLoaded', init);
})();
