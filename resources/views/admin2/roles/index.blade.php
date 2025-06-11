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
                <h3>Roles</h3>
                <p class="text-subtitle text-muted">Daftar roles yang tersedia</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <x-breadcrumb2 :items="[
                        ['label' => 'Menu', 'url' => route('root')],
                        ['label' => 'Roles', 'active' => true]
                    ]" />
                
            </div>
        </div>
    </div>
</div>
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
                            <th>Aksi</th>
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
                                            <div class="d-flex">
                                                <a href="{{ route('roles.edit', $role->id) }}"
                                                    class="btn btn-primary btn-sm me-1">Edit</a>
                                                <form action="{{ route('roles.destroy', $role->id) }}"
                                                    method="POST" class="d-inline"
                                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus role ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts_admin')
<script src="{{ asset('assetsv2/extensions/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assetsv2/extensions/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assetsv2/extensions/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assetsv2/static/js/pages/admin-roles.js') }}"></script>
@endsection