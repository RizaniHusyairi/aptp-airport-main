@extends('layouts-V2.master-layouts-v2')
@section('title', 'Manajemen FAQ')

@section('styles_admin')
    <link rel="stylesheet" href="{{ asset('assetsv2/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assetsv2/compiled/css/table-datatable-jquery.css') }}">
@endsection

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Manajemen FAQ</h3>
                <p class="text-subtitle text-muted">Kelola pertanyaan yang sering diajukan, tampil di halaman FAQ, beranda, dan halaman layanan.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <x-breadcrumb2 :items="[
                    ['label' => 'Dashboard', 'url' => route('root')],
                    ['label' => 'FAQ', 'active' => true]
                ]" />
            </div>
        </div>
    </div>
</div>
<section class="section">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Daftar Pertanyaan</h5>
            <a href="{{ route('staff.faqs.create') }}" class="btn btn-primary"><i class="bi bi-plus-circle me-2"></i> Tambah Pertanyaan</a>
        </div>
        <div class="card-body">
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif
            <div class="table-responsive">
                <table class="table table-striped" id="table-faq">
                    <thead>
                        <tr>
                            <th>Urutan</th>
                            <th>Pertanyaan</th>
                            <th>Kategori</th>
                            <th>Layanan Terkait</th>
                            <th>Beranda</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($faqs as $faq)
                        <tr>
                            <td>{{ $faq->sort_order }}</td>
                            <td>{{ Str::limit($faq->question, 70) }}</td>
                            <td>{{ $faq->category }}</td>
                            <td>{{ $faq->service->name ?? '-' }}</td>
                            <td>
                                @if ($faq->is_featured)
                                    <span class="badge bg-light-primary">Unggulan</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if ($faq->is_active)
                                    <span class="badge bg-success">Tampil</span>
                                @else
                                    <span class="badge bg-secondary">Disembunyikan</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('staff.faqs.edit', $faq->id) }}" class="btn btn-warning btn-sm"><i class="bi bi-pencil-square"></i></a>
                                    <form action="{{ route('staff.faqs.destroy', $faq->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pertanyaan ini?')">
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
            $('#table-faq').DataTable({
                responsive: true,
                autoWidth: false,
                columnDefs: [{ orderable: false, targets: [6] }],
                order: [[2, 'asc'], [0, 'asc']],
                pageLength: 25,
                lengthMenu: [10, 25, 50, 100],
                language: {
                    "emptyTable": "Belum ada pertanyaan FAQ",
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
