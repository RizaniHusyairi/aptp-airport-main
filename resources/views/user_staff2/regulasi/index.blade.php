@extends('layouts-V2.master-layouts-v2')
@section('title', 'Manajemen Regulasi')
@section('styles_admin')
    <link rel="stylesheet" href="{{ asset('assetsv2/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assetsv2/compiled/css/table-datatable-jquery.css') }}">
@endsection
@section('content')
<div class="page-heading">
                    <div class="page-title">
                        <div class="row">
                            <div class="col-12 col-md-6 order-md-1 order-last">
                                <h3>Manajemen Regulasi</h3>
                                <p class="text-subtitle text-muted">Lihat data Regulasi.</p>
                            </div>
                            <div class="col-12 col-md-6 order-md-2 order-first">
                                <x-breadcrumb2 :items="[
                                        ['label' => 'Menu', 'url' => route('profile')],
                                        ['label' => 'Regulasi', 'active' => true]
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
                                <h5 class="card-title">Daftar Regulasi</h5>                                
                                <a href="{{ route('letters.staff.create') }}" class="btn btn-primary"><i class="bi bi-plus"></i> Tambah Surat </a>
                                
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped" id="table-regulasi">
                                        <thead>
                                            <tr>
                                                <th>No Surat</th>
                                                <th>Judul</th>
                                                <th>Jenis</th>
                                                <th>Tanggal Terbit</th>
                                                <th>File</th>
                                                <th>Aksi</th>
                                                
                                            </tr>
                                        </thead>
                                        
                                            @staff
                                        <tbody>
                                            @forelse ($letters as $letter)
                                                    <tr data-id="{{ $letter->id }}">
                                                        <td>{{ $letter->number }}</td>
                                                        <td>{{ $letter->title }}</td>
                                                        <td>{{ $letter->type == 'edaran' ? 'Surat Edaran' : 'Surat Utusan' }}</td>
                                                        <td>{{ \Carbon\Carbon::parse($letter->issue_date)->translatedFormat('d M Y') }}</td>
                                                        <td><a href="{{ asset('uploads/uploads/letters/' . basename($letter->file_path)) }}" target="_blank" class="badge bg-primary">Lihat</a></td>
                                                        <td class="d-flex">
                                                            <a href="{{ route('letters.staff.edit', $letter) }}" class="btn btn-sm btn-warning btn-tooltip text-white m-1" data-bs-toggle="tooltip" title="Edit Surat"><i class="bi bi-pencil"></i></a>
                                                            <button class="btn btn-sm btn-danger btn-hapus btn-tooltip m-1" data-id="{{ $letter->id }}" data-bs-toggle="tooltip" title="Hapus Surat"><i class="bi bi-trash"></i></button>

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

    <script src="{{ asset('assetsv2/compiled/js/staff-regulasi.js') }}"></script>
@endsection