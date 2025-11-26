@extends('layouts-V2.master-layouts-v2')
@section('title', 'Manajemen Program Kerja')

@section('styles_admin')
    <link rel="stylesheet" href="{{ asset('assetsv2/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assetsv2/compiled/css/table-datatable-jquery.css') }}">
    <style> .progress { height: 1.25rem; font-size: 0.8rem; } </style>
@endsection

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Manajemen Program Kerja</h3>
                <p class="text-subtitle text-muted">Kelola dan pantau progres program kerja.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <x-breadcrumb2 :items="[
                    ['label' => 'Dashboard', 'url' => route('root')],
                    ['label' => 'Program Kerja', 'active' => true]
                ]" />
            </div>
        </div>
    </div>
</div>
<section class="section">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Daftar Program Kerja</h5>
            <a href="{{ route('staff.work-programs.create') }}" class="btn btn-primary"><i class="bi bi-plus-circle me-2"></i> Tambah Program</a>
        </div>
        <div class="card-body">
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif
             @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif
            <div class="table-responsive">
                <table class="table table-striped" id="table-data">
                    <thead>
                        <tr>
                            <th style="width: 40%;">Nama Program</th>
                            <th>Kategori</th>
                            <th style="width: 30%;">Progres Penyelesaian</th>
                            <th>Tanggal Dibuat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($programs as $program)
                        <tr>
                            <td>{{ $program->name }}</td>
                            <td>
                                <span class="badge bg-light-secondary text-dark">{{ $program->category }}</span>
                            </td>
                            <td>

                                @php $progress = $program->progress_percentage; @endphp
                                <div class="progress">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated @if($progress == 100) bg-success @else bg-primary @endif"
                                         role="progressbar" style="width: {{ $progress }}%"
                                         aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100">
                                         {{ $progress }}%
                                    </div>
                                </div>
                            </td>
                            <td>{{ $program->created_at->translatedFormat('d M Y') }}</td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('staff.work-programs.show', $program->id) }}" class="btn btn-info btn-sm">Detail</a>
                                    <a href="{{ route('staff.work-programs.edit', $program->id) }}" class="btn btn-warning btn-sm"><i class="bi bi-pencil-square"></i></a>
                                    <form action="{{ route('staff.work-programs.destroy', $program->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus program kerja ini beserta semua tugasnya?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></button>
                                    </form>
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
@endsection

@section('scripts_admin')
    <script src="{{ asset('assetsv2/extensions/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assetsv2/extensions/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assetsv2/extensions/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assetsv2/compiled/js/staff-programKerja.js') }}"></script>
    <script>
        
    </script>
@endsection
