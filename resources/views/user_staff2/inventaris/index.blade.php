@extends('layouts-V2.master-layouts-v2')
@section('title', 'Manajemen Inventaris')

@section('styles_admin')
    <link rel="stylesheet" href="{{ asset('assetsv2/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assetsv2/compiled/css/table-datatable-jquery.css') }}">
@endsection

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Manajemen Inventaris</h3>
                <p class="text-subtitle text-muted">Kelola daftar peralatan inventaris bandara.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <x-breadcrumb2 :items="[
                    ['label' => 'Dashboard', 'url' => route('root')],
                    ['label' => 'Inventaris', 'active' => true]
                ]" />
            </div>
        </div>
    </div>
</div>
<section class="section">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Daftar Inventaris</h5>
            <a href="{{ route('staff.inventories.create') }}" class="btn btn-primary"><i class="bi bi-plus-circle me-2"></i> Tambah Item</a>
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
                            <th>Nama Alat</th>
                            <th>Tanggal Penginputan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($inventories as $item)
                        <tr>
                            <td>
                                <img src="{{ asset('storage/' . $item->photo_path) }}" alt="{{ $item->name }}" width="100" class="rounded">
                            </td>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->input_date->translatedFormat('d F Y') }}</td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('staff.inventories.edit', $item->id) }}" class="btn btn-warning btn-sm"><i class="bi bi-pencil-square"></i></a>
                                    <form action="{{ route('staff.inventories.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus item ini?')">
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
    
@endsection
