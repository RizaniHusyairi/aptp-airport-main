@csrf
<div class="row">
    <div class="col-md-4 mb-3">
        <label for="flow_type" class="form-label">Aliran Dana <span class="text-danger">*</span></label>
        <select id="flow_type" name="finance[0][flow_type]" class="form-select" required>
            <option value="">Pilih Aliran Dana</option>
            <option value="in" @if(isset($finance) && $finance->flow_type == 'in') selected @endif>Pemasukan</option>
            <option value="budget" @if(isset($finance) && $finance->flow_type == 'budget') selected @endif>Anggaran</option>
        </select>
    </div>

    {{-- Input Baru: Sumber Dana --}}
    <div class="col-md-4 mb-3" id="source-container" style="{{ isset($finance) && in_array($finance->flow_type, ['in', 'budget']) ? '' : 'display: none;' }}">
        <label for="source" class="form-label">Sumber Dana</label>
        <select id="source" name="finance[0][source]" class="form-select">
            <option value="">Lainnya</option>
            <option value="Rupiah Murni" @if(isset($finance) && $finance->source == 'Rupiah Murni') selected @endif>Rupiah Murni</option>
            <option value="PNBP BLU" @if(isset($finance) && $finance->source == 'PNBP BLU') selected @endif>PNBP BLU</option>
        </select>
    </div>

    <div class="col-md-4 mb-3">
        <label for="amount" class="form-label">Jumlah (Rp) <span class="text-danger">*</span></label>
        <input type="text" id="amount" name="finance[0][amount]" class="form-control jumlah-rupiah" value="{{ $finance->amount ?? '' }}" required>
    </div>
</div>

<div class="row">
     <div class="col-md-6 mb-3">
        <label for="date" class="form-label">Periode (YYYY-MM) <span class="text-danger">*</span></label>
        <input type="month" id="date" name="finance[0][date]" class="form-control" value="{{ isset($finance) ? $finance->date->format('Y-m') : '' }}" required>
    </div>
    <div class="col-md-6 mb-3">
        <label for="note" class="form-label">Catatan</label>
        <textarea id="note" name="finance[0][note]" class="form-control" rows="1">{{ $finance->note ?? '' }}</textarea>
    </div>
</div>

{{-- Detail Pengeluaran (untuk Anggaran) --}}
<div id="detail-pengeluaran-container" style="{{ isset($finance) && $finance->flow_type === 'budget' ? '' : 'display: none;' }}">
    <hr>
    <h6 class="mt-4">Detail Pengeluaran</h6>
    <div class="table-responsive">
        <table class="table" id="budget-expenses-table">
            <thead>
                <tr>
                    <th>Deskripsi</th>
                    <th>Jumlah (Rp)</th>
                    <th style="width: 50px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @if(isset($finance) && $finance->budgetExpenses->isNotEmpty())
                    @foreach($finance->budgetExpenses as $expense)
                    <tr>
                        <td><input type="text" name="budget_expenses[{{ $loop->index }}][description]" class="form-control" value="{{ $expense->description }}" required></td>
                        <td><input type="text" name="budget_expenses[{{ $loop->index }}][amount]" class="form-control jumlah-rupiah" value="{{ $expense->amount }}" required></td>
                        <td><button type="button" class="btn btn-sm btn-danger btn-hapus-baris"><i class="bi bi-trash"></i></button></td>
                    </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
    <div class="mt-2">
        <button type="button" class="btn btn-outline-primary" id="tambah-baris"><i class="bi bi-plus"></i> Tambah Baris</button>
        <p class="mt-2"><strong>Total Pengeluaran: </strong><span id="total-pengeluaran">Rp 0</span></p>
        <div id="error-pengeluaran" class="alert alert-danger" style="display: none;"></div>
    </div>
</div>

<div class="text-end mt-4">
    <a href="{{ route('keuangan.staffIndex') }}" class="btn btn-light-secondary me-2">Batal</a>
    <button type="submit" class="btn btn-primary" id="btn-simpan">Simpan</button>
</div>
