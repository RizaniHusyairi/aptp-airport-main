@extends('layouts-V2.master-layouts-v2')
@section('title', 'Manajemen Fasilitas')

@section('styles_admin')
    <link rel="stylesheet" href="{{ asset('assetsv2/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assetsv2/compiled/css/table-datatable-jquery.css') }}">
@endsection

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Manajemen Fasilitas</h3>
                <p class="text-subtitle text-muted">Kelola semua fasilitas yang ditampilkan di website.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <x-breadcrumb2 :items="[
                    ['label' => 'Dashboard', 'url' => route('root')],
                    ['label' => 'Fasilitas', 'active' => true]
                ]" />
            </div>
        </div>
    </div>
</div>
<section class="section">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Daftar Fasilitas</h5>
            <a href="{{ route('admin.facilities.create') }}" class="btn btn-primary"><i class="bi bi-plus"></i> Tambah Fasilitas</a>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <div class="table-responsive">
                <table class="table table-striped" id="table1">
                    <thead>
                        <tr>
                            <th>Gambar</th>
                            <th>Nama Fasilitas</th>
                            <th>Kategori</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($facilities as $facility)
                        <tr>
                            <td>
                                <img src="{{asset('uploads/' . $facility->image_path)}}" alt="{{ $facility->name }}" width="100" class="rounded">
                            </td>
                            <td>{{ $facility->name }}</td>
                            <td><span class="badge bg-info text-capitalize">{{ $facility->category }}</span></td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('admin.facilities.edit', $facility) }}" class="btn btn-sm btn-warning">Edit</a>
                                    <form action="{{ route('admin.facilities.destroy', $facility) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus fasilitas ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center">Belum ada data fasilitas.</td>
                        </tr>
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
    <script>
        $(document).ready(function() {
            $('#table1').DataTable({
                 "language": { "url": "{{ asset('assetsv2/extensions/datatables.net-bs5/js/Indonesian.json') }}" }
            });
        });
    </script>
@endsection

