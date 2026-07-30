{{--
    Papan jadwal penerbangan bernuansa penerbangan.
    Dipakai bersama halaman Keberangkatan dan Kedatangan.

    Variabel:
      $mode      'departure' | 'arrival'
      $endpoint  URL API, mis. route('api.departures')
      $eyebrow   label kecil di atas judul
      $titleLead teks judul sebelum kata berwarna
      $titleWord kata yang diberi warna aksen
      $subtitle  paragraf pengantar
--}}
@php
    $isArrival = ($mode ?? 'departure') === 'arrival';
@endphp

<section class="fb">

    {{-- HERO: langit senja + kanvas partikel --}}
    <div class="fb-hero">
        <canvas class="fb-canvas" aria-hidden="true"></canvas>

        <div class="container fb-hero-inner">
            <div class="row">
                <div class="col-lg-8">
                    <span class="fb-eyebrow">
                        <i class="bi {{ $isArrival ? 'bi-box-arrow-in-down-left' : 'bi-box-arrow-up-right' }}"></i>
                        {{ $eyebrow }}
                    </span>

                    <h1 class="fb-title">{{ $titleLead }} <span>{{ $titleWord }}</span></h1>
                    <p class="fb-subtitle">{{ $subtitle }}</p>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-9">
                    <div class="fb-meta">
                        <div class="fb-meta-card">
                            <span class="fb-meta-label">Kode Bandara</span>
                            <span class="fb-meta-value">AAP</span>
                        </div>
                        <div class="fb-meta-card">
                            <span class="fb-meta-label">Waktu Setempat</span>
                            <span class="fb-meta-value" data-fb-clock>--:--:--</span>
                        </div>
                        <div class="fb-meta-card">
                            <span class="fb-meta-label">{{ $isArrival ? 'Kedatangan' : 'Keberangkatan' }} Terdata</span>
                            <span class="fb-meta-value" data-fb-count>0</span>
                        </div>
                        <div class="fb-meta-card">
                            <span class="fb-meta-label">Status Data</span>
                            <span class="fb-meta-value" style="font-size:15px;">
                                <span class="fb-live-dot"></span>Langsung
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- PAPAN JADWAL --}}
    <div class="fb-board-section">
        <div class="container">
            <div class="fb-board" data-mode="{{ $isArrival ? 'arrival' : 'departure' }}"
                 data-endpoint="{{ $endpoint }}">

                <div class="fb-board-head">
                    <h2 class="fb-board-title">
                        <i class="bi bi-card-list"></i>
                        Jadwal {{ $isArrival ? 'Kedatangan' : 'Keberangkatan' }} Hari Ini
                    </h2>
                    <span class="fb-updated">Memuat…</span>
                </div>

                {{-- Header kolom, hanya tampil di layar lebar --}}
                <div class="fb-cols">
                    <span>Kode Penerbangan</span>
                    <span>Maskapai</span>
                    <span>{{ $isArrival ? 'Asal' : 'Tujuan' }}</span>
                    @unless($isArrival)
                        <span>Gate</span>
                    @endunless
                    <span>Waktu {{ $isArrival ? 'Kedatangan' : 'Keberangkatan' }}</span>
                    <span>Status</span>
                </div>

                {{-- Diisi oleh flight-board.js --}}
                <div class="fb-list"></div>

            </div>
        </div>
    </div>

</section>
