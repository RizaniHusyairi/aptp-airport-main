@extends('layouts-V2.master-layouts-v2')
@section('title', 'Manajemen Data LLAU Harian')

@section('styles_admin')
    <link rel="stylesheet" href="{{ asset('assetsv2/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assetsv2/compiled/css/table-datatable-jquery.css') }}">
@endsection

@section('content')
<div class="page-heading">
    <div class="page-title">
        {{-- ... (Judul Halaman Tetap Sama) ... --}}
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Data LLAU Harian</h3>
                <p class="text-subtitle text-muted">Kelola data lalu lintas udara harian.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                 <x-breadcrumb2 :items="[
                    ['label' => 'Dashboard', 'url' => route('root')],
                    ['label' => 'Data LLAU', 'active' => true]
                ]" />
            </div>
        </div>
    </div>
</div>

<section class="section">
    <div class="card">
        <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
            <h5 class="card-title mb-0">Daftar Data LLAU</h5>
            
            <div class="d-flex flex-wrap gap-2">
                {{-- Form Export PDF (Tetap Sama) --}}
                <form action="{{ route('staff.air-traffic.exportPdf') }}" method="GET" class="d-flex gap-2">
                    <input type="month" name="month_year" class="form-control form-control-sm" value="{{ now()->format('Y-m') }}" required>
                    <button type="submit" class="btn btn-secondary btn-sm flex-shrink-0">
                        <i class="bi bi-file-earmark-pdf"></i> Ekspor PDF
                    </button>
                </form>
                
                {{-- Tombol Tambah (Tetap Sama) --}}
                <a href="{{ route('staff.air-traffic.create') }}" class="btn btn-primary btn-sm flex-shrink-0">
                    <i class="bi bi-plus-circle me-2"></i> Tambah Data
                </a>
            </div>
        </div>
        
        <div class="card-body">
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            {{-- === FITUR BARU: FILTER & SORTING === --}}
            <div class="p-3 rounded mb-3 border">
                <form action="{{ route('staff.air-traffic.index') }}" method="GET">
                    <div class="row g-2 align-items-end">
                        {{-- 1. Filter Bulan --}}
                        <div class="col-md-4">
                            <label for="filter_month" class="form-label small fw-bold">Filter Bulan</label>
                            <input type="month" class="form-control form-control-sm" name="filter_month" 
                                   value="{{ request('filter_month') }}">
                        </div>

                        {{-- 2. Sort Order --}}
                        <div class="col-md-4">
                            <label for="sort_order" class="form-label small fw-bold">Urutkan Tanggal</label>
                            <select name="sort_order" class="form-select form-select-sm">
                                <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>Terbaru</option>
                                <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>Terlama</option>
                            </select>
                        </div>

                        {{-- 3. Tombol Aksi --}}
                        <div class="col-md-4 d-flex gap-2">
                            <button type="submit" class="btn btn-info btn-sm text-white w-100">
                                <i class="bi bi-search"></i> Terapkan
                            </button>
                            @if(request()->has('filter_month') || request()->has('sort_order'))
                                <a href="{{ route('staff.air-traffic.index') }}" class="btn btn-secondary btn-sm w-100">
                                    <i class="bi bi-arrow-counterclockwise"></i> Reset
                                </a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
            {{-- === END FITUR BARU === --}}

            <div class="table-responsive">
                <table class="table table-striped" id="table-lalulintas">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Pesawat (D/B)</th>
                            <th>Penumpang (D/B)</th>
                            <th>Bagasi (D/B)</th>
                            <th>Kargo (D/B)</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($traffics as $traffic)
                        <tr>
                            <td data-sort="{{ $traffic->date->format('Y-m-d') }}">
                                {{ $traffic->date->translatedFormat('d M Y') }}
                                {{-- Badge kecil untuk hari --}}
                                <br><small class="text-muted">{{ $traffic->date->translatedFormat('l') }}</small>
                            </td>
                            <td>{{ number_format($traffic->aircraft_arrival, 0, ',', '.') }} / {{ number_format($traffic->aircraft_departure, 0, ',', '.') }}</td>
                            <td>{{ number_format($traffic->passenger_arrival, 0, ',', '.') }} / {{ number_format($traffic->passenger_departure, 0, ',', '.') }}</td>
                            <td>{{ number_format($traffic->baggage_arrival, 0, ',', '.') }} / {{ number_format($traffic->baggage_departure, 0, ',', '.') }}</td>
                            <td>{{ number_format($traffic->cargo_arrival, 0, ',', '.') }} / {{ number_format($traffic->cargo_departure, 0, ',', '.') }}</td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('staff.air-traffic.edit', $traffic->id) }}" class="btn btn-warning btn-sm"><i class="bi bi-pencil-square"></i></a>
                                    <form action="{{ route('staff.air-traffic.destroy', $traffic->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data tanggal {{ $traffic->date->format('d/m/Y') }}?')">
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
    <script src="{{ asset('assetsv2/compiled/js/staff-lalulintas.js') }}"></script>

    <script>
        
    </script>
@endsection