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
            <li class="sidebar-item {{ Route::is('root') ? 'active' : '' }}">
                <a href="{{ route('root') }}" class='sidebar-link'>
                    <i class="bi bi-grid-fill"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="sidebar-item {{ Route::is('customers.index') ? 'active' : '' }}">
                <a href="{{ route('customers.index') }}" class='sidebar-link'>
                    <i class="bi bi-person-fill"></i>
                    <span>Pengguna</span>
                </a>
            </li>
            <li class="sidebar-item {{ Route::is('roles.index') ? 'active' : '' }}">
                <a href="{{ route('roles.index') }}" class='sidebar-link'>
                    <i class="bi bi-shield-lock-fill"></i>
                    <span>Roles</span>
                </a>
            </li>
            <li class="sidebar-item {{ Route::is('admin.tourism.index') ? 'active' : '' }}">
                <a href="{{ route('admin.tourism.index') }}" class='sidebar-link'>
                    <i class="bi bi-compass-fill"></i>
                    <span>Pariwisata</span>
                </a>
            </li>
            <li class="sidebar-item {{ Route::is('admin.facilities.index') ? 'active' : '' }}">
                <a href="{{ route('admin.facilities.index') }}" class='sidebar-link'>
                    <i class="bi bi-gem"></i>
                    <span>Fasilitas</span>
                </a>
            </li>
            <li class="sidebar-item {{ Route::is('slider.staffIndex') ? 'active' : '' }}">
                <a href="{{ route('slider.staffIndex') }}" class='sidebar-link'>
                    <i class="bi bi-image"></i>
                    <span>Slider</span>
                </a>
            </li>
            {{-- ... setelah menu Slider ... --}}
            <li class="sidebar-item {{ Route::is('admin.info-slides.*') ? 'active' : '' }}">
                <a href="{{ route('admin.info-slides.index') }}" class='sidebar-link'>
                    <i class="bi bi-card-image"></i>
                    <span>Slide Informasi</span>
                </a>
            </li>
            <li class="sidebar-item {{ Route::is('staff.periodic-documents.*') ? 'active' : '' }}">
                <a href="{{ route('staff.periodic-documents.index') }}" class='sidebar-link'>
                    <i class="bi bi-calendar-date"></i>
                    <span>Informasi Berkala</span>
                </a>
            </li>
            <li class="sidebar-item {{ Route::is('staff.immediate-informations.*') ? 'active' : '' }}">
                <a href="{{ route('staff.immediate-informations.index') }}" class='sidebar-link'>
                    <i class="bi bi-broadcast-pin"></i>
                    <span>Informasi Serta Merta</span>
                </a>
            </li>
            <li class="sidebar-item {{ Route::is('staff.evergreen-informations.*') ? 'active' : '' }}">
                <a href="{{ route('staff.evergreen-informations.index') }}" class='sidebar-link'>
                    <i class="bi bi-archive-fill"></i>
                    <span>Informasi Setiap Saat</span>
                </a>
            </li>
            <li class="sidebar-item {{ Route::is('staff.information-service-reports.*') ? 'active' : '' }}">
                <a href="{{ route('staff.information-service-reports.index') }}" class='sidebar-link'>
                    <i class="bi bi-archive-fill"></i>
                    <span>Laporan Layanan Informasi</span>
                </a>
            </li>
        </ul>
    </div>
</div>