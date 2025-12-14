@extends('layouts-V2.master-layouts-v2')
@section('title', 'Manajemen Izin Kerja')

@section('styles_admin')
    <link rel="stylesheet" href="{{ asset('assetsv2/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assetsv2/compiled/css/table-datatable-jquery.css') }}">
@endsection

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Manajemen Izin Kerja</h3>
                <p class="text-subtitle text-muted">Daftar semua pengajuan izin kerja.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <x-breadcrumb2 :items="[
                    ['label' => 'Menu', 'url' => route('profile')],
                    ['label' => 'Izin Kerja', 'active' => true]
                ]" />
            </div>
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
            <h5 class="card-title mb-0">Daftar Pengajuan</h5>
            @notstaff
            <a href="{{ route('kerja.create') }}" class="btn btn-primary"><i class="bi bi-plus"></i> Buat Pengajuan Baru</a>
            @endnotstaff
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped" id="table-perizinan-kerja">
                    <thead>
                        <tr>
                            <th>No.</th>
                            @staff <th>Nama Pengaju</th> @endstaff
                            <th>Jenis Pekerjaan</th>
                            <th>Lokasi</th>
                            <th>Tanggal Diajukan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($workPermits as $permit)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            @staff <td>{{ $permit->user->name }}</td> @endstaff
                            <td>{{ $permit->work_type }}</td>
                            <td>{{ $permit->location }}</td>
                            <td>{{ $permit->created_at->translatedFormat('d M Y') }}</td>
                            <td>
                                @php
                                    $statusClass = match($permit->submission_status) {
                                        'Disetujui' => 'bg-success',
                                        'Ditolak' => 'bg-danger',
                                        'Revisi Diperlukan' => 'bg-warning',
                                        default => 'bg-info',
                                    };
                                @endphp
                                <span class="badge {{ $statusClass }}">{{ $permit->submission_status }}</span>
                            </td>
                            <td>
                                @notstaff
                                <div class="d-flex">
                                    {{-- Tombol untuk melihat detail pengajuan (termasuk berkas) --}}
                                    <a href="{{ route('kerja.userShow', $permit->id) }}" class="btn btn-sm btn-info text-white me-1">Lihat</a>
                                    
                                    {{-- Tombol Hapus hanya muncul jika statusnya belum final --}}
                                    @if(!in_array($permit->submission_status, ['Disetujui', 'Ditolak']))
                                        <form action="{{ route('kerja.destroy', $permit->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengajuan ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm text-white">Hapus</button>
                                        </form>
                                    @endif

                                </div>
                                    @endnotstaff
                                @staff <a href="{{ route('kerja.show', $permit->id) }}" class="btn btn-sm btn-primary">Detail</a> @endstaff
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
    <script>
        $(document).ready(function() {
            $('#table-perizinan-kerja').DataTable({
                "language": { "url": "{{ asset('assetsv2/extensions/datatables.net-bs5/js/Indonesian.json') }}" }
            });
        });
    </script>
@endsection
