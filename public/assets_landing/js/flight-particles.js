/**
 * Latar partikel bernuansa penerbangan untuk hero papan jadwal.
 *
 * Tanpa library eksternal. Tiga lapis:
 *   1. bintang halus yang berkelip dan melayang naik
 *   2. gumpalan awan lembut yang bergeser mendatar
 *   3. siluet pesawat yang melintas sesekali dengan jejak kontrail
 *
 * Otomatis berhenti bila pengguna meminta pengurangan animasi
 * (prefers-reduced-motion) dan saat tab tidak aktif, agar tidak
 * membuang daya di latar belakang.
 */
(function () {
    'use strict';

    function initCanvas(canvas) {
        var ctx = canvas.getContext('2d');
        if (!ctx) return;

        var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        var width = 0;
        var height = 0;
        var dpr = Math.min(window.devicePixelRatio || 1, 2);

        var stars = [];
        var clouds = [];
        var planes = [];
        var rafId = null;
        var lastTime = 0;
        var nextPlaneIn = 1200; // ms sebelum pesawat pertama melintas

        function rand(min, max) {
            return Math.random() * (max - min) + min;
        }

        function resize() {
            var rect = canvas.getBoundingClientRect();
            width = rect.width;
            height = rect.height;

            canvas.width = Math.floor(width * dpr);
            canvas.height = Math.floor(height * dpr);
            ctx.setTransform(dpr, 0, 0, dpr, 0, 0);

            build();
        }

        function build() {
            // Kepadatan bintang mengikuti luas hero, dibatasi agar tetap ringan
            var starCount = Math.min(110, Math.round((width * height) / 12000));
            stars = [];
            for (var i = 0; i < starCount; i++) {
                stars.push({
                    x: rand(0, width),
                    y: rand(0, height),
                    r: rand(0.5, 1.7),
                    vy: rand(-5, -14),          // px per detik, melayang naik
                    vx: rand(-3, 3),
                    alpha: rand(0.2, 0.75),
                    twinkle: rand(0.4, 1.4),
                    phase: rand(0, Math.PI * 2)
                });
            }

            var cloudCount = width < 700 ? 3 : 5;
            clouds = [];
            for (var c = 0; c < cloudCount; c++) {
                clouds.push({
                    x: rand(-0.2, 1.2) * width,
                    y: rand(0.1, 0.85) * height,
                    r: rand(70, 190),
                    vx: rand(6, 20),
                    alpha: rand(0.03, 0.08)
                });
            }
        }

        function spawnPlane() {
            var toRight = Math.random() > 0.35;
            var y = rand(0.14, 0.62) * height;
            var speed = rand(55, 95);

            planes.push({
                x: toRight ? -80 : width + 80,
                y: y,
                dir: toRight ? 1 : -1,
                vx: speed,
                scale: rand(0.75, 1.25),
                trail: []
            });
        }

        function drawStars(dt, now) {
            for (var i = 0; i < stars.length; i++) {
                var s = stars[i];
                s.x += s.vx * dt;
                s.y += s.vy * dt;

                // Muncul kembali dari bawah setelah keluar dari atas
                if (s.y < -4) {
                    s.y = height + 4;
                    s.x = rand(0, width);
                }
                if (s.x < -4) s.x = width + 4;
                if (s.x > width + 4) s.x = -4;

                var flicker = 0.65 + 0.35 * Math.sin(now * 0.001 * s.twinkle + s.phase);
                ctx.globalAlpha = s.alpha * flicker;
                ctx.fillStyle = '#ffffff';
                ctx.beginPath();
                ctx.arc(s.x, s.y, s.r, 0, Math.PI * 2);
                ctx.fill();
            }
            ctx.globalAlpha = 1;
        }

        function drawClouds(dt) {
            for (var i = 0; i < clouds.length; i++) {
                var c = clouds[i];
                c.x += c.vx * dt;
                if (c.x - c.r > width + 40) {
                    c.x = -c.r - 40;
                    c.y = rand(0.1, 0.85) * height;
                }

                var grad = ctx.createRadialGradient(c.x, c.y, 0, c.x, c.y, c.r);
                grad.addColorStop(0, 'rgba(180, 210, 255, ' + c.alpha + ')');
                grad.addColorStop(1, 'rgba(180, 210, 255, 0)');
                ctx.fillStyle = grad;
                ctx.beginPath();
                ctx.arc(c.x, c.y, c.r, 0, Math.PI * 2);
                ctx.fill();
            }
        }

        function drawPlaneShape(p) {
            var s = p.scale;
            ctx.save();
            ctx.translate(p.x, p.y);
            ctx.scale(p.dir * s, s);

            ctx.fillStyle = 'rgba(255, 255, 255, 0.85)';
            ctx.beginPath();
            // Badan pesawat
            ctx.moveTo(16, 0);
            ctx.lineTo(-6, -2.6);
            ctx.lineTo(-14, -2.6);
            ctx.lineTo(-11, 0);
            ctx.lineTo(-14, 2.6);
            ctx.lineTo(-6, 2.6);
            ctx.closePath();
            ctx.fill();
            // Sayap
            ctx.beginPath();
            ctx.moveTo(2, 0);
            ctx.lineTo(-6, -11);
            ctx.lineTo(-2, -11);
            ctx.lineTo(4, 0);
            ctx.closePath();
            ctx.fill();
            ctx.beginPath();
            ctx.moveTo(2, 0);
            ctx.lineTo(-6, 11);
            ctx.lineTo(-2, 11);
            ctx.lineTo(4, 0);
            ctx.closePath();
            ctx.fill();

            ctx.restore();
        }

        function drawPlanes(dt) {
            for (var i = planes.length - 1; i >= 0; i--) {
                var p = planes[i];
                p.x += p.vx * p.dir * dt;

                // Rekam jejak kontrail
                p.trail.push({ x: p.x, y: p.y, life: 1 });
                if (p.trail.length > 90) p.trail.shift();

                // Gambar kontrail dari yang tertua ke terbaru
                for (var t = 0; t < p.trail.length; t++) {
                    var pt = p.trail[t];
                    pt.life -= dt * 0.45;
                    if (pt.life <= 0) continue;

                    ctx.globalAlpha = pt.life * 0.28;
                    ctx.fillStyle = '#ffffff';
                    ctx.beginPath();
                    ctx.arc(pt.x - p.dir * 14 * p.scale, pt.y, 1.5 * p.scale * pt.life, 0, Math.PI * 2);
                    ctx.fill();
                }
                ctx.globalAlpha = 1;

                drawPlaneShape(p);

                // Buang setelah keluar layar
                if ((p.dir > 0 && p.x > width + 120) || (p.dir < 0 && p.x < -120)) {
                    planes.splice(i, 1);
                }
            }
        }

        function frame(now) {
            if (!lastTime) lastTime = now;
            // Batasi dt agar animasi tidak "meloncat" setelah tab kembali aktif
            var dt = Math.min((now - lastTime) / 1000, 0.05);
            lastTime = now;

            ctx.clearRect(0, 0, width, height);

            drawClouds(dt);
            drawStars(dt, now);

            nextPlaneIn -= dt * 1000;
            if (nextPlaneIn <= 0 && planes.length < 2) {
                spawnPlane();
                nextPlaneIn = rand(7000, 15000);
            }
            drawPlanes(dt);

            rafId = window.requestAnimationFrame(frame);
        }

        function start() {
            if (rafId === null) {
                lastTime = 0;
                rafId = window.requestAnimationFrame(frame);
            }
        }

        function stop() {
            if (rafId !== null) {
                window.cancelAnimationFrame(rafId);
                rafId = null;
            }
        }

        resize();

        if (reduceMotion) {
            // Gambar satu bingkai statis saja, tanpa animasi
            ctx.clearRect(0, 0, width, height);
            drawClouds(0);
            drawStars(0, 0);
            return;
        }

        start();

        // Hemat daya saat tab tidak terlihat
        document.addEventListener('visibilitychange', function () {
            if (document.hidden) { stop(); } else { start(); }
        });

        var resizeTimer = null;
        window.addEventListener('resize', function () {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(resize, 200);
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.fb-canvas').forEach(initCanvas);
    });
})();
