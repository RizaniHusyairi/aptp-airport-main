@extends('layouts-V2.master-layouts-v2')
@section('title', 'Manajemen Pariwisata')

@section('styles_admin')
    <link rel="stylesheet" href="{{ asset('assetsv2/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assetsv2/compiled/css/table-datatable-jquery.css') }}">
@endsection

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Manajemen Pariwisata</h3>
                <p class="text-subtitle text-muted">Kelola semua destinasi wisata yang ditampilkan di website.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <x-breadcrumb2 :items="[
                    ['label' => 'Dashboard', 'url' => route('root')],
                    ['label' => 'Pariwisata', 'active' => true]
                ]" />
            </div>
        </div>
    </div>
</div>
<section class="section">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Daftar Destinasi Wisata</h5>
            <a href="{{ route('admin.tourism.create') }}" class="btn btn-primary"><i class="bi bi-plus"></i> Tambah Destinasi</a>
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
                            <th>Nama Destinasi</th>
                            <th>Kategori</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($tourisms as $tourism)
                        <tr>
                            <td>
                                <img src="{{ Storage::url($tourism->cover_image) }}" alt="{{ $tourism->name }}" width="100" class="rounded">
                            </td>
                            <td>{{ $tourism->name }}</td>
                            <td>{{ $tourism->category }}</td>
                            <td>
                                <span class="badge bg-{{ $tourism->status == 'published' ? 'success' : 'warning' }}">{{ ucfirst($tourism->status) }}</span>
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('admin.tourism.edit', $tourism) }}" class="btn btn-sm btn-warning">Edit</a>
                                    <form action="{{ route('admin.tourism.destroy', $tourism) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus destinasi ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">Belum ada data destinasi wisata.</td>
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
