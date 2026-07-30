{{--
    Kartu Tautan Terkait, dikelompokkan per `group`.
    Dipakai bersama oleh beranda dan halaman /tautan-terkait.
    Data berasal dari helper externalLinks() (ter-cache).
--}}
@foreach(externalLinks() as $groupName => $links)
    <div class="tt-group">
        <h3 class="tt-group-title">{{ $groupName }}</h3>
        <div class="row g-4">
            @foreach($links as $link)
                <div class="col-lg-3 col-md-6">
                    <a href="{{ $link['url'] }}" target="_blank" rel="noopener" class="tt-card">
                        @if($link['logo'])
                            <div class="tt-card-logo">
                                <img src="{{ $link['logo'] }}" alt="Logo {{ $link['name'] }}" loading="lazy" decoding="async">
                            </div>
                        @else
                            <div class="tt-card-icon"><i class="bi {{ $link['icon'] }}"></i></div>
                        @endif
                        <h4 class="tt-card-title">{{ $link['name'] }}</h4>
                        @if($link['description'])
                            <p class="tt-card-desc">{{ $link['description'] }}</p>
                        @endif
                        <span class="tt-card-link">Kunjungi <i class="bi bi-arrow-right"></i></span>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
@endforeach
