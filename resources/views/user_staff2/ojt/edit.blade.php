@extends('layouts-V2.master-layouts-v2')
@section('title', 'Edit Data OJT')

@section('content')
<section class="section">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Edit Data Peserta OJT</h5>
            <a href="{{ route('staff.ojt.index') }}" class="btn btn-light-secondary btn-sm"><i class="bi bi-arrow-left"></i> Kembali</a>
        </div>
        <div class="card-body">
            <form action="{{ route('staff.ojt.update', $student->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT') {{-- PENTING UNTUK UPDATE --}}

                <div class="row">
                    {{-- Data Pribadi --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $student->name) }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nomor KTP/KTM/Pelajar</label>
                        <input type="text" name="id_number" class="form-control" value="{{ old('id_number', $student->id_number) }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Tempat Lahir</label>
                        <input type="text" name="birth_place" class="form-control" value="{{ old('birth_place', $student->birth_place) }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Tanggal Lahir</label>
                        <input type="date" name="birth_date" class="form-control" value="{{ old('birth_date', $student->birth_date->format('Y-m-d')) }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">No. Handphone</label>
                        <input type="text" name="phone_number" class="form-control" value="{{ old('phone_number', $student->phone_number) }}" required>
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label">Alamat Lengkap</label>
                        <textarea name="address" class="form-control" rows="2" required>{{ old('address', $student->address) }}</textarea>
                    </div>

                    <hr>

                    {{-- Data Akademik --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Asal Sekolah/Universitas</label>
                        <input type="text" name="institution" class="form-control" value="{{ old('institution', $student->institution) }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Jurusan</label>
                        <input type="text" name="major" class="form-control" value="{{ old('major', $student->major) }}" required>
                    </div>

                    {{-- Data Magang --}}
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Lama OJT</label>
                        <input type="text" name="duration" class="form-control" value="{{ old('duration', $student->duration) }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Tanggal Mulai</label>
                        <input type="date" name="start_date" class="form-control" value="{{ old('start_date', $student->start_date->format('Y-m-d')) }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Tanggal Selesai</label>
                        <input type="date" name="end_date" class="form-control" value="{{ old('end_date', $student->end_date->format('Y-m-d')) }}" required>
                    </div>

                    {{-- Dynamic Input Pembimbing --}}
                    <div class="col-12 mb-3">
                        <label class="form-label">Nama Pembimbing</label>
                        <div id="supervisor-wrapper">
                            @if(is_array($student->supervisors) && count($student->supervisors) > 0)
                                @foreach($student->supervisors as $index => $spv)
                                    <div class="input-group mb-2">
                                        <input type="text" name="supervisors[]" class="form-control" value="{{ $spv }}" required>
                                        @if($loop->first)
                                            <button type="button" class="btn btn-success" id="add-supervisor"><i class="bi bi-plus"></i></button>
                                        @else
                                            <button type="button" class="btn btn-danger remove-supervisor"><i class="bi bi-trash"></i></button>
                                        @endif
                                    </div>
                                @endforeach
                            @else
                                {{-- Fallback jika data kosong --}}
                                <div class="input-group mb-2">
                                    <input type="text" name="supervisors[]" class="form-control" placeholder="Nama Pembimbing 1" required>
                                    <button type="button" class="btn btn-success" id="add-supervisor"><i class="bi bi-plus"></i></button>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Checkbox Unit Kerja --}}
                    <div class="col-12 mb-3">
                        <label class="form-label d-block fw-bold">Unit Kerja Selama OJT:</label>
                        <div class="row">
                            @foreach($units as $unit)
                            <div class="col-md-3 col-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="work_units[]" value="{{ $unit }}"
                                        id="unit_{{ $loop->index }}"
                                        {{ in_array($unit, old('work_units', $student->work_units ?? [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="unit_{{ $loop->index }}">
                                        {{ $unit }}
                                    </label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <hr>

                    {{-- Upload Files (Nullable saat Edit) --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Scan KTP/KTM</label>
                        @if($student->identity_card_path)
                            <div class="mb-2">
                                <a href="{{ asset('uploads/' . $student->identity_card_path) }}" target="_blank" class="badge bg-primary">Lihat File Saat Ini</a>
                            </div>
                        @endif
                        <input type="file" name="identity_card" class="form-control" accept="image/*">
                        <small class="text-muted">Biarkan kosong jika tidak ingin mengubah.</small>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Pas Foto (4x6)</label>
                        @if($student->photo_path)
                            <div class="mb-2">
                                <img src="{{ asset('uploads/' . $student->photo_path) }}" alt="Foto" style="height: 50px; border: 1px solid #ccc;">
                            </div>
                        @endif
                        <input type="file" name="photo" class="form-control" accept="image/*">
                        <small class="text-muted">Biarkan kosong jika tidak ingin mengubah.</small>
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-3">
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</section>

<script>
    document.getElementById('add-supervisor').addEventListener('click', function() {
        let wrapper = document.getElementById('supervisor-wrapper');
        let inputGroup = document.createElement('div');
        inputGroup.className = 'input-group mb-2';
        inputGroup.innerHTML = `
            <input type="text" name="supervisors[]" class="form-control" placeholder="Nama Pembimbing Lainnya" required>
            <button type="button" class="btn btn-danger remove-supervisor"><i class="bi bi-trash"></i></button>
        `;
        wrapper.appendChild(inputGroup);
    });

    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-supervisor')) {
            e.target.closest('.input-group').remove();
        }
    });
</script>
@endsection
