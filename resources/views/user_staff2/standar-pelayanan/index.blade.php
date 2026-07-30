@extends('layouts-V2.master-layouts-v2')
@section('title', 'Manajemen Standar Pelayanan')

@section('styles_admin')
    <link rel="stylesheet" href="{{ asset('assetsv2/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assetsv2/compiled/css/table-datatable-jquery.css') }}">
@endsection

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Manajemen Standar Pelayanan</h3>
                <p class="text-subtitle text-muted">Kelola dokumen Standar Pelayanan, Maklumat Pelayanan, dan Survei Kepuasan Masyarakat.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <x-breadcrumb2 :items="[
                    ['label' => 'Dashboard', 'url' => route('root')],
                    ['label' => 'Standar Pelayanan', 'active' => true]
                ]" />
            </div>
        </div>
    </div>
</div>
<section class="section">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Daftar Dokumen</h5>
            <a href="{{ route('staff.service-standards.create') }}" class="btn btn-primary"><i class="bi bi-plus-circle me-2"></i> Tambah Dokumen</a>
        </div>
        <div class="card-body">
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif
            <div class="table-responsive">
                <table class="table table-striped" id="table-standar-pelayanan">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Jenis</th>
                            <th>Nama Dokumen</th>
                            <th>Nomor</th>
                            <th>Tanggal Terbit</th>
                            <th>Sumber</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($documents as $doc)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $doc->type }}</td>
                            <td>{{ $doc->title }}</td>
                            <td>{{ $doc->document_number ?? '-' }}</td>
                            <td>{{ $doc->published_date->translatedFormat('d M Y') }}</td>
                            <td>
                                @if ($doc->is_uploaded)
                                    <span class="badge bg-light-primary">Unggahan</span>
                                @else
                                    <span class="badge bg-light-info">Tautan</span>
                                @endif
                                <a href="{{ $doc->document_url }}" target="_blank" rel="noopener" class="ms-1 small">Lihat</a>
                            </td>
                            <td>
                                @if ($doc->is_active)
                                    <span class="badge bg-success">Tampil</span>
                                @else
                                    <span class="badge bg-secondary">Disembunyikan</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('staff.service-standards.edit', $doc->id) }}" class="btn btn-warning btn-sm"><i class="bi bi-pencil-square"></i></a>
                                    <form action="{{ route('staff.service-standards.destroy', $doc->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus dokumen ini?')">
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
            $('#table-standar-pelayanan').DataTable({
                responsive: true,
                autoWidth: false,
                // Kolom Sumber & Aksi tidak relevan untuk diurutkan
                columnDefs: [{ orderable: false, targets: [5, 7] }],
                order: [[1, 'asc']],
                pageLength: 10,
                lengthMenu: [5, 10, 25, 50],
                language: {
                    "emptyTable": "Tidak ada data dokumen standar pelayanan",
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
