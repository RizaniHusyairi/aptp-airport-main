@extends('layouts-V2.master-layouts-v2')
@section('title', 'Manajemen Perizinan Usaha')
@section('styles_admin')
    <link rel="stylesheet" href="{{ asset('assetsv2/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assetsv2/compiled/css/table-datatable-jquery.css') }}">
@endsection
@section('content')
<div class="page-heading">
                    <div class="page-title">
                        <div class="row">
                            <div class="col-12 col-md-6 order-md-1 order-last">
                                <h3>Manajemen Perizinan Usaha</h3>
                                <p class="text-subtitle text-muted">Lihat data pengajuan Perizinan Usaha.</p>
                            </div>
                            <div class="col-12 col-md-6 order-md-2 order-first">
                                <x-breadcrumb2 :items="[
                                        ['label' => 'Menu', 'url' => route('profile')],
                                        ['label' => 'Perizinan Usaha', 'active' => true]
                                    ]" />
                                
                            </div>
                        </div>
                    </div>
                    <section class="section">
                        <div class="card">
                            @if(session('success'))
                            <div class="alert alert-primary alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                            @endif
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="card-title">Daftar Pengajuan Perizinan Usaha</h5>
                                @notadmin
                                @notstaff
                                    <a href="{{ route('perijinan.create') }}" class="btn btn-primary"><i class="bi bi-plus"></i> Tambah Pengajuan</a>
                                @endnotstaff
                                @endnotadmin
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped" id="table-license">
                                        <thead>
                                            <tr>
                                                <th>Nama File Pengajuan</th>
                                                <th>Dibuat</th>
                                                <th>Status</th>
                                                @staff
                                                <th>Pemilik</th>
                                                @endstaff
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        @notadmin
                                        @notstaff
                                        <tbody>
                                                    
                                                @forelse ($licenses as $index => $license)
                                                    <tr data-id="{{ $index + 1 }}">
                                                        <td>{{ $license->documents ? preg_replace('/^\d+_/', '', basename($license->documents)) : '-' }}</td>
                                                        <td>{{ $license->created_at->format('d M Y H:i') }}</td>
                                                        <td>
                                                            @php
                                                            $status = $license->submission_status;
                                                            $badgeClass = match($status) {
                                                                 'Disetujui' => 'bg-success',
                                                                'Ditolak' => 'bg-danger',
                                                                'Revisi Diperlukan' => 'bg-warning',
                                                                default => 'bg-info',
                                                            };
                                                            @endphp
                                                            <span class="badge {{ $badgeClass }}">{{ ucfirst($status) }}</span>
                                                        </td>                          
                                                        <td>
                                                            @if ($license->documents)
                                                            <div class="d-flex">
                                                                <a href="{{ route('perijinan.userShow', $license->id) }}" class="me-1 btn btn-sm btn-info text-white btn-tooltip" data-bs-toggle="tooltip" title="Lihat Detail"><i class="bi bi-eye"></i></a>
                                                                                                                                
                                                                @if ($license->submission_status == 'Diajukan')
                                                                <form action="{{ route('perijinan.destroy', $license->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus pengajuan ini?')">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-danger btn-sm text-white btn-tooltip" data-bs-toggle="tooltip" title="Hapus Pengajuan"><i class="bi bi-trash"></i></button>
                                                                </form>
                                                                @endif
                                                            </div>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    @empty
                                                    
                                                @endforelse
                                        </tbody>
                                                @endnotstaff
                                            @endnotadmin
                                            @staff
                                        <tbody>
                                                    @forelse ($licenses as $index => $license)
                                                    <tr data-id="">
                                                        <td>{{ $license->documents ? preg_replace('/^\d+_/', '', basename($license->documents)) : '-' }}</td>
                                                        <td>{{ $license->created_at->format('d M Y H:i') }}</td>
                                                        <td>
                                                        @php
                                                            $status = $license->submission_status;
                                                            $badgeClass = match($status) {
                                                                 'Disetujui' => 'bg-success',
                                                                'Ditolak' => 'bg-danger',
                                                                'Revisi Diperlukan' => 'bg-warning',
                                                                default => 'bg-info',
                                                            };
                                                        @endphp
                                                        <span class="badge {{ $badgeClass }}">{{ ucfirst($status) }}</span>
                                                        </td>
                                                        <td>
                                                        @foreach ($license->users as $user)
                                                            <span class="badge bg-secondary">{{ $user->name }}</span>
                                                        @endforeach
                                                        </td>
                                                        <td>
                                                        <div class="row g-1">
                                                            <div class="col-12 mb-1">
                                                            <a href="{{ route('perijinan.show', $license->id) }}" class="btn btn-sm btn-primary text-white btn-tooltip" data-bs-toggle="tooltip" title="Lihat Detail">
                                                                <i class="bi bi-eye"></i>
                                                            </a>
                                                            </div>
                                                        </div>
                                                        </td>
                                                    </tr>
                                                    @empty
                                                    
                                                    @endforelse
                                                
                                                </tbody>
                                                @endstaff
                                            
                                    </table>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>

@endsection
@section('scripts_admin')
    <script src="{{ asset('assetsv2/extensions/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assetsv2/extensions/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>    
    <script src="{{ asset('assetsv2/compiled/js/staff-license.js') }}"></script>
@endsection