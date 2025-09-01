{{-- resources/views/user_staff2/informasi-setiap-saat/index.blade.php --}}
@extends('layouts-V2.master-layouts-v2')
@section('title', 'Manajemen Informasi Setiap Saat')

@section('styles_admin')
    <link rel="stylesheet" href="{{ asset('assetsv2/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assetsv2/compiled/css/table-datatable-jquery.css') }}">
@endsection

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Manajemen Informasi Setiap Saat</h3>
                <p class="text-subtitle text-muted">Kelola dokumen yang selalu tersedia untuk publik.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <x-breadcrumb2 :items="[
                    ['label' => 'Dashboard', 'url' => route('root')],
                    ['label' => 'Informasi Setiap Saat', 'active' => true]
                ]" />
            </div>
        </div>
    </div>
</div>

<section class="section">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Daftar Dokumen</h5>
            <a href="{{ route('staff.evergreen-informations.create') }}" class="btn btn-primary"><i class="bi bi-plus-circle me-2"></i> Tambah Dokumen</a>
        </div>
        <div class="card-body">
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif
            <div class="table-responsive">
                <table class="table table-striped" id="table-data">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Judul/Uraian Informasi</th>
                            <th>Tanggal Publikasi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($informations as $info)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $info->title }}</td>
                            <td>{{ $info->published_date->translatedFormat('d F Y') }}</td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('staff.evergreen-informations.edit', $info->id) }}" class="btn btn-warning btn-sm"><i class="bi bi-pencil-square"></i></a>
                                    <form action="{{ route('staff.evergreen-informations.destroy', $info->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
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
    <script src="{{ asset('assetsv2/compiled/js/staff-informasi-setiapsaat.js') }}"></script>

@endsection