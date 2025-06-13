@php
    
use Illuminate\Support\Str; 
$currentRoute = Route::currentRouteName();
$permissionRoutes = [
'Manajemen Berita' => ['route' => 'berita.staffIndex', 'icon' => 'bi bi-newspaper', 'label' => 'Berita'],
'Manajemen Tenant' => ['route' => 'tenant.staffIndex', 'icon' => 'bi bi-shop', 'label' => 'Tenant'],
'Manajemen Sewa' => ['route' => 'staffSewa.index', 'icon' => 'bi bi-building', 'label' => 'Penyewaan'],
'Manajemen Perijinan Usaha' => ['route' => 'perijinan.staffIndex', 'icon' => 'bi bi-file-earmark-check', 'label' => 'Perijinan Usaha'],
'Manajemen Pengiklanan' => ['route' => 'pengiklanan.staffIndex', 'icon' => 'bi bi-megaphone', 'label' => 'Pengiklanan'],
'Manajemen Field Trip' => ['route' => 'fieldtrip.staffIndex', 'icon' => 'bi bi-geo-alt', 'label' => 'Field Trip'],
'Manajemen Lelang' => ['route' => 'lelang.staffIndex', 'icon' => 'bi bi-hammer', 'label' => 'Lelang/Beauty Contest'],
'Manajemen Slot Charter' => ['route' => 'slot.staffIndex', 'icon' => 'bi bi-clock', 'label' => 'Slot Charter'],
'Manajemen Laporan Keuangan' => ['route' => 'keuangan.staffIndex', 'icon' => 'bi bi-graph-up', 'label' => 'Laporan Keuangan'],
'Manajemen Slider' => ['route' => 'slider.staffIndex', 'icon' => 'bi bi-image', 'label' => 'Slider'],
'Manajemen Ajuan Informasi Publik' => ['route' => 'informasiPublik.staffIndex', 'icon' => 'bi bi-info-circle', 'label' => 'Informasi Publik'],
'Manajemen Lalu Lintas Angkutan Udara' => ['route' => 'laluLintas.staffIndex', 'icon' => 'bi bi-airplane', 'label' => 'Lalu Lintas Angkutan Udara'],
'Manajemen Pengaduan' => ['route' => 'pengaduan.staffIndex', 'icon' => 'bi bi-exclamation-triangle', 'label' => 'Pengaduan'],
'Manajemen Regulasi' => ['route' => 'letters.staff.index', 'icon' => 'bi bi-book', 'label' => 'Regulasi'],
          ];
@endphp
@php
          $user = auth()->user();
@endphp
<div class="sidebar-wrapper active">
    <div class="sidebar-header position-relative">
        <div class="d-flex justify-content-between align-items-center">
            <div class="logo">
                <a class="logo-apt" href="{{ route('home') }}"><img src="{{ asset('assetsv2/image/logo/logo-apt.svg') }}" alt="Logo"></a>
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
            @foreach ($permissionRoutes as $permissionName => $data)
                @if ($user->hasPermission($permissionName))
                <li class="sidebar-item {{ Route::is($data['route']) ? 'active' : '' }}">
                <a href="{{ route($data['route']) }}" class='sidebar-link' data-bs-toggle="tooltip" data-bs-placement="right" title="Berita">
                        <i class="{{ $data['icon'] }}"></i>
                        <span>{{ $data['label'] }}</span>
                    </a>
                </li>
                
                @endif
            @endforeach
            
        </ul>
    </div>
</div>