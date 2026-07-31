
@php
          $user = auth()->user();
@endphp
<div class="sidebar-wrapper active">
    <div class="sidebar-header position-relative">
        <div class="d-flex justify-content-between align-items-center">
            <div class="logo">
                <a class="logo-apt" href="{{ route('home') }}"><img src="{{ asset('assets_login/images/logo-apt.svg') }}" alt="Logo"></a>
                <a class="logo-mini-apt" href="{{ route('home') }}"><img src="{{ asset('assetsv2/image/logo/logo-mini-apt.svg') }}" alt="Logo-mini"></a>
            </div>
            <div class="theme-toggle d-flex gap-2 align-items-center mt-2">
                <!-- Theme toggle SVG and input -->
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 21 21">...</svg>
                <div class="form-check form-switch fs-6">
                    <input class="form-check-input me-0" type="checkbox" id="toggle-dark" style="cursor: pointer">
                    <label class="form-check-label"></label>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">...</svg>
            </div>
            <div class="sidebar-toggler x">
                <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
            </div>
        </div>
    </div>
    <div class="sidebar-menu">
        <ul class="menu">
            <li class="sidebar-title">Menu</li>
            <li class="sidebar-item {{ Route::is('staff.dashboard.index') ? 'active' : '' }}">
                <a href="{{ route('staff.dashboard.index') }}" class='sidebar-link' data-bs-toggle="tooltip" data-bs-placement="right" title="Dashboard">
                    <i class="bi bi-grid-fill"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="sidebar-item {{ Route::is('persuratan.staffIndex') ? 'active' : '' }}">
                <a href="{{ route('persuratan.staffIndex') }}" class='sidebar-link' data-bs-toggle="tooltip" data-bs-placement="right" title="Surat">
                    <i class="bi bi-book"></i>
                    <span>Surat</span>
                </a>
            </li>
            {{--
                Menu dikelompokkan per bidang kerja. Judul kelompok hanya
                dirender bila ada minimal satu menu yang boleh diakses,
                sehingga tidak muncul judul tanpa isi.
            --}}
            @foreach ($permissionRoutes as $groupName => $items)
                @php
                    $visibleItems = collect($items)->filter(function ($data, $permissionName) use ($user) {
                        return $user->hasPermission($permissionName);
                    });
                @endphp

                @if ($visibleItems->isNotEmpty())
                    <li class="sidebar-title">{{ $groupName }}</li>

                    @foreach ($visibleItems as $permissionName => $data)
                        @if ($permissionName === 'Manajemen Posko Nataru')
                            <li class="sidebar-item has-sub {{ Route::is('staff.nataru.*') ? 'active' : '' }}">
                                <a href="#" class='sidebar-link'>
                                    <i class="{{ $data['icon'] }}"></i>
                                    <span>{{ $data['label'] }}</span>
                                </a>
                                <ul class="submenu {{ Route::is('staff.nataru.*') ? 'active' : '' }}">
                                    <li class="submenu-item {{ Route::is('staff.nataru.dashboard') ? 'active' : '' }}">
                                        <a href="{{ route('staff.nataru.dashboard') }}">Dashboard &amp; Grafik</a>
                                    </li>
                                    <li class="submenu-item {{ Route::is('staff.nataru-events.index') ? 'active' : '' }}">
                                        <a href="{{ route('staff.nataru-events.index') }}">Manajemen Posko</a>
                                    </li>
                                </ul>
                            </li>
                        @else
                            <li class="sidebar-item {{ Route::is($data['route']) ? 'active' : '' }}">
                                <a href="{{ route($data['route']) }}" class='sidebar-link' data-bs-toggle="tooltip" data-bs-placement="right" title="{{ $data['label'] }}">
                                    <i class="{{ $data['icon'] }}"></i>
                                    <span>{{ $data['label'] }}</span>
                                </a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach
        </ul>
    </div>
</div>
