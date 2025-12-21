@extends('layouts-V2.master-layouts-v2')
@section('title', 'Formulir Pengajuan OJT')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Formulir Pengajuan Sertifikat OJT</h3>
                <p class="text-subtitle text-muted">Lengkapi data diri Anda untuk mengajukan penerbitan sertifikat magang.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <x-breadcrumb2 :items="[
                    ['label' => 'Dashboard', 'url' => route('root')],
                    ['label' => 'Pengajuan Saya', 'url' => route('user.ojt.index')],
                    ['label' => 'Buat Baru', 'active' => true]
                ]" />
            </div>
        </div>
    </div>
</div>

<section class="section">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title"><i class="bi bi-pencil-square me-2"></i>Data Peserta OJT</h5>
        </div>
        <div class="card-body">
            {{-- Tampilkan Error Validasi --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('user.ojt.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    {{-- 1. Data Pribadi --}}
                    <div class="col-12"><h6 class="text-primary mb-3">A. Data Pribadi</h6></div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', Auth::user()->name) }}" placeholder="Sesuai KTP/Ijazah" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nomor KTP / KTM / Kartu Pelajar <span class="text-danger">*</span></label>
                        <input type="text" name="id_number" class="form-control" value="{{ old('id_number') }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Tempat Lahir <span class="text-danger">*</span></label>
                        <input type="text" name="birth_place" class="form-control" value="{{ old('birth_place') }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                        <input type="date" name="birth_date" class="form-control" value="{{ old('birth_date') }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">No. Handphone (WhatsApp) <span class="text-danger">*</span></label>
                        <input type="text" name="phone_number" class="form-control" value="{{ old('phone_number') }}" placeholder="08xxxxxxxxxx" required>
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label">Alamat Lengkap <span class="text-danger">*</span></label>
                        <textarea name="address" class="form-control" rows="2" placeholder="Alamat domisili saat ini" required>{{ old('address') }}</textarea>
                    </div>

                    <hr>

                    {{-- 2. Data Akademik --}}
                    <div class="col-12"><h6 class="text-primary mb-3">B. Data Akademik</h6></div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Asal Sekolah / Universitas <span class="text-danger">*</span></label>
                        <input type="text" name="institution" class="form-control" value="{{ old('institution') }}" placeholder="Contoh: Universitas Mulawarman" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Jurusan / Program Studi <span class="text-danger">*</span></label>
                        <input type="text" name="major" class="form-control" value="{{ old('major') }}" placeholder="Contoh: Teknik Informatika" required>
                    </div>

                    <hr>

                    {{-- 3. Data Pelaksanaan OJT --}}
                    <div class="col-12"><h6 class="text-primary mb-3">C. Pelaksanaan Magang (OJT)</h6></div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Durasi OJT <span class="text-danger">*</span></label>
                        <input type="text" name="duration" class="form-control" value="{{ old('duration') }}" placeholder="Contoh: 3 Bulan" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Tanggal Mulai <span class="text-danger">*</span></label>
                        <input type="date" name="start_date" class="form-control" value="{{ old('start_date') }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Tanggal Selesai <span class="text-danger">*</span></label>
                        <input type="date" name="end_date" class="form-control" value="{{ old('end_date') }}" required>
                    </div>

                    {{-- Dynamic Input Pembimbing --}}
                    <div class="col-12 mb-3">
                        <label class="form-label">Nama Pembimbing Lapangan <span class="text-danger">*</span></label>
                        <div id="supervisor-wrapper">
                            {{-- Input pertama --}}
                            <div class="input-group mb-2">
                                <input type="text" name="supervisors[]" class="form-control" placeholder="Nama Pembimbing 1" required>
                                <button type="button" class="btn btn-success" id="add-supervisor" title="Tambah Pembimbing"><i class="bi bi-plus"></i></button>
                            </div>
                            
                            {{-- Logic untuk old input jika validasi gagal --}}
                            @if(old('supervisors'))
                                @foreach(old('supervisors') as $index => $oldSup)
                                    @if($index > 0) 
                                        <div class="input-group mb-2">
                                            <input type="text" name="supervisors[]" class="form-control" value="{{ $oldSup }}" required>
                                            <button type="button" class="btn btn-danger remove-supervisor"><i class="bi bi-trash"></i></button>
                                        </div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                        <small class="text-muted">Klik tombol (+) jika memiliki lebih dari satu pembimbing.</small>
                    </div>

                    {{-- Checkbox Unit Kerja --}}
                    <div class="col-12 mb-3">
                        <label class="form-label d-block fw-bold mb-2">Unit Kerja Penempatan <span class="text-danger">*</span></label>
                        <div class="p-3 border rounded">
                            <div class="row">
                                @foreach($units as $unit)
                                <div class="col-md-3 col-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="work_units[]" value="{{ $unit }}" id="unit_{{ $loop->index }}"
                                        {{ (is_array(old('work_units')) && in_array($unit, old('work_units'))) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="unit_{{ $loop->index }}">
                                            {{ $unit }}
                                        </label>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @error('work_units') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <hr>

                    {{-- 4. Upload Berkas --}}
                    <div class="col-12"><h6 class="text-primary mb-3">D. Dokumen Pendukung</h6></div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Scan KTP / Kartu Mahasiswa / Pelajar <span class="text-danger">*</span></label>
                        <input type="file" name="identity_card" class="form-control" accept="image/jpeg,image/png,image/jpg" required>
                        <small class="text-muted">Format: JPG/PNG, Maks. 2MB.</small>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Pas Foto (4x6) <span class="text-danger">*</span></label>
                        <input type="file" name="photo" class="form-control" accept="image/jpeg,image/png,image/jpg" required>
                        <small class="text-muted">Latar belakang <strong>Merah</strong>, Kemeja <strong>Putih</strong>. Format: JPG/PNG, Maks. 2MB.</small>
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-4 gap-2">
                    <a href="{{ route('user.ojt.index') }}" class="btn btn-light-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-send-fill me-2"></i> Kirim Pengajuan</button>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection

@section('scripts_admin')
<script>
    // Script untuk menambah input pembimbing secara dinamis
    document.getElementById('add-supervisor').addEventListener('click', function() {
        let wrapper = document.getElementById('supervisor-wrapper');
        
        // Buat elemen input group baru
        let inputGroup = document.createElement('div');
        inputGroup.className = 'input-group mb-2';
        inputGroup.innerHTML = `
            <input type="text" name="supervisors[]" class="form-control" placeholder="Nama Pembimbing Lainnya" required>
            <button type="button" class="btn btn-danger remove-supervisor"><i class="bi bi-trash"></i></button>
        `;
        
        wrapper.appendChild(inputGroup);
    });

    // Event delegation untuk tombol hapus (tong sampah)
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-supervisor')) {
            e.target.closest('.input-group').remove();
        }
    });
</script>
@endsection