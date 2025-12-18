@extends('layouts-V2.master-layouts-v2')
@section('title', 'Input Data OJT')

@section('content')
<section class="section">
    <div class="card">
        <div class="card-header"><h5>Formulir Data Anak OJT</h5></div>
        <div class="card-body">
            <form action="{{ route('staff.ojt.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    {{-- Data Pribadi --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nomor KTP/KTM/Pelajar</label>
                        <input type="text" name="id_number" class="form-control" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Tempat Lahir</label>
                        <input type="text" name="birth_place" class="form-control" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Tanggal Lahir</label>
                        <input type="date" name="birth_date" class="form-control" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">No. Handphone</label>
                        <input type="text" name="phone_number" class="form-control" required>
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label">Alamat Lengkap</label>
                        <textarea name="address" class="form-control" rows="2" required></textarea>
                    </div>

                    <hr>

                    {{-- Data Akademik --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Asal Sekolah/Universitas</label>
                        <input type="text" name="institution" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Jurusan</label>
                        <input type="text" name="major" class="form-control" required>
                    </div>

                    {{-- Data Magang --}}
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Lama OJT (Contoh: 3 Bulan)</label>
                        <input type="text" name="duration" class="form-control" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Tanggal Mulai</label>
                        <input type="date" name="start_date" class="form-control" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Tanggal Selesai</label>
                        <input type="date" name="end_date" class="form-control" required>
                    </div>

                    {{-- Dynamic Input Pembimbing --}}
                    <div class="col-12 mb-3">
                        <label class="form-label">Nama Pembimbing</label>
                        <div id="supervisor-wrapper">
                            <div class="input-group mb-2">
                                <input type="text" name="supervisors[]" class="form-control" placeholder="Nama Pembimbing 1" required>
                                <button type="button" class="btn btn-success" id="add-supervisor"><i class="bi bi-plus"></i></button>
                            </div>
                        </div>
                        <small class="text-muted">Klik (+) untuk menambah pembimbing lain.</small>
                    </div>

                    {{-- Checkbox Unit Kerja --}}
                    <div class="col-12 mb-3">
                        <label class="form-label d-block fw-bold">Unit Kerja Selama OJT:</label>
                        <div class="row">
                            @foreach($units as $unit)
                            <div class="col-md-3 col-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="work_units[]" value="{{ $unit }}" id="unit_{{ $loop->index }}">
                                    <label class="form-check-label" for="unit_{{ $loop->index }}">
                                        {{ $unit }}
                                    </label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <hr>

                    {{-- Upload Files --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Scan KTP/KTM</label>
                        <input type="file" name="identity_card" class="form-control" accept="image/*" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Pas Foto (4x6, Latar Merah)</label>
                        <input type="file" name="photo" class="form-control" accept="image/*" required>
                        <small class="text-muted">Gunakan kemeja putih.</small>
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-3">
                    <button type="submit" class="btn btn-primary">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</section>

<script>
    // Script sederhana untuk menambah input pembimbing
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
