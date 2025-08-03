@extends('layouts-V2.master-layouts-v2')
@section('title', 'Edit Laporan Keuangan')
@section('styles_admin')
 
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endsection
@section('content')
@staff
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Edit Laporan Keuangan</h3>
                    <p class="text-subtitle text-muted">Ubah laporan keuangan.</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <x-breadcrumb2 :items="[
                        ['label' => 'Menu', 'url' => route('profile')],
                        ['label' => 'Laporan Keuangan', 'url' => route('keuangan.staffIndex')],
                        ['label' => 'Edit Laporan Keuangan', 'active' => true]
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
                    <h5 class="card-title">Form Edit Laporan Keuangan</h5>
                </div>
                <div class="card-body">
                    <form id="form-edit-laporan" action="{{ route('keuangan.update', $finance->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="flow_type" class="form-label">Aliran Dana <span class="text-danger">*</span></label>
                                <select id="flow_type" name="finance[0][flow_type]" class="form-select" required>
                                    <option value="">Pilih Aliran Dana</option>
                                    <option value="in" {{ old('finance.0.flow_type', $finance->flow_type) === 'in' ? 'selected' : '' }}>Pemasukan</option>
                                    <option value="budget" {{ old('finance.0.flow_type', $finance->flow_type) === 'budget' ? 'selected' : '' }}>Anggaran</option>
                                </select>
                                @error('finance.0.flow_type')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="amount" class="form-label">Jumlah (Rp) <span class="text-danger">*</span></label>
                                <input type="text" id="amount" name="finance[0][amount]" class="form-control jumlah-rupiah"
                                       value="{{ old('finance.0.amount', $finance->amount) }}" required>
                                @error('finance.0.amount')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="date" class="form-label">Periode (YYYY-MM) <span class="text-danger">*</span></label>
                                <input type="month" id="date" name="finance[0][date]" class="form-control"
                                       value="{{ old('finance.0.date', $finance->date->format('Y-m')) }}" required>
                                @error('finance.0.date')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="note" class="form-label">Catatan</label>
                                <textarea id="note" name="finance[0][note]" class="form-control" rows="4">{{ old('finance.0.note', $finance->note) }}</textarea>
                                @error('finance.0.note')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div id="detail-pengeluaran-container" style="{{ $finance->flow_type === 'budget' ? 'display: block;' : 'display: none;' }}">
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
                                    <tbody>
                                        @foreach ($finance->budgetExpenses as $index => $expense)
                                            <tr>
                                                <td><input type="text" name="budget_expenses[{{ $index + 1 }}][description]" class="form-control"
                                                           value="{{ old('budget_expenses.' . ($index + 1) . '.description', $expense->description) }}" required></td>
                                                <td><input type="text" name="budget_expenses[{{ $index + 1 }}][amount]" class="form-control jumlah-rupiah"
                                                           value="{{ old('budget_expenses.' . ($index + 1) . '.amount', $expense->amount) }}" required></td>
                                                <td><button type="button" class="btn btn-sm btn-danger btn-hapus-baris" data-bs-toggle="tooltip" title="Hapus Baris"><i class="bi bi-trash"></i></button></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-2">
                                <button type="button" class="btn btn-outline-primary" id="tambah-baris"><i class="bi bi-plus"></i> Tambah Baris</button>
                                <p class="mt-2"><strong>Total Pengeluaran: </strong><span id="total-pengeluaran">Rp {{ number_format($finance->budgetExpenses->sum('amount'), 0, ',', '.') }}</span></p>
                                <div id="error-pengeluaran" class="alert alert-danger" style="display: none;"></div>
                            </div>
                        </div>
                        <div class="text-end mt-4">
                            <a href="{{ route('keuangan.staffIndex') }}" class="btn btn-secondary me-2">Batal</a>
                            <button type="submit" class="btn btn-primary" id="btn-simpan">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
@else
    <div class="alert alert-danger">Akses hanya untuk staff.</div>
@endstaff
@endsection
@section('scripts_admin')
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('assetsv2/extensions/cleave.js/cleave.min.js') }}"></script>
    <script src="{{ asset('assetsv2/compiled/js/staff-tambah-laporan.js') }}"></script>
@endsection