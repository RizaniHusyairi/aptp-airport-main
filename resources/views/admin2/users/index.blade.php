@extends('layouts-V2.master-layouts-v2')
@section('title', 'Dashboard')
@section('styles_admin')
    <link rel="stylesheet" href="{{ asset('assetsv2/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assetsv2/compiled/css/table-datatable-jquery.css') }}">
@endsection
@section('content')

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Pengguna</h3>
                <p class="text-subtitle text-muted">Daftar pengguna yang mendaftar</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <x-breadcrumb2 :items="[
                        ['label' => 'Menu', 'url' => route('root')],
                        ['label' => 'Pengguna', 'active' => true]
                    ]" />
            </div>
        </div>
    </div>
</div>
<!-- Basic Tables start -->
<section class="section">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">
                Daftar Pengguna
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table" id="table2" style="width: 100%;"> 
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Telepon</th>
                            <th>Role</th> {{-- <<< KOLOM BARU --}}
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $index => $user)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->phone }}</td>
                                <td>
                                
                                    {{-- === DATA ROLE BARU === --}}
                                    @forelse ($user->roles as $role)
                                        <span class="badge bg-light-primary">{{ $role->name }}</span>
                                    @empty
                                        <span class="badge bg-light-secondary">N/A</span>
                                    @endforelse
                                
                                </td>
                                <td>
                                    <span class="badge bg-{{ $user->is_accepted ? 'success' : 'warning' }}">
                                        {{ $user->is_accepted ? 'Terverifikasi' : 'Belum Verifikasi' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex gap-1"> {{-- Tambahkan gap agar tombol tidak nempel --}}
                                        <a href="{{ route('customers.show', $user->id) }}" class="btn btn-primary btn-sm">Lihat</a>
                                        
                                        {{-- === TOMBOL RESET PASSWORD BARU === --}}
                                        <form action="{{ route('customers.resetPassword', $user->id) }}"
                                            method="POST" class="d-inline"
                                            onsubmit="return confirm('Apakah Anda yakin ingin mereset password pengguna ini menjadi Apt123?');">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-warning btn-sm text-white" title="Reset Password ke 12345678">Reset</button>
                                        </form>
                                        {{-- ================================== --}}

                                        <form action="{{ route('customers.destroy', $user->id) }}"
                                            method="POST" class="d-inline"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?');">
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
    <script src="{{ asset('assetsv2/compiled/js/admin-pengguna.js') }}"></script>
@endsection