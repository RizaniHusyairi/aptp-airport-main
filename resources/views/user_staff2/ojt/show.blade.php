@extends('layouts-V2.master-layouts-v2')
@section('title', 'Detail Peserta OJT - ' . $student->name)

@section('styles_admin')
    <style>
        .profile-card {
            text-align: center;
            padding: 2rem 1rem;
        }
        .profile-img-container {
            width: 150px;
            height: 180px; /* Rasio 4x6 */
            margin: 0 auto 1.5rem;
            border: 1px solid #dee2e6;
            padding: 5px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .profile-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 4px;
        }
        .detail-label {
            font-size: 0.85rem;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.25rem;
            font-weight: 600;
        }
        .detail-value {
            font-size: 1rem;
            font-weight: 500;
            margin-bottom: 1.25rem;
        }
        .id-card-preview {
            width: 100%;
            max-width: 300px;
            border-radius: 8px;
            border: 1px solid #ddd;
            transition: transform 0.3s;
            cursor: pointer;
        }
        .id-card-preview:hover {
            transform: scale(1.02);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .section-title {
            border-bottom: 2px solid #f2f7ff;
            padding-bottom: 10px;
            margin-bottom: 20px;
            font-size: 1.1rem;
            font-weight: bold;
            color: #435ebe;
        }
    </style>
@endsection

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Detail Peserta OJT</h3>
                <p class="text-subtitle text-muted">Informasi lengkap dan status peserta magang.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <x-breadcrumb2 :items="[
                    ['label' => 'Dashboard', 'url' => route('root')],
                    ['label' => 'Data OJT', 'url' => route('staff.ojt.index')],
                    ['label' => 'Detail', 'active' => true]
                ]" />
            </div>
            <div class="col-12">
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif
                @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

            </div>
        </div>
    </div>
</div>

