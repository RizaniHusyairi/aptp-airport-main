<header id="header" class="header d-flex align-items-center @yield('header-class', '')">
    <div class="container position-relative d-flex align-items-center justify-content-between">
        <a href="{{ route('home') }}" class="logo d-flex align-items-center me-auto me-xl-0">
            <img src="{{ asset('assets_landing/img/logo/logo-apt.svg') }}" alt="logo apt" class="logo-apt">
            <img src="{{ asset('assets_landing/img/logo/logo-white-apt.svg') }}" alt="logo apt" class="logo-white-apt">
        </a>

        <nav id="navmenu" class="navmenu">
            <ul>
                @foreach($menuItems['header'] as $item)
                    @if(isset($item['dropdown']))
                        <li class="dropdown">
                            <a class="nav-title">
                                <span>{{ $item['name'] }}</span>
                                <i class="bi bi-chevron-down toggle-dropdown"></i>
                            </a>
                            <ul>
                                @foreach($item['dropdown'] as $subItem)
                                    @if(isset($subItem['dropdown']))
                                        <li class="dropdown">
                                            <a>
                                                <span>{{ $subItem['name'] }}</span>
                                                <i class="bi bi-chevron-down toggle-dropdown"></i>
                                            </a>
                                            <ul>
                                                @foreach($subItem['dropdown'] as $subSubItem)
                                                    <li>
                                                        <a href="{{ $subSubItem['route'] }}">{{ $subSubItem['name'] }}</a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </li>
                                    @else
                                        <li >
                                            <a href="{{ $subItem['route'] }}"
                                               @if(isset($subItem['external'])) target="_blank" @endif>
                                                {{ $subItem['name'] }}
                                            </a>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </li>
                    @else
                        <li>
                            <a href="{{ $item['route'] }}" class="nav-title">{{ $item['name'] }}</a>
                        </li>
                    @endif
                @endforeach
            </ul>
            <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
        </nav>
        @auth
        <a class="btn-getstarted" href="{{ route('root') }}">Dashboard</a>
        @else
        <a class="btn-getstarted" href="{{ route('login') }}">Masuk</a>

        @endauth
    </div>
</header>