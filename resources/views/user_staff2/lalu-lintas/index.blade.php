@extends('layouts-V2.master-layouts-v2')
@section('title', 'Manajemen Lalu lintas Angkutan Udara')
@section('styles_admin')
    <link rel="stylesheet" href="{{ asset('assetsv2/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assetsv2/compiled/css/table-datatable-jquery.css') }}">
@endsection
@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Manajemen Lalu lintas Angkutan Udara</h3>
                <p class="text-subtitle text-muted">Lihat data Lalu lintas Angkutan Udara.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <x-breadcrumb2 :items="[
                        ['label' => 'Menu', 'url' => route('profile')],
                        ['label' => 'Lalu Lintas', 'active' => true]
                    ]" />
                
            </div>
        </div>
    </div>
    <section class="section">
        <div class="card">
            @if(session('success'))
            <div class="alert alert-primary alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title">Daftar Lalu lintas Angkutan Udara</h5>                                
                <a href="{{ route('laluLintas.create') }}" class="btn btn-primary"><i class="bi bi-plus"></i> Tambah Data </a>
                
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped" id="table-lalulintas">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Tanggal</th>
                                <th>Jenis / Angkutan</th>
                                <th>Datang</th>
                                <th>Berangkat</th>
                                <th>Dibuat</th>
                                <th>Aksi</th>
                                
                                
                            </tr>
                        </thead>
                            @staff
                            <tbody>
                                @forelse ($traffics as $index => $traffic)
                                <tr data-id="{{ $traffic->id }}">
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $traffic->date }}</td>
                                    <td>{{ $traffic->type }}</td>
                                    <td>{{ $traffic->arrival }}</td>
                                    <td>{{ $traffic->departure }}</td>
                                    <td>{{ $traffic->created_at->format('d M Y H:i') }}</td>
                                    <td class="d-flex">
                                            <a href="{{ route('laluLintas.edit', $traffic) }}" class="btn btn-sm btn-warning btn-tooltip text-white m-1" data-bs-toggle="tooltip" title="Edit data"><i class="bi bi-pencil"></i></a>
                                            <button class="btn btn-sm btn-danger btn-hapus btn-tooltip m-1" data-id="{{ $traffic->id }}" data-bs-toggle="tooltip" title="Hapus data"><i class="bi bi-trash"></i></button>

                                    </td>
                                </tr>
                                @empty
                                
                                @endforelse
                            </tbody>
                            @endstaff
                            
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>

@endsection
@section('scripts_admin')
    <script src="{{ asset('assetsv2/extensions/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assetsv2/extensions/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script> 
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="{{ asset('assetsv2/compiled/js/staff-lalulintas.js') }}"></script>
@endsection