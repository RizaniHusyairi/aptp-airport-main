<!-- ========== Left Sidebar Start ========== -->
<div class="vertical-menu">

  <div data-simplebar class="h-100">

    <!--- Sidemenu -->
    <div id="sidebar-menu">
      <!-- Left Menu Start -->
      <ul class="metismenu list-unstyled" id="side-menu">
        @php
          use Illuminate\Support\Str; 
          $currentRoute = Route::currentRouteName();
          $permissionRoutes = [
            'Manajemen Berita' => ['route' => 'berita.staffIndex', 'icon' => 'bx bx-news', 'label' => 'Berita'],
            'Manajemen Tenant' => ['route' => 'tenant.staffIndex', 'icon' => 'bx bx-store', 'label' => 'Tenant'],
            'Manajemen Penyewaan' => ['route' => 'staffSewa.index', 'icon' => 'bx bx-map', 'label' => 'Penyewaan'],
            'Manajemen Perijinan Usaha' => ['route' => 'perijinan.staffIndex', 'icon' => 'bx bx-id-card', 'label' => 'Perijinan Usaha'],
            'Manajemen Pengiklanan' => ['route' => 'pengiklanan.staffIndex', 'icon' => 'bx bx-broadcast', 'label' => 'Pengiklanan'],
            'Manajemen Field Trip' => ['route' => 'fieldtrip.staffIndex', 'icon' => 'bx bx-walk', 'label' => 'Field Trip'],
            'Manajemen Lelang' => ['route' => 'lelang.staffIndex', 'icon' => 'bx bx-walk', 'label' => 'Lelang/Beauty Contest'],
            'Manajemen Laporan Keuangan' => ['route' => 'keuangan.staffIndex', 'icon' => 'bx bx-money', 'label' => 'Laporan Keuangan'],
            'Manajemen Slider' => ['route' => 'slider.staffIndex', 'icon' => 'bx bx-slider', 'label' => 'Slider'],
            'Manajemen Ajuan Informasi Publik' => ['route' => 'informasiPublik.staffIndex', 'icon' => 'bx bx-info-circle', 'label' => 'Informasi Publik'],
            'Manajemen Lalu Lintas Angkutan Udara' => ['route' => 'laluLintas.staffIndex', 'icon' => 'bx bxs-traffic', 'label' => 'Lalu Lintas Angkutan Udara'],
            'Manajemen Pengaduan' => ['route' => '', 'icon' => 'bx bxs-traffic', 'label' => 'Pengaduan'],
          ];
          $userRoutes = [
            'Ajukan Tenant' => ['route' => 'tenant.index', 'icon' => 'bx bx-store', 'label' => 'Ajukan Tenant'],
            'Ajukan Sewa' => ['route' => 'sewa.index', 'icon' => 'bx bx-map', 'label' => 'Ajukan Sewa'],
            'Ajukan Perijinan Usaha' => ['route' => 'perijinan.index', 'icon' => 'bx bx-id-card', 'label' => 'Ajukan Perijinan Usaha'],
            'Ajukan Pengiklanan' => ['route' => 'pengiklanan.index', 'icon' => 'bx bx-broadcast', 'label' => 'Ajukan Pengiklanan'],
            'Ajukan Field Trip' => ['route' => 'fieldtrip.index', 'icon' => 'bx bx-walk', 'label' => 'Ajukan Field Trip'],
            'Ajukan Lelang' => ['route' => 'lelang.index', 'icon' => 'fas fa-gavel', 'label' => 'Ajukan Lelang/Beauty Contest'],
          ];
        @endphp

        @php
          $user = auth()->user();
        @endphp
        <!-- Staff Sidebar -->
        {{-- <span class="badge bg-info">user: {{ $user }}</span> --}}
        @foreach ($permissionRoutes as $permissionName => $data)

          @if ($user->hasPermission($permissionName))
            
            <li>
              <a href="{{ route($data['route']) }}" class="waves-effect d-flex align-items-center">
                <i class="{{ $data['icon'] }}"></i>
                <span>{{ $data['label'] }}</span>
              </a>
            </li>
          @endif
        @endforeach
        <!-- User Sidebar -->
        @foreach ($userRoutes as $permissionName => $data)
          @notadmin
            @notstaff
              <li class="{{ Str::startsWith($currentRoute, Str::before($data['route'], '.')) ? 'mm-active' : '' }}">
                <a href="{{ route($data['route']) }}" class="waves-effect">
                  <i class="{{ $data['icon'] }}"></i>
                  <span>{{ $data['label'] }}</span>
                </a>
              </li>
            @endnotstaff
          @endnotadmin
        @endforeach
        <!-- Admin Sidebar -->
        @admin
          <li>
            <a href="{{ route('root') }}" class="waves-effect">
              <i class="bx bx-home-circle"></i>
              <span key="t-contact">@lang('sidebar.dashboard')</span>
            </a>
          </li>
          <li class="{{ request()->routeIs('customers.*') ? 'mm-active' : '' }}">
            <a href="{{ route('customers.index') }}" class="waves-effect">
              <i class='bx bx-user'></i>
              <span key="t-contact">Pengguna</span>
            </a>
          </li>
          <li class="{{ request()->routeIs('roles.*') ? 'mm-active' : '' }}">
            <a href="{{ route('roles.index') }}" class="waves-effect">
              <i class='bx bx-user'></i>
              <span key="t-contact">Roles</span>
            </a>
          </li>

        @else
        @endadmin

      </ul>
    </div>
    <!-- Sidebar -->
  </div>
</div>
<!-- Left Sidebar End -->

@push('scripts')
  <script>
    $(document).ready(function() {
      getOrderStatusCount()
    });

    const getOrderStatusCount = () => {
      $.ajax({
        url: "{{ route('ticketStatusCount') }}",
        type: "GET",
        dataType: "json",
        success: function(data) {
          // remove d-none class from the badge
          $('.ticket-badge').removeClass('d-none');

          $("#totalTickets").html(data.totalTickets);
          $("#pendingTickets").html(data.pendingTickets);
          $("#approvedTickets").html(data.approvedTickets);
          $("#canceledTickets").html(data.canceledTickets);
        }
      });
    }

  </script>
@endpush
