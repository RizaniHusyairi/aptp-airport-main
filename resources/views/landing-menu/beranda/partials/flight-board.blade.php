{{--
    Papan jadwal penerbangan (tanpa hero).
    Dipakai dua kali pada halaman Jadwal Penerbangan — satu per tab.

    Variabel:
      $mode      'departure' | 'arrival'
      $endpoint  URL API, mis. route('api.departures')
--}}
@php
    $isArrival = ($mode ?? 'departure') === 'arrival';
@endphp

<div class="fb-board" data-mode="{{ $isArrival ? 'arrival' : 'departure' }}"
     data-endpoint="{{ $endpoint }}">

    <div class="fb-board-head">
        <h2 class="fb-board-title">
            <i class="bi {{ $isArrival ? 'bi-box-arrow-in-down-left' : 'bi-box-arrow-up-right' }}"></i>
            Jadwal {{ $isArrival ? 'Kedatangan' : 'Keberangkatan' }} Hari Ini
        </h2>
        <span class="fb-updated">Memuat…</span>
    </div>

    {{-- Header kolom, hanya tampil di layar lebar --}}
    <div class="fb-cols">
        <span>Registrasi</span>
        <span>Maskapai</span>
        <span>{{ $isArrival ? 'Asal' : 'Tujuan' }}</span>
        <span>{{ $isArrival ? 'Conveyor' : 'Gate / Konter' }}</span>
        <span>Waktu {{ $isArrival ? 'Kedatangan' : 'Keberangkatan' }}</span>
        <span>Status</span>
    </div>

    {{-- Diisi oleh flight-board.js --}}
    <div class="fb-list"></div>

</div>
