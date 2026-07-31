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
            {{--
                Menu admin dikelompokkan per bidang pengelolaan agar mudah
                dipindai. Judul kelompok memakai kelas .sidebar-title bawaan tema.
            --}}
            <li class="sidebar-title">Menu</li>
            <li class="sidebar-item {{ Route::is('root') ? 'active' : '' }}">
                <a href="{{ route('root') }}" class='sidebar-link' data-bs-toggle="tooltip" data-bs-placement="right" title="Beranda Dashboard">
                    <i class="bi bi-grid-fill"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <li class="sidebar-title">Pengguna &amp; Akses</li>
            <li class="sidebar-item {{ Route::is('customers.*') ? 'active' : '' }}">
                <a href="{{ route('customers.index') }}" class='sidebar-link' data-bs-toggle="tooltip" data-bs-placement="right" title="Daftar Pengguna">
                    <i class="bi bi-person-fill"></i>
                    <span>Pengguna</span>
                </a>
            </li>
            <li class="sidebar-item {{ Route::is('roles.*') ? 'active' : '' }}">
                <a href="{{ route('roles.index') }}" class='sidebar-link' data-bs-toggle="tooltip" data-bs-placement="right" title="Peran &amp; Hak Akses">
                    <i class="bi bi-shield-lock-fill"></i>
                    <span>Peran &amp; Hak Akses</span>
                </a>
            </li>

            <li class="sidebar-title">Konten Website</li>
            <li class="sidebar-item {{ Route::is('slider.staffIndex') ? 'active' : '' }}">
                <a href="{{ route('slider.staffIndex') }}" class='sidebar-link' data-bs-toggle="tooltip" data-bs-placement="right" title="Slider Beranda">
                    <i class="bi bi-images"></i>
                    <span>Slider</span>
                </a>
            </li>
            <li class="sidebar-item {{ Route::is('admin.info-slides.*') ? 'active' : '' }}">
                <a href="{{ route('admin.info-slides.index') }}" class='sidebar-link' data-bs-toggle="tooltip" data-bs-placement="right" title="Slide Informasi">
                    <i class="bi bi-card-image"></i>
                    <span>Slide Informasi</span>
                </a>
            </li>
            <li class="sidebar-item {{ Route::is('admin.tourism.*') ? 'active' : '' }}">
                <a href="{{ route('admin.tourism.index') }}" class='sidebar-link' data-bs-toggle="tooltip" data-bs-placement="right" title="Destinasi Pariwisata">
                    <i class="bi bi-compass-fill"></i>
                    <span>Pariwisata</span>
                </a>
            </li>
            <li class="sidebar-item {{ Route::is('admin.facilities.*') ? 'active' : '' }}">
                <a href="{{ route('admin.facilities.index') }}" class='sidebar-link' data-bs-toggle="tooltip" data-bs-placement="right" title="Fasilitas Bandara">
                    <i class="bi bi-gem"></i>
                    <span>Fasilitas</span>
                </a>
            </li>
            <li class="sidebar-item {{ Route::is('staff.faqs.*') ? 'active' : '' }}">
                <a href="{{ route('staff.faqs.index') }}" class='sidebar-link' data-bs-toggle="tooltip" data-bs-placement="right" title="Pertanyaan yang Sering Diajukan">
                    <i class="bi bi-patch-question-fill"></i>
                    <span>FAQ</span>
                </a>
            </li>
            <li class="sidebar-item {{ Route::is('staff.external-links.*') ? 'active' : '' }}">
                <a href="{{ route('staff.external-links.index') }}" class='sidebar-link' data-bs-toggle="tooltip" data-bs-placement="right" title="Tautan Portal Eksternal">
                    <i class="bi bi-link-45deg"></i>
                    <span>Tautan Terkait</span>
                </a>
            </li>

            <li class="sidebar-title">Profil &amp; Pelayanan</li>
            <li class="sidebar-item {{ Route::is('admin.profile-bandara-settings.*') ? 'active' : '' }}">
                <a href="{{ route('admin.profile-bandara-settings.index') }}" class='sidebar-link' data-bs-toggle="tooltip" data-bs-placement="right" title="Teks Profil Bandara">
                    <i class="bi bi-file-earmark-person-fill"></i>
                    <span>Teks Profil</span>
                </a>
            </li>
            <li class="sidebar-item {{ Route::is('staff.service-standards.*') ? 'active' : '' }}">
                <a href="{{ route('staff.service-standards.index') }}" class='sidebar-link' data-bs-toggle="tooltip" data-bs-placement="right" title="Standar &amp; Maklumat Pelayanan">
                    <i class="bi bi-patch-check-fill"></i>
                    <span>Standar Pelayanan</span>
                </a>
            </li>
            <li class="sidebar-item {{ Route::is('admin.skm-settings.*') ? 'active' : '' }}">
                <a href="{{ route('admin.skm-settings.index') }}" class='sidebar-link' data-bs-toggle="tooltip" data-bs-placement="right" title="Tautan Survei Kepuasan Masyarakat">
                    <i class="bi bi-clipboard2-check-fill"></i>
                    <span>Pengaturan SKM</span>
                </a>
            </li>

            <li class="sidebar-title">PPID</li>
            <li class="sidebar-item {{ Route::is('staff.periodic-documents.*') ? 'active' : '' }}">
                <a href="{{ route('staff.periodic-documents.index') }}" class='sidebar-link' data-bs-toggle="tooltip" data-bs-placement="right" title="Informasi Berkala">
                    <i class="bi bi-calendar-date"></i>
                    <span>Informasi Berkala</span>
                </a>
            </li>
            <li class="sidebar-item {{ Route::is('staff.immediate-informations.*') ? 'active' : '' }}">
                <a href="{{ route('staff.immediate-informations.index') }}" class='sidebar-link' data-bs-toggle="tooltip" data-bs-placement="right" title="Informasi Serta Merta">
                    <i class="bi bi-broadcast-pin"></i>
                    <span>Informasi Serta Merta</span>
                </a>
            </li>
            <li class="sidebar-item {{ Route::is('staff.evergreen-informations.*') ? 'active' : '' }}">
                <a href="{{ route('staff.evergreen-informations.index') }}" class='sidebar-link' data-bs-toggle="tooltip" data-bs-placement="right" title="Informasi Setiap Saat">
                    <i class="bi bi-archive-fill"></i>
                    <span>Informasi Setiap Saat</span>
                </a>
            </li>
            <li class="sidebar-item {{ Route::is('staff.information-service-reports.*') ? 'active' : '' }}">
                <a href="{{ route('staff.information-service-reports.index') }}" class='sidebar-link' data-bs-toggle="tooltip" data-bs-placement="right" title="Laporan Layanan Informasi">
                    <i class="bi bi-file-earmark-bar-graph-fill"></i>
                    <span>Laporan Layanan Informasi</span>
                </a>
            </li>
            <li class="sidebar-item {{ Route::is('staff.ppid-regulations.*') ? 'active' : '' }}">
                <a href="{{ route('staff.ppid-regulations.index') }}" class='sidebar-link' data-bs-toggle="tooltip" data-bs-placement="right" title="Regulasi PPID">
                    <i class="bi bi-file-earmark-ruled-fill"></i>
                    <span>Regulasi PPID</span>
                </a>
            </li>
        </ul>
    </div>
</div>