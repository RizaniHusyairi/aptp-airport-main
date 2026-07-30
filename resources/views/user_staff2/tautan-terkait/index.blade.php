@extends('layouts-V2.master-layouts-v2')
@section('title', 'Manajemen Tautan Terkait')

@section('styles_admin')
    <link rel="stylesheet" href="{{ asset('assetsv2/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assetsv2/compiled/css/table-datatable-jquery.css') }}">
@endsection

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Manajemen Tautan Terkait</h3>
                <p class="text-subtitle text-muted">Kelola tautan portal eksternal yang tampil di beranda, footer, navbar, dan halaman Tautan Terkait.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <x-breadcrumb2 :items="[
                    ['label' => 'Dashboard', 'url' => route('root')],
                    ['label' => 'Tautan Terkait', 'active' => true]
                ]" />
            </div>
        </div>
    </div>
</div>
<section class="section">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Daftar Tautan</h5>
            <a href="{{ route('staff.external-links.create') }}" class="btn btn-primary"><i class="bi bi-plus-circle me-2"></i> Tambah Tautan</a>
        </div>
        <div class="card-body">
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif
            <div class="table-responsive">
                <table class="table table-striped" id="table-tautan-terkait">
                    <thead>
                        <tr>
                            <th>Urutan</th>
                            <th>Tampilan</th>
                            <th>Nama</th>
                            <th>Kelompok</th>
                            <th>URL</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($links as $link)
                        <tr>
                            <td>{{ $link->sort_order }}</td>
                            <td>
                                @if($link->logo_path)
                                    <img src="{{ $link->logo_url }}" alt="Logo {{ $link->name }}" style="max-height: 28px; width: auto;">
                                @else
                                    <i class="bi {{ $link->icon ?: 'bi-box-arrow-up-right' }} fs-4"></i>
                                @endif
                            </td>
                            <td>
                                {{ $link->name }}
                                @if($link->description)
                                    <br><small class="text-muted">{{ Str::limit($link->description, 60) }}</small>
                                @endif
                            </td>
                            <td>{{ $link->group }}</td>
                            <td>
                                <a href="{{ $link->url }}" target="_blank" rel="noopener" class="small">
                                    Kunjungi <i class="bi bi-box-arrow-up-right"></i>
                                </a>
                            </td>
                            <td>
                                @if ($link->is_active)
                                    <span class="badge bg-success">Tampil</span>
                                @else
                                    <span class="badge bg-secondary">Disembunyikan</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('staff.external-links.edit', $link->id) }}" class="btn btn-warning btn-sm"><i class="bi bi-pencil-square"></i></a>
                                    <form action="{{ route('staff.external-links.destroy', $link->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus tautan ini?')">
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
    <script>
        $(document).ready(function () {
            $('#table-tautan-terkait').DataTable({
                responsive: true,
                autoWidth: false,
                // Kolom Tampilan, URL, dan Aksi tidak relevan untuk diurutkan
                columnDefs: [{ orderable: false, targets: [1, 4, 6] }],
                order: [[0, 'asc']],
                pageLength: 10,
                lengthMenu: [5, 10, 25, 50],
                language: {
                    "emptyTable": "Belum ada tautan terkait",
                    "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                    "infoEmpty": "Menampilkan 0 sampai 0 dari 0 entri",
                    "infoFiltered": "(disaring dari _MAX_ total entri)",
                    "lengthMenu": "Tampilkan _MENU_ entri",
                    "loadingRecords": "Memuat...",
                    "processing": "Memproses...",
                    "search": "Cari:",
                    "zeroRecords": "Tidak ada data yang cocok ditemukan",
                    "paginate": {
                        "first": "Pertama",
                        "last": "Terakhir",
                        "next": "Selanjutnya",
                        "previous": "Sebelumnya"
                    }
                }
            });
        });
    </script>
@endsection
