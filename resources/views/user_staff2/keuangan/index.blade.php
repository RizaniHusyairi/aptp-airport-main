@extends('layouts-V2.master-layouts-v2')
@section('title', 'Manajemen Laporan Keuangan')
@section('styles_admin')
    <link rel="stylesheet" href="{{ asset('assetsv2/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assetsv2/compiled/css/table-datatable-jquery.css') }}">
@endsection
@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Laporan Keuangan</h3>
                <p class="text-subtitle text-muted">Kelola laporan keuangan untuk staff.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <x-breadcrumb2 :items="[
                                        ['label' => 'Menu', 'url' => route('profile')],
                                        ['label' => 'Laporan Keuangan', 'active' => true]
                                    ]" />
                
            </div>
        </div>
    </div>
    <section class="section">
        <div class="card">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Daftar Transaksi Keuangan</h5>
                <a href="{{ route('keuangan.create') }}" class="btn btn-primary"><i class="bi bi-plus"></i> Tambah Laporan Keuangan</a>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-3 col-6 mb-2">
                        <label for="filter-tahun" class="form-label">Tahun</label>
                        <select id="filter-tahun" class="form-select">
                            <option value="">Semua Tahun</option>
                            <!-- Opsi tahun diisi oleh JavaScript -->
                        </select>
                    </div>
                    <div class="col-md-3 col-6 mb-2">
                        <label for="filter-arus-dana" class="form-label">Arus Dana</label>
                        <select id="filter-arus-dana" class="form-select">
                            <option value="">Semua</option>
                            <option value="Pemasukan">Pemasukan</option>
                            <option value="Anggaran">Anggaran</option>
                        </select>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped" id="table-laporan-keuangan">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Jenis</th>
                                <th>Jumlah</th>
                                <th>Catatan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($finances as $index => $finance)
                            <tr data-id="{{ $finance->id }}">
                                <td>{{ \Carbon\Carbon::parse($finance->date)->format('M Y') }}</td>
                                <td>
                                    @if($finance->flow_type === 'in')
                                        <span class="badge bg-success">Pemasukan</span>
                                    @else
                                        <span class="badge bg-warning">Anggaran</span>
                                    @endif
                                </td>
                                <td>Rp {{ number_format($finance->amount, 0, ',', '.') }}</td>
                                <td>{{ $finance->note ?? '-' }}</td>
                                <td>
                                    @if($finance->flow_type == 'budget')
                                    
                                    <button class="btn btn-sm btn-info btn-lihat-pengeluaran text-white btn-tooltip" data-bs-toggle="tooltip" title="Lihat Belanja"><i class="bi bi-eye"></i></button>
                                    @foreach($finance->budgetExpenses as $expense)
                                    
                                    <div  class="expense d-none" data-id="{{ $expense->id }}">
                                        <span class="nomor">{{ $loop->iteration }}</span>
                                        <span class="deskripsi">{{ $expense->description }}</span>
                                        <span class="jumlah">Rp {{ number_format($expense->amount, 0, ',', '.') }}</span>

                                    </div>
                                    @endforeach
                                    
                                    
                                    @endif
                                    <a href="{{ route('keuangan.edit', $finance->id) }}" class="btn btn-sm btn-warning btn-tooltip text-white" data-bs-toggle="tooltip" title="Edit"><i class="bi bi-pencil"></i></a>
                                    <button class="btn btn-sm btn-danger btn-hapus btn-tooltip" data-id="{{ $finance->id }}" data-bs-toggle="tooltip" title="Hapus"><i class="bi bi-trash"></i></button>
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
</div>
<div class="modal fade" id="modal-pengeluaran" tabindex="-1" aria-labelledby="modal-pengeluaran-label" aria-hidden="true">
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="modal-pengeluaran-label">Detail Pengeluaran</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="table-responsive">
                <table class="table table-striped" id="table-detail-pengeluaran">
                    <thead>
                        <tr>
                            <th>Nomor</th>
                            <th>Deskripsi</th>
                            <th>Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data akan diisi oleh JavaScript -->
                    </tbody>
                </table>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
        </div>
    </div>
</div>
</div>
@endsection

@section('scripts_admin')
    <script src="{{ asset('assetsv2/extensions/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assetsv2/extensions/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('../assetsv2/compiled/js/staff-laporan-keuangan.js') }}"></script>
@endsection