<section class="section">   
    <div class="row">
        {{-- KOLOM KIRI: Foto & Identitas Utama --}}
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body profile-card">
                    <div class="profile-img-container">
                        @if($student->photo_path)
                            <img src="{{ asset('uploads/' . $student->photo_path) }}" alt="{{ $student->name }}" class="profile-img">
                        @else
                            <div class="d-flex align-items-center justify-content-center bg-light h-100 text-muted">
                                <i class="bi bi-person-fill" style="font-size: 3rem;"></i>
                            </div>
                        @endif
                    </div>

                    <h4 class="mb-1">{{ $student->name }}</h4>
                    <p class="text-muted mb-3">{{ $student->id_number }}</p>

                    <span class="badge bg-light-primary text-primary mb-4 px-3 py-2">
                        <i class="bi bi-mortarboard-fill me-1"></i> {{ $student->institution }}
                    </span>

                    <div class="d-grid gap-2">
                        <a href="{{ route('staff.ojt.certificate', $student->id) }}" target="_blank" class="btn btn-success">
                            <i class="bi bi-printer-fill me-2"></i> Cetak Sertifikat
                        </a>

                        {{-- Tombol Delete (Opsional di halaman detail) --}}
                        <form action="{{ route('staff.ojt.destroy', $student->id) }}" method="POST" onsubmit="return confirm('Hapus data ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger w-100">
                                <i class="bi bi-trash"></i> Hapus Data
                            </button>
                        </form>

                        <a href="{{ route('staff.ojt.index') }}" class="btn btn-secondary mt-2">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- KOLOM KANAN: Detail Informasi --}}
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body">

                    {{-- 1. Informasi Pribadi --}}
                    <div class="section-title"><i class="bi bi-person-lines-fill me-2"></i>Data Pribadi</div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="detail-label">Tempat, Tanggal Lahir</div>
                            <div class="detail-value">
                                {{ $student->birth_place }}, {{ $student->birth_date->translatedFormat('d F Y') }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="detail-label">No. Handphone</div>
                            <div class="detail-value">{{ $student->phone_number }}</div>
                        </div>
                        <div class="col-12">
                            <div class="detail-label">Alamat Lengkap</div>
                            <div class="detail-value">{{ $student->address }}</div>
                        </div>
                    </div>

                    {{-- 2. Informasi Akademik --}}
                    <div class="section-title mt-3"><i class="bi bi-building me-2"></i>Informasi Akademik</div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="detail-label">Institusi Pendidikan</div>
                            <div class="detail-value fw-bold">{{ $student->institution }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="detail-label">Jurusan / Program Studi</div>
                            <div class="detail-value">{{ $student->major }}</div>
                        </div>
                    </div>

                    {{-- 3. Detail Pelaksanaan OJT --}}
                    <div class="section-title mt-3"><i class="bi bi-calendar-check me-2"></i>Pelaksanaan OJT</div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="detail-label">Durasi</div>
                            <div class="detail-value">{{ $student->duration }}</div>
                        </div>
                        <div class="col-md-8">
                            <div class="detail-label">Periode Tanggal</div>
                            <div class="detail-value">
                                <i class="bi bi-calendar-range me-1"></i>
                                {{ $student->start_date->translatedFormat('d F Y') }}
                                <span class="mx-2">&mdash;</span>
                                {{ $student->end_date->translatedFormat('d F Y') }}
                            </div>
                        </div>

                        <div class="col-12 mb-3">
                            <div class="detail-label">Unit Kerja Penempatan</div>
                            <div class="d-flex flex-wrap gap-2 mt-1">
                                @if(is_array($student->work_units))
                                    @foreach($student->work_units as $unit)
                                        <span class="badge bg-info bg-opacity-10 text-info border border-info px-3 py-2">
                                            {{ $unit }}
                                        </span>
                                    @endforeach
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="detail-label">Pembimbing Lapangan</div>
                            <ul class="list-group list-group-flush mt-1">
                                @if(is_array($student->supervisors))
                                    @foreach($student->supervisors as $supervisor)
                                        <li class="list-group-item px-0 py-1 border-0">
                                            <i class="bi bi-person-check me-2 text-success"></i> {{ $supervisor }}
                                        </li>
                                    @endforeach
                                @else
                                    <li class="text-muted">-</li>
                                @endif
                            </ul>
                        </div>
                    </div>

                    {{-- 4. Dokumen Pendukung --}}
                    <div class="section-title mt-4"><i class="bi bi-file-earmark-image me-2"></i>Dokumen Identitas</div>
                    <div class="row">
                        <div class="col-12">
                            <div class="detail-label">Scan KTP / Kartu Pelajar</div>
                            @if($student->identity_card_path)
                                <div class="mt-2">
                                    <a href="{{ asset('uploads/' . $student->identity_card_path) }}" target="_blank">
                                        <img src="{{ asset('uploads/' . $student->identity_card_path) }}" class="id-card-preview" alt="KTP">
                                    </a>
                                    <div class="small mt-1 fst-italic">Klik gambar untuk memperbesar</div>
                                </div>
                            @else
                                <div class="alert alert-secondary border">
                                    <i class="bi bi-exclamation-circle me-1"></i> Dokumen tidak tersedia.
                                </div>
                            @endif
                        </div>
                    </div>

                </div>
            </div>
            <div class="card shadow-sm mt-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-primary"><i class="bi bi-journal-check me-2"></i>Penilaian OJT</h5>
                    @if($student->average_score)
                        <span class="badge bg-success fs-6">
                            Nilai Akhir: {{ $student->letter_grade }} ({{ number_format($student->average_score, 2) }})
                        </span>
                    @endif
                </div>
                <div class="card-body">
                    <form action="{{ route('staff.ojt.updateGrades', $student->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" id="grading-table">
                                <thead class="table text-center align-middle">
                                    <tr>
                                        <th style="width: 5%">No</th>
                                        <th style="width: 25%">Kategori / Tipe <br><small class="fw-normal">(Hard Skill/Soft Skill)</small></th>
                                        <th>Komponen Penilaian</th>
                                        <th style="width: 15%">Nilai (0-100)</th>
                                        <th style="width: 5%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="grading-body">
                                    @php
                                        // Template Default (Jika data kosong)
                                        $defaults = [
                                            ['type' => 'Hard Skill', 'comp' => 'Kemampuan Mendesign Program'],
                                            ['type' => 'Hard Skill', 'comp' => 'Kemampuan Melaksanakan Magang'],
                                            ['type' => 'Hard Skill', 'comp' => 'Laporan & Presentasi'],
                                            ['type' => 'Soft Skill', 'comp' => 'Integritas'],
                                            ['type' => 'Soft Skill', 'comp' => 'Tanggung Jawab'],
                                            ['type' => 'Soft Skill', 'comp' => 'Kerja Keras'],
                                            ['type' => 'Soft Skill', 'comp' => 'Kreativitas']
                                        ];

                                        $currentGrades = $student->grades ?? [];
                                    @endphp

                                    {{-- Datalist untuk Autocomplete Kategori --}}
                                    <datalist id="categoryOptions">
                                        <option value="Hard Skill">
                                        <option value="Soft Skill">
                                        <option value="Bidang Kompetensi FSD">
                                        <option value="Bidang Kompetensi FSU">
                                    </datalist>

                                    @if(count($currentGrades) > 0)
                                        {{-- TAMPILKAN DATA DARI DATABASE --}}
                                        @foreach($currentGrades as $index => $grade)
                                            <tr>
                                                <td class="text-center">{{ $loop->iteration }}</td>
                                                <td>
                                                    <input type="text" name="types[]" class="form-control fw-bold"
                                                        list="categoryOptions"
                                                        value="{{ $grade['type'] ?? 'Umum' }}"
                                                        placeholder="Tipe..." required>
                                                </td>
                                                <td>
                                                    <input type="text" name="components[]" class="form-control"
                                                        value="{{ $grade['component'] }}"
                                                        placeholder="Nama komponen..." required>
                                                </td>
                                                <td>
                                                    <input type="number" name="scores[]" class="form-control text-center score-input fw-bold"
                                                        value="{{ $grade['score'] }}" min="0" max="100" required>
                                                </td>
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-danger btn-sm remove-row"><i class="bi bi-trash"></i></button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        {{-- TAMPILKAN TEMPLATE DEFAULT --}}
                                        @foreach($defaults as $index => $item)
                                            <tr>
                                                <td class="text-center">{{ $index + 1 }}</td>
                                                <td>
                                                    <input type="text" name="types[]" class="form-control fw-bold text-primary"
                                                        list="categoryOptions"
                                                        value="{{ $item['type'] }}" required>
                                                </td>
                                                <td>
                                                    <input type="text" name="components[]" class="form-control"
                                                        value="{{ $item['comp'] }}" required>
                                                </td>
                                                <td>
                                                    <input type="number" name="scores[]" class="form-control text-center score-input fw-bold"
                                                        value="0" min="0" max="100" required>
                                                </td>
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-danger btn-sm remove-row"><i class="bi bi-trash"></i></button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="5">
                                            <button type="button" class="btn btn-outline-primary btn-sm" id="add-grade-row">
                                                <i class="bi bi-plus-circle"></i> Tambah Baris Penilaian
                                            </button>
                                        </td>
                                    </tr>
                                    <tr class="fw-bold">
                                        <td colspan="3" class="text-end">Rata-Rata Akhir:</td>
                                        <td class="text-center fs-5" id="live-average">0</td>
                                        <td></td>
                                    </tr>
                                    <tr class="fw-bold">
                                        <td colspan="3" class="text-end">Predikat:</td>
                                        <td class="text-center text-primary" id="live-predicate">-</td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Simpan Penilaian
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            {{-- Panel Aksi Staff --}}
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0 text-white"><i class="bi bi-gear-fill me-2"></i>Aksi Verifikator</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="small text-muted">Status Saat Ini:</label>
                        <h5 class="fw-bold">{{ $student->status }}</h5>
                    </div>

                    @if($student->status != 'Selesai')
                        <hr>
                        <p class="small">Jika penilaian sudah lengkap, silakan finalisasi pengajuan ini agar sertifikat dapat diunduh oleh peserta.</p>
                        
                        <form action="{{ route('staff.ojt.finalize', $student->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            
                            {{-- Opsi Upload Manual (Jika Tanda Tangan Basah) --}}
                            <div class="mb-3">
                                <label class="form-label small">Upload Scan Sertifikat</label>
                                <input type="file" name="signed_file" class="form-control form-control-sm" required>
                            </div>

                            <button type="submit" class="btn btn-success w-100">
                                <i class="bi bi-check-circle-fill me-2"></i> Setujui & Selesai
                            </button>
                        </form>
                    @else
                        <div class="alert alert-success py-2 small">
                            <i class="bi bi-check-circle me-1"></i> Pengajuan telah selesai.
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>
</section>


@endsection

@section('scripts_admin')

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tableBody = document.getElementById('grading-body');
        const liveAverage = document.getElementById('live-average');
        const livePredicate = document.getElementById('live-predicate');
        const btnAdd = document.getElementById('add-grade-row');

        // Fungsi Hitung Rata-rata
        function calculateAverage() {
            let total = 0;
            let count = 0;
            document.querySelectorAll('.score-input').forEach(input => {
                let val = parseFloat(input.value);
                if (!isNaN(val)) {
                    total += val;
                    count++;
                }
            });

            let avg = count > 0 ? (total / count).toFixed(2) : 0;
            liveAverage.textContent = avg;

            let pred = '-';
            if (avg >= 90) pred = 'A (Sangat Baik)';
            else if (avg >= 80) pred = 'B (Baik)';
            else if (avg >= 75) pred = 'C (Cukup)';
            else if (avg > 0) pred = 'D (Kurang)';

            livePredicate.textContent = pred;
        }

        // Event Input Nilai (Live Calculation)
        tableBody.addEventListener('input', function(e) {
            if (e.target.classList.contains('score-input')) {
                calculateAverage();
            }
        });

        // Event Tambah Baris Baru
        btnAdd.addEventListener('click', function() {
            let rowCount = tableBody.rows.length + 1;
            let tr = document.createElement('tr');
            tr.innerHTML = `
                <td class="text-center bg-light">${rowCount}</td>
                <td>
                    <input type="text" name="types[]" class="form-control fw-bold text-primary"
                           list="categoryOptions" placeholder="Tipe..." required>
                </td>
                <td>
                    <input type="text" name="components[]" class="form-control" placeholder="Nama Komponen..." required>
                </td>
                <td>
                    <input type="number" name="scores[]" class="form-control text-center score-input fw-bold"
                           value="0" min="0" max="100" required>
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-danger btn-sm remove-row"><i class="bi bi-trash"></i></button>
                </td>
            `;
            tableBody.appendChild(tr);
            calculateAverage(); // Recalculate just in case
        });

        // Event Hapus Baris
        tableBody.addEventListener('click', function(e) {
            if (e.target.closest('.remove-row')) {
                e.target.closest('tr').remove();
                // Re-numbering rows (Optional but good for UX)
                Array.from(tableBody.rows).forEach((row, index) => {
                    row.cells[0].innerText = index + 1;
                });
                calculateAverage();
            }
        });

        // Hitung saat load pertama kali
        calculateAverage();
    });
</script>
@endsection
