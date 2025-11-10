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
                    ['label' => 'Dashboard', 'url' => route('staff.dashboard.index')],
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
                            <td>
                                {{-- <img src="{{ asset('uploads/' . $item->photo_path) }}" alt="{{ $item->name }}" width="100" class="rounded"> --}}
                                <a href="{{ asset('uploads/' . $item->photo_path) }}" class="glightbox" data-gallery="inventaris-gallery">
                                    <img src="{{ asset('uploads/' . $item->photo_path) }}" alt="{{ $item->name }}" width="100" class="rounded img-thumbnail">
                                </a>
                            </td>
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
                                    {{-- === PERUBAHAN DI SINI: Tombol Detail === --}}
                                    <a href="{{ route('staff.inventories.show', $item->id) }}" class="btn btn-primary btn-sm">Detail</a>
                                    
                                    <a href="{{ route('staff.inventories.edit', $item->id) }}" class="btn btn-warning btn-sm"><i class="bi bi-pencil-square"></i></a>
                                    <form action="{{ route('staff.inventories.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin?')">
                                        @csrf @method('DELETE')
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
    <script src="{{ asset('assetsv2/compiled/js/staff-inventaris.js') }}"></script>
    <script>
        $(document).ready(function() {
            
            // Inisialisasi GLightbox
            const lightbox = GLightbox({
                selector: '.glightbox' // Menargetkan semua link dengan class 'glightbox'
            });

            // Logika untuk modal status (tidak berubah)
            $('.status-select').on('change', function() {
                const targetContainer = $(this).data('target');
                if ($(this).val() === 'Pemeliharaan') {
                    $(targetContainer).slideDown();
                    $(targetContainer).find('input').prop('required', true);
                } else {
                    $(targetContainer).slideUp();
                    $(targetContainer).find('input').prop('required', false);
                }
            });
            
             // Inisialisasi tooltip Bootstrap (jika menggunakan)
             var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
             var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                 return new bootstrap.Tooltip(tooltipTriggerEl)
             })
        });
    </script>

    
@endsection
