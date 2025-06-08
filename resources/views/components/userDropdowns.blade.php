<div class="dropdown">
    <a href="#" data-bs-toggle="dropdown" aria-expanded="false">
        <div class="user-menu d-flex">
            <div class="user-name text-end me-3">
                <h6 class="mb-0 text-gray-600">{{ auth()->user()->name }}</h6>
                <p class="mb-0 text-sm text-gray-600">
                    @admin
                        Admin
                    @elseadmin
                        @staff
                            Staff
                        @elsestaff
                            Pengaju
                        @endstaff
                    @endadmin
                </p>
            </div>
            <div class="user-img d-flex align-items-center">
                <div class="avatar avatar-md">
                    {{-- <img src="{{ asset('assetsv2/compiled/jpg/' . (auth()->user()->is_admin ? '1.jpg' : auth()->user()->is_staff ? '2.jpg' : '3.jpg')) }}"> --}}
                    
                </div>
            </div>
        </div>
    </a>
    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton" style="min-width: 11rem;">
        <li><h6 class="dropdown-header">Halo, {{ auth()->user()->name }}!</h6></li>

        {{-- <li><a class="dropdown-item" href="{{ route((auth()->user()->is_admin ? 'admin' : auth()->user()->is_staff ? 'staff' : 'pengaju') . '.profile') }}"><i class="icon-mid bi bi-person me-2"></i> Profil Saya</a></li> --}}
        <li><a class="dropdown-item" href=""><i class="icon-mid bi bi-person me-2"></i> Profil Saya</a></li>
        <li><hr class="dropdown-divider"></li>
        <li>
            <a class="dropdown-item" href="{{ route('logout') }}"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="icon-mid bi bi-box-arrow-left me-2"></i> Keluar
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form> 
        </li>
    </ul>
</div>