@extends('layouts-V2.master-layouts-v2')
@section('title', 'Tambah Laporan Keuangan')
@section('styles_admin')
    
@endsection
@section('content')
<div class="page-heading">
                    <div class="page-title">
                        <div class="row">
                            <div class="col-12 col-md-6 order-md-1 order-last">
                                <h3>Tambah Laporan Keuangan</h3>
                                <p class="text-subtitle text-muted">Tambah laporan keuangan baru.</p>
                            </div>
                            <div class="col-12 col-md-6 order-md-2 order-first">
                                <x-breadcrumb2 :items="[
                                    ['label' => 'Menu', 'url' => route('profile')],
                                    ['label' => 'Laporan Keuangan', 'url' => route('keuangan.index')],
                                    ['label' => 'Tambah Laporan Keuangan', 'active' => true]
                                ]" />
                                
                            </div>
                        </div>
                    </div>
                    <section class="section">
                        <div class="card">
                            @if($errors->has('finance'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    {{ $errors->first('finance') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif
                            @if($errors->has('budget_expenses'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    {{ $errors->first('budget_expenses') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif
                            <div class="card-header">

                                <h5 class="card-title">Form Tambah Laporan Keuangan</h5>
                            </div>
                            <div class="card-body">
                                <form id="form-tambah-laporan" action="{{ route('keuangan.store') }}" method="POST">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="flow_type" class="form-label">Aliran Dana <span class="text-danger">*</span></label>
                                            <select id="flow_type" name="finance[0][flow_type]" class="form-select" required>
                                                <option value="">Pilih Aliran Dana</option>
                                                <option value="in">Pemasukan</option>
                                                <option value="budget">Anggaran</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="amount" class="form-label">Jumlah (Rp) <span class="text-danger">*</span></label>
                                            <input type="text" id="amount" name="finance[0][amount]" class="form-control jumlah-rupiah" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="date" class="form-label">Periode (YYYY-MM) <span class="text-danger">*</span></label>
                                            <input type="month" id="date" name="finance[0][date]" class="form-control" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="note" class="form-label">Catatan <span class="text-danger">*</span></label>
                                            <textarea id="note" name="finance[0][note]" class="form-control" rows="4" required></textarea>
                                        </div>
                                    </div>
                                    <div id="detail-pengeluaran-container" style="display: none;">
                                        <h6 class="mt-4">Detail Pengeluaran</h6>
                                        <div class="table-responsive">
                                            <table class="table table-striped" id="budget-expenses-table">
                                                <thead>
                                                    <tr>
                                                        <th>Deskripsi</th>
                                                        <th>Jumlah (Rp)</th>
                                                        <th>Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                        </div>
                                        <div class="mt-2">
                                            <button type="button" class="btn btn-outline-primary" id="tambah-baris"><i class="bi bi-plus"></i> Tambah Baris</button>
                                            <p class="mt-2"><strong>Total Pengeluaran: </strong><span id="total-pengeluaran">Rp 0</span></p>
                                            <div id="error-pengeluaran" class="alert alert-danger" style="display: none;"></div>
                                        </div>
                                    </div>
                                    <div class="text-end mt-4">
                                        <button type="submit" class="btn btn-primary" id="btn-simpan">Simpan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </section>
                </div>
                @endsection
                
@section('scripts_admin')
    <script src="{{ asset('assetsv2/extensions/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assetsv2/extensions/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
    
    <script src="{{ asset('../assetsv2/compiled/js/staff-tambah-laporan.js') }}"></script>
@endsection