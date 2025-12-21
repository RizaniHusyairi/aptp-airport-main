@extends('layouts-V2.master-layouts-v2')
@section('title', 'Detail Pengajuan OJT')

@section('styles_admin')
    <style>
        .profile-img-container {
            width: 140px;
            height: 180px;
            margin: 0 auto 1rem;
            border: 1px solid #dee2e6;
            padding: 4px;
            background: #fff;
            border-radius: 6px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
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
            font-weight: 600;
            margin-bottom: 2px;
        }
        .detail-value {
            font-size: 1rem;
            font-weight: 500;
            margin-bottom: 1rem;
        }
        .section-header {
            border-bottom: 1px solid #eee;
            padding-bottom: 8px;
            margin-bottom: 15px;
            color: #25396f;
            font-weight: bold;
        }
    </style>
@endsection

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Detail Pengajuan</h3>
                <p class="text-subtitle text-muted">Informasi status, penilaian, dan sertifikat.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <x-breadcrumb2 :items="[
                    ['label' => 'Dashboard', 'url' => route('root')],
                    ['label' => 'Pengajuan Saya', 'url' => route('user.ojt.index')],
                    ['label' => 'Detail', 'active' => true]
                ]" />
            </div>
        </div>
    </div>
</div>

<section class="section">
    <div class="row">
        {{-- KOLOM KIRI: Status & Aksi --}}
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body text-center pt-4">
                    {{-- Foto Profil --}}
                    <div class="profile-img-container">
                        <img src="{{ asset('uploads/' . $application->photo_path) }}" alt="Foto Peserta" class="profile-img">
                    </div>

                    <h5 class="mb-1">{{ $application->name }}</h5>
                    <p class="text-muted small mb-3">{{ $application->institution }}</p>

                    {{-- Badge Status --}}
                    <div class="mb-4">
                        @if($application->status == 'Menunggu Verifikasi')
                            <span class="badge bg-warning fs-6 px-3 py-2">Menunggu Verifikasi</span>
                        @elseif($application->status == 'Selesai')
                            <span class="badge bg-success fs-6 px-3 py-2"><i class="bi bi-check-circle me-1"></i> Selesai / Terbit</span>
                        @else
                            <span class="badge bg-secondary fs-6 px-3 py-2">{{ $application->status }}</span>
                        @endif
                    </div>

                    {{-- TOMBOL DOWNLOAD SERTIFIKAT --}}
                    @if($application->status == 'Selesai')
                        <div class="d-grid gap-2">
                            {{-- Jika Staff mengupload file manual (Scan Tanda Tangan Basah) --}}
                            @if($application->final_certificate_path)
                                <a href="{{ asset('uploads/' . $application->final_certificate_path) }}" target="_blank" class="btn btn-primary">
                                    <i class="bi bi-cloud-arrow-down-fill me-2"></i> Download Sertifikat
                                </a>
                            
                            {{-- Jika Staff hanya approve (Sertifikat Digital QR Code System) --}}
                            @else
                                <a href="{{ route('staff.ojt.certificate', ['student' => $application->id]) }}" target="_blank" class="btn btn-outline-primary">
                                    <i class="bi bi-printer me-2"></i> Cetak Sertifikat Digital
                                </a>
                            @endif
                        </div>
                    @else
                        <div class="alert alert-light-secondary font-sm">
                            <i class="bi bi-info-circle me-1"></i> Sertifikat dapat diunduh setelah status pengajuan "Selesai".
                        </div>
                    @endif

                    <hr class="my-4">
                    <div class="text-start">
                        <a href="{{ route('user.ojt.index') }}" class="btn btn-light w-100">
                            <i class="bi bi-arrow-left me-2"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- KOLOM KANAN: Detail Data & Nilai --}}
        <div class="col-md-8">
            
            {{-- 1. CARD NILAI (Hanya muncul jika sudah dinilai) --}}
            @if($application->average_score)
            <div class="card shadow-sm border-primary mb-4">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0 text-white"><i class="bi bi-award-fill me-2"></i>Hasil Penilaian OJT</h6>
                </div>
                <div class="card-body pt-4">
                    <div class="row text-center mb-4">
                        <div class="col-6 border-end">
                            <h2 class="text-primary fw-bold mb-0">{{ number_format($application->average_score, 2) }}</h2>
                            <small class="text-muted">Nilai Rata-Rata</small>
                        </div>
                        <div class="col-6">
                            <h2 class="text-success fw-bold mb-0">{{ $application->letter_grade }}</h2>
                            <small class="text-muted">{{ $application->predicate }}</small>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Komponen Penilaian</th>
                                    <th class="text-center" width="15%">Nilai</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($application->grades as $grade)
                                <tr>
                                    <td>
                                        <span class="badge border me-1">{{ $grade['type'] ?? 'Umum' }}</span>
                                        {{ $grade['component'] }}
                                    </td>
                                    <td class="text-center fw-bold">{{ $grade['score'] }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif

            {{-- 2. CARD DATA DIRI --}}
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="section-header"><i class="bi bi-person-lines-fill me-2"></i>Data Peserta</h5>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="detail-label">Nama Lengkap</div>
                            <div class="detail-value">{{ $application->name }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="detail-label">Nomor Identitas</div>
                            <div class="detail-value">{{ $application->id_number }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="detail-label">Tempat, Tanggal Lahir</div>
                            <div class="detail-value">{{ $application->birth_place }}, {{ $application->birth_date->translatedFormat('d F Y') }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="detail-label">No. Handphone</div>
                            <div class="detail-value">{{ $application->phone_number }}</div>
                        </div>
                    </div>

                    <h5 class="section-header mt-3"><i class="bi bi-building me-2"></i>Data Akademik</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="detail-label">Institusi</div>
                            <div class="detail-value">{{ $application->institution }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="detail-label">Jurusan</div>
                            <div class="detail-value">{{ $application->major }}</div>
                        </div>
                    </div>

                    <h5 class="section-header mt-3"><i class="bi bi-calendar-check me-2"></i>Pelaksanaan OJT</h5>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="detail-label">Durasi</div>
                            <div class="detail-value">{{ $application->duration }}</div>
                        </div>
                        <div class="col-md-8">
                            <div class="detail-label">Periode</div>
                            <div class="detail-value">
                                {{ $application->start_date->translatedFormat('d M Y') }} 
                                s/d 
                                {{ $application->end_date->translatedFormat('d M Y') }}
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="detail-label">Unit Penempatan</div>
                            <div class="mt-1">
                                @foreach($application->work_units as $unit)
                                    <span class="badge bg-info bg-opacity-10 text-info border border-info me-1">{{ $unit }}</span>
                                @endforeach
                            </div>
                        </div>
                         <div class="col-12 mt-3">
                            <div class="detail-label">Pembimbing Lapangan</div>
                            <ul class="list-unstyled mb-0">
                                @foreach($application->supervisors as $spv)
                                    <li><i class="bi bi-person-check text-success me-2"></i>{{ $spv }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    <h5 class="section-header mt-4"><i class="bi bi-file-earmark-image me-2"></i>Dokumen</h5>
                     <div class="row">
                        <div class="col-12">
                            <div class="detail-label mb-2">Scan Kartu Identitas</div>
                            <a href="{{ asset('uploads/' . $application->identity_card_path) }}" target="_blank" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-eye me-1"></i> Lihat Dokumen
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>
@endsection