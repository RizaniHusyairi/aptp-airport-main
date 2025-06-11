@extends('layouts-V2.master-layouts-v2')
@section('title', 'Dashboard')
@section('styles_admin')
    <link rel="stylesheet" href="{{ asset('assetsv2/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assetsv2/compiled/css/table-datatable-jquery.css') }}">
@endsection

@php
    $colorMap = [
                    'Manajemen Tenant' => 'primary',
                    'Manajemen Sewa Lahan' => 'success',
                    'Manajemen Perijinan Usaha' => 'warning',
                    'Manajemen Pengiklanan' => 'info',
                    'Manajemen Field Trip' => 'secondary',
                    'Manajemen Berita' => 'danger',
                    'Manajemen Laporan Keuangan' => 'dark',
                    'Manajemen Slider' => 'light',
                    'Manajemen Ajuan Informasi Publik' => 'primary',
                    'Manajemen Lalu Lintas Angkutan Udara' => 'danger',
                    'Manajemen Regulasi' => 'success',
                    'Manajemen Lelang' => 'info',
                    'Manajemen Pengaduan' => 'secondary',
                    'Manajemen Slot Charter' => 'warning',
                ];
@endphp

@section('content')

<div class="page-heading">
                <div class="page-title">
                    <div class="row">
                        <div class="col-12 col-md-6 order-md-1 order-last">
                            <h3>Detail Pengguna</h3>
                            <p class="text-subtitle text-muted">Detail informasi dari pengguna</p>
                        </div>
                        <div class="col-12 col-md-6 order-md-2 order-first">
                            <x-breadcrumb2 :items="[
                                ['label' => 'Menu', 'url' => route('root')],
                                ['label' => 'Pengguna', 'url' => route('customers.index')],
                                ['label' => 'Detail Pengguna', 'active' => true]
                            ]" />
                        </div>
                    </div>
                </div>
            </div>
            <!-- Minimal jQuery Datatable end -->
            <section id="content-types">
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-content">
                                <div class="card-body">
                                    <h4 class="card-title mb-4">Informasi Pengguna</h4>
                                    <div class="card-text row">
                                        <div class="col-md-6">
                                            <h5>Nama</h5>
                                            <p> {{ $user->name }}</p>

                                        </div>
                                        <div class="col-md-6">
                                            <h5>Email</h5>
                                            <p> {{ $user->email }}</p>

                                        </div>
                                        <div class="col-md-6">
                                            <h5>Telepon</h5>
                                            <p> {{ $user->phone }}</p>

                                        </div>
                                        <div class="col-md-6">
                                            <h5>Alamat</h5>
                                            <p> {{ $user->address }}</p>

                                        </div>
                                        {{-- <div class="col-md-6">
                                            <h5>Jumlah tiket</h5>
                                            <p> <span class="badge bg-info">0</span></p>

                                        </div> --}}
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer d-flex justify-content-between">
                                <div class="col-lg-6">

                                    <a href="{{ route('customers.index') }}" class="btn btn-secondary ">Kembali</a>
                                </div>
                                <div class="col-lg-6">
                                    @if ($user->is_accepted)
                                    {{-- Jika user sudah terverifikasi --}}
                                    
                                    {{-- Tombol Batalkan Verifikasi --}}
                                    <form action="{{ route('customers.unverify', $user->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-warning text-white">Batalkan Verifikasi Akun</button>
                                    </form>
    
                                    {{-- Toggle Staff --}}
                                    @if ($user->is_staff)
                                    <form action="{{ route('customers.toggleStaff', $user->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-danger">Jadikan Sebagai Pengguna</button>
                                    </form>
                                    @else
                                    <form action="{{ route('customers.toggleStaff', $user->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-primary">Jadikan Sebagai Staff</button>
                                    </form>
                                    @endif
    
                                @else
                                    {{-- Jika user belum terverifikasi --}}
                                    <form action="{{ route('customers.verify', $user->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-success">Verifikasi User</button>
                                    </form>
                                @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>

            </section>
            {{-- role management --}}
            @if ($user->is_staff)
                
            <section class="section">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title">Daftar Roles</h5>
                    <a href="{{ route("roles.create") }}" class="btn btn-success">Tambah Role</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table" id="table-role">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama</th>
                                    <th>Permissions</th>
                                    <th>Dibuat</th>
                                    <th>Aktifkan Role</th>
                                </tr>
                            </thead>
                            <tbody>
                                        @foreach ($roles as $index => $role)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $role->name }}</td>
                                                <td>
                                                    @foreach ($role->permissions as $permission)
                                                        <span class="badge bg-{{ $colorMap[$permission->permission_name] ?? 'secondary' }} me-1">
                                                            {{ $permission->permission_name }}
                                                        </span>
                                                    @endforeach
                                                </td>
                                                <td>{{ $role->created_at->format('d-m-Y') }}</td>
                                                <td>
                                                    <form action="{{ route('customers.toggle-role', $user->id) }}" method="POST">
                                                    @csrf
                                                        <input type="hidden" name="role_id" value="{{ $role->id }}">
                                                        <div class="form-check form-switch">
                                                            <input 
                                                                class="form-check-input" 
                                                                type="checkbox" 
                                                                onchange="this.form.submit()"
                                                                {{ in_array($role->id, $userRoles) ? 'checked' : '' }}
                                                            >
                                                        </div>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                        </table>
                    </div>
                </div>
            </div>
            </section>
            @endif

            

@endsection
@section('scripts_admin')
    <script src="{{ asset('assetsv2/extensions/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assetsv2/extensions/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assetsv2/extensions/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assetsv2/static/js/pages/admin-pengguna.js') }}"></script>
@endsection