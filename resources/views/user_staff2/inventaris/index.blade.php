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
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            <div class="table-responsive">
                <table class="table table-striped" id="table-data">
                    <thead>
                        <tr>
                            <th>Foto</th>
                            <th>Nama Alat</th>
                            <th>Kondisi</th>
                            <th>Laporan</th>
                            <th>Tanggal Input</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($inventories as $item)
                        <tr>
                            <td><img src="{{ asset('uploads/' . $item->photo_path) }}" alt="{{ $item->name }}" width="100" class="rounded"></td>
                            <td>{{ $item->name }}</td>
                            <td>
                                @php
                                    $statusClass = $item->status == 'Baik' ? 'bg-success' : 'bg-warning';
                                @endphp
                                <span class="badge {{ $statusClass }}">{{ $item->status }}</span>
                            </td>
                            <td>
                                @if($item->maintenance_report_link)
                                    <a href="{{ $item->maintenance_report_link }}" target="_blank" class="btn btn-sm btn-outline-info">Lihat Laporan</a>
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{ $item->input_date->translatedFormat('d F Y') }}</td>
                            <td>
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#statusModal-{{ $item->id }}">
                                        Ubah Status
                                    </button>
                                    <a href="{{ route('staff.inventories.edit', $item->id) }}" class="btn btn-warning btn-sm"><i class="bi bi-pencil-square"></i></a>
                                    <form action="{{ route('staff.inventories.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin?')">
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

{{-- MODAL UNTUK SETIAP ITEM INVENTARIS --}}
@foreach ($inventories as $item)
<div class="modal fade" id="statusModal-{{ $item->id }}" tabindex="-1" aria-labelledby="statusModalLabel-{{ $item->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <form class="modal-content" method="POST" action="{{ route('staff.inventories.updateStatus', $item->id) }}">
            @csrf
            @method('PATCH')
            <div class="modal-header">
                <h5 class="modal-title" id="statusModalLabel-{{ $item->id }}">Ubah Status: {{ $item->name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="status-{{ $item->id }}" class="form-label">Status Kondisi</label>
                    <select class="form-select status-select" id="status-{{ $item->id }}" name="status" data-target="#report-link-container-{{ $item->id }}">
                        <option value="Baik" @selected($item->status == 'Baik')>Baik</option>
                        <option value="Pemeliharaan" @selected($item->status == 'Pemeliharaan')>Pemeliharaan</option>
                    </select>
                </div>
                <div class="mb-3 report-link-container" id="report-link-container-{{ $item->id }}" style="display: {{ $item->status == 'Pemeliharaan' ? 'block' : 'none' }};">
                    <label for="maintenance_report_link-{{ $item->id }}" class="form-label">Link Laporan Pemeliharaan (Google Drive) <span class="text-danger">*</span></label>
                    <input type="url" class="form-control" id="maintenance_report_link-{{ $item->id }}" name="maintenance_report_link" value="{{ old('maintenance_report_link', $item->maintenance_report_link) }}" placeholder="https://...">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Status</button>
            </div>
        </form>
    </div>
</div>
@endforeach

@endsection

@section('scripts_admin')
    <script src="{{ asset('assetsv2/extensions/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assetsv2/extensions/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assetsv2/extensions/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assetsv2/compiled/js/staff-inventaris.js') }}"></script>

    
@endsection
