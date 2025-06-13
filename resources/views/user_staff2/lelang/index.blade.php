@extends('layouts-V2.master-layouts-v2')
@section('title', 'Manajemen Lelang/Beauty Contest')
@section('styles_admin')
    <link rel="stylesheet" href="{{ asset('assetsv2/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assetsv2/compiled/css/table-datatable-jquery.css') }}">
@endsection
@section('content')
<div class="page-heading">
                    <div class="page-title">
                        <div class="row">
                            <div class="col-12 col-md-6 order-md-1 order-last">
                                <h3>Manajemen Lelang/Beauty Contest</h3>
                                <p class="text-subtitle text-muted">Lihat data pengajuan Lelang/Beauty Contest.</p>
                            </div>
                            <div class="col-12 col-md-6 order-md-2 order-first">
                                <x-breadcrumb2 :items="[
                                        ['label' => 'Menu', 'url' => route('profile')],
                                        ['label' => 'Lelang/Beauty Contest', 'active' => true]
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
                                <h5 class="card-title">Daftar Pengajuan Lelang/Beauty Contest</h5>
                                @notadmin
                                @notstaff
                                    <a href="{{ route('lelang.create') }}" class="btn btn-primary"><i class="bi bi-plus"></i> Tambah Pengajuan</a>
                                @endnotstaff
                                @endnotadmin
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped" id="table-lelang">
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
                                                    
                                                @forelse ($lelangs as $index => $lelang)
                                                    <tr data-id="{{ $index + 1 }}">
                                                        <td>{{ $lelang->documents ? preg_replace('/^\d+_/', '', basename($lelang->documents)) : '-' }}</td>
                                                        <td>{{ $lelang->created_at->format('d M Y H:i') }}</td>
                                                        <td>
                                                            @php
                                                            $status = $lelang->submission_status;
                                                            $badgeClass = match($status) {
                                                                'disetujui' => 'bg-success',
                                                                'ditolak' => 'bg-danger',
                                                                default => 'bg-info',
                                                            };
                                                            @endphp
                                                            <span class="badge {{ $badgeClass }}">{{ ucfirst($status) }}</span>
                                                        </td>                          
                                                        <td>
                                                            @if ($lelang->documents)
                                                            <div class="row">
                                                                <form class="col">
                                                                    <a href="{{ asset('uploads/documents/lelang/' . basename($lelang->documents)) }}" class="btn btn-sm btn-info text-white btn-tooltip" data-bs-toggle="tooltip" title="Lihat Detail" target="_blank"><i class="bi bi-eye"></i></a>
                                                                </form>
                                                                @if ($lelang->submission_status == 'diajukan')
                                                                <form class="col" action="{{ route('lelang.destroy', $lelang->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus pengajuan ini?')">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-danger btn-sm text-white btn-tooltip" data-bs-toggle="tooltip" title="Hapus Pengajuan"><i class="bi bi-trash"></i></button>
                                                                </form>
                                                                @endif
                                                            </div>
                                                            @else
                                                            <span class="text-muted">Tidak ada berkas</span>
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
                                                    @forelse ($lelangs as $index => $lelang)
                                                    <tr data-id="">
                                                        <td>{{ $lelang->documents ? preg_replace('/^\d+_/', '', basename($lelang->documents)) : '-' }}</td>
                                                        <td>{{ $lelang->created_at->format('d M Y H:i') }}</td>
                                                        <td>
                                                        @php
                                                            $status = $lelang->submission_status;
                                                            $badgeClass = match($status) {
                                                                'disetujui' => 'bg-success',
                                                                'ditolak' => 'bg-danger',
                                                                default => 'bg-info',
                                                            };
                                                        @endphp
                                                        <span class="badge {{ $badgeClass }}">{{ ucfirst($status) }}</span>
                                                        </td>
                                                        <td>
                                                        @foreach ($lelang->users as $user)
                                                            <span class="badge bg-secondary">{{ $user->name }}</span>
                                                        @endforeach
                                                        </td>
                                                        <td>
                                                        <div class="row g-1">
                                                            <div class="col-12 mb-1">
                                                            <a href="{{ route('lelang.show', $lelang->id) }}" class="btn btn-sm btn-primary text-white btn-tooltip" data-bs-toggle="tooltip" title="Lihat Detail">
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
    <script src="{{ asset('assetsv2/compiled/js/staff-lelang.js') }}"></script>
@endsection