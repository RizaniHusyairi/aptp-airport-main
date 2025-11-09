@extends('layouts-V2.master-layouts-v2')
@section('title', 'Manajemen Informasi Publik')
@section('styles_admin')
    <link rel="stylesheet" href="{{ asset('assetsv2/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assetsv2/compiled/css/table-datatable-jquery.css') }}">
@endsection
@section('content')
<div class="page-heading">
                    <div class="page-title">
                        <div class="row">
                            <div class="col-12 col-md-6 order-md-1 order-last">
                                <h3>Manajemen Informasi Publik</h3>
                                <p class="text-subtitle text-muted">Lihat data pengajuan Informasi Publik.</p>
                            </div>
                            <div class="col-12 col-md-6 order-md-2 order-first">
                                <x-breadcrumb2 :items="[
                                        ['label' => 'Menu', 'url' => route('staff.dashboard.index')],
                                        ['label' => 'Informasi Publik', 'active' => true]
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
                                <h5 class="card-title">Daftar pengajuan Informasi Publik</h5>
                                @notstaff
                                    <a href="{{ route('informasiPublik.create') }}" class="btn btn-primary"><i class="bi bi-plus"></i> Buat Pengajuan Baru</a>
                                @endnotstaff
                                
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped" id="table-publik">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                @staff<th>Nama</th>@endstaff
                                                <th>Rincian Permintaan</th>
                                                <th>Status</th>
                                                <th>Tanggal Dibuat</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                            
                                        <tbody>

                                            @forelse ($publicInformation as $index => $item)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                @staff<td>{{ $item->user->name ?? '-' }}</td>@endstaff
                                                <td>{{ Str::limit($item->rincian_informasi, 40) }}</td>

                                                <td>
                                                <span class="badge {{ $item->status == 'Sudah dibalas' ? 'bg-success' : 'bg-warning' }}">
                                                    {{ $item->status }}
                                                </span>
                                                </td>
                                                 <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d M Y - H:i') }}</td>
                                                <td>
                                                    <div class="d-flex gap-1">
                                                        @staff  
                                                        <a href="{{ route('informasiPublik.show', $item->id) }}" class="btn btn-primary btn-sm">Lihat Detail</a>
                                                        @endstaff
                                                        {{-- Tombol Hapus hanya untuk Pengaju & jika status belum final --}}
                                                        @notstaff
                                                        <a href="{{ route('informasiPublik.userShow', $item->id) }}" class="btn btn-primary btn-sm">Lihat Detail</a>
                                                            @if($item->status == 'Belum dibalas')
                                                                <form action="{{ route('informasiPublik.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus pengajuan ini?')">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                                                </form>
                                                            @endif
                                                        @endnotstaff
                                                    </div>
                                                </td>
                                            </tr>
                                            @empty
                                            

                                            @endforelse
                                        
                                        </tbody>
                                                                                    
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
    <script src="{{ asset('assetsv2/compiled/js/staff-informasi-publik.js') }}"></script>
@endsection