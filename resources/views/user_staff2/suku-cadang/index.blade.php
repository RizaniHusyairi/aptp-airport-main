@extends('layouts-V2.master-layouts-v2')
@section('title', 'Manajemen Suku Cadang')

@section('styles_admin')
    <link rel="stylesheet" href="{{ asset('assetsv2/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assetsv2/compiled/css/table-datatable-jquery.css') }}">
@endsection

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Manajemen Suku Cadang</h3>
                <p class="text-subtitle text-muted">Kelola daftar suku cadang yang tersedia.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <x-breadcrumb2 :items="[
                    ['label' => 'Dashboard', 'url' => route('root')],
                    ['label' => 'Suku Cadang', 'active' => true]
                ]" />
            </div>
        </div>
    </div>
</div>
<section class="section">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Daftar Suku Cadang</h5>
            <a href="{{ route('staff.spare-parts.create') }}" class="btn btn-primary"><i class="bi bi-plus-circle me-2"></i> Tambah Suku Cadang</a>
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
                            <th>Foto</th>
                            <th>Nama Suku Cadang</th>
                            <th>Stok</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($spareParts as $part)
                        <tr>
                            <td>
                                @if($part->photo_path)
                                    <img src="{{ asset('uploads/' . $part->photo_path) }}" alt="{{ $part->name }}" width="100" class="rounded">
                                @else
                                    <img src="https://placehold.co/100x100/e2e8f0/adb5bd?text=No+Image" alt="No Image" width="100" class="rounded">
                                @endif
                            </td>
                            <td>{{ $part->name }}</td>
                            <td>{{ $part->stock }}</td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('staff.spare-parts.edit', $part->id) }}" class="btn btn-warning btn-sm"><i class="bi bi-pencil-square"></i></a>
                                    <form action="{{ route('staff.spare-parts.destroy', $part->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus suku cadang ini?')">
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
    <script src="{{ asset('assetsv2/compiled/js/staff-sukuCadang.js') }}"></script>
    
    <script src="{{ asset('assetsv2/extensions/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
    
@endsection
