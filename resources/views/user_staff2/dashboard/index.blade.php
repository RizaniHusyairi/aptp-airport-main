@extends('layouts-V2.master-layouts-v2')
@section('title', 'Dasbor Staff')

@section('styles_admin')
    <style>
        .icon-dashboard {
            font-size: 2.5rem;
        }
    </style>
@endsection

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Dashboard Staff</h3>
                <p class="text-subtitle text-muted">Ringkasan aktivitas dan tugas Anda, {{ Auth::user()->name }}.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <x-breadcrumb2 :items="[
                    ['label' => 'Dasbor', 'active' => true]
                ]" />
            </div>
        </div>
    </div>
</div>

<div class="page-content">
    <section class="row">
        {{-- ====================================================== --}}
        {{-- ===            KARTU RINGKASAN (WIDGETS)           === --}}
        {{-- ====================================================== --}}

        {{-- Widget Total Pengajuan Layanan Menunggu --}}
        @if($permissions->contains('Manajemen Tenant') || $permissions->contains('Manajemen Sewa') || $permissions->contains('Manajemen Extend Advance') || $permissions->contains('Manajemen Slot Charter'))
        <div class="col-6 col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body px-4 py-4-5">
                    <div class="row">
                        <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                            <div class="stats-icon blue mb-2">
                                <i class="icon-dashboard bi bi-journal-check"></i>
                            </div>
                        </div>
                        <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                            <h6 class="text-muted font-semibold">Pengajuan Layanan</h6>
                            <h6 class="font-extrabold mb-0">{{ number_format($data['pending_submissions_count']) }} <span class="text-sm text-muted">Menunggu</span></h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- Widget Pengaduan Menunggu --}}
        @if($permissions->contains('Manajemen Pengaduan'))
        <div class="col-6 col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body px-4 py-4-5">
                    <div class="row">
                        <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                            <div class="stats-icon red mb-2">
                                <i class="icon-dashboard bi bi-exclamation-octagon-fill"></i>
                            </div>
                        </div>
                        <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                            <h6 class="text-muted font-semibold">Pengaduan</h6>
                            <h6 class="font-extrabold mb-0">{{ number_format($data['pending_complaints_count']) }} <span class="text-sm text-muted">Menunggu</span></h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- Widget Ajuan Informasi Publik Menunggu --}}
        @if($permissions->contains('Manajemen Ajuan Informasi Publik'))
        <div class="col-6 col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body px-4 py-4-5">
                    <div class="row">
                        <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                            <div class="stats-icon green mb-2">
                                <i class="icon-dashboard bi bi-info-circle-fill"></i>
                            </div>
                        </div>
                        <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                            <h6 class="text-muted font-semibold">Ajuan Informasi</h6>
                            <h6 class="font-extrabold mb-0">{{ number_format($data['pending_public_info_count']) }} <span class="text-sm text-muted">Menunggu</span></h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- Widget Data LLAU Bulan Ini --}}
        @if($permissions->contains('Manajemen Lalu Lintas Angkutan Udara'))
        <div class="col-6 col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body px-4 py-4-5">
                    <div class="row">
                        <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                            <div class="stats-icon purple mb-2">
                                <i class="icon-dashboard bi bi-graph-up-arrow"></i>
                            </div>
                        </div>
                        <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                            <h6 class="text-muted font-semibold">Log LLAU</h6>
                            <h6 class="font-extrabold mb-0">{{ number_format($data['llau_logs_this_month']) }} <span class="text-sm text-muted">Bulan Ini</span></h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        
        {{-- === WIDGET BARU: Inventaris === --}}
        @if($permissions->contains('Manajemen Inventaris'))
        <div class="col-6 col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body px-4 py-4-5">
                    <div class="row">
                        <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                            <div class="stats-icon red mb-2">
                                <i class="icon-dashboard bi bi-box-seam"></i>
                            </div>
                        </div>
                        <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                            <h6 class="text-muted font-semibold">Inventaris</h6>
                            <h6 class="font-extrabold mb-0">{{ number_format($data['maintenance_inventory_count']) }} <span class="text-sm text-muted">Pemeliharaan</span></h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- === WIDGET BARU: Program Kerja === --}}
        @if($permissions->contains('Manajemen Program Kerja'))
        <div class="col-6 col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body px-4 py-4-5">
                    <div class="row">
                        <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                            <div class="stats-icon green mb-2">
                                <i class="icon-dashboard bi bi-clipboard-check"></i>
                            </div>
                        </div>
                        <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                            @php
                                // Definisikan role Kanit di sini agar view tidak error
                                $kanitRoles = [
                                    'Kanit'
                                ];
                            @endphp
                            @if(Auth::user()->hasRole($kanitRoles))
                                <h6 class="text-muted font-semibold">Tugas Perlu Verifikasi</h6>
                                <h6 class="font-extrabold mb-0">{{ number_format($data['tasks_awaiting_verification']) }} <span class="text-sm text-muted">Tugas</span></h6>
                            @else
                                <h6 class="text-muted font-semibold">Tugas Perlu Revisi</h6>
                                <h6 class="font-extrabold mb-0">{{ number_format($data['tasks_needing_revision'] ?? 0) }} <span class="text-sm text-muted">Tugas</span></h6>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </section>

    <section class="row">
        {{-- ====================================================== --}}
        {{-- ===              DAFTAR TUGAS TERBARU              === --}}
        {{-- ====================================================== --}}
        
        {{-- Kolom Ajuan Informasi Publik Terbaru --}}
        @if($permissions->contains('Manajemen Ajuan Informasi Publik') && $data['recent_public_info']->isNotEmpty())
        <div class="col-lg-6 col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Ajuan Informasi Publik Terbaru (Menunggu)</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>Pengaju</th>
                                    <th>Informasi Diminta</th>
                                    <th>Tgl. Ajuan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data['recent_public_info'] as $info)
                                <tr>
                                    <td>{{ $info->user->name ?? 'N/A' }}</td>
                                    <td>
                                        <a href="{{ route('informasiPublik.staffShow', $info->id) }}">
                                            {{ Str::limit($info->information_details, 50) }}
                                        </a>
                                    </td>
                                    <td>{{ $info->created_at->diffForHumans() }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- Kolom Pengaduan Terbaru --}}
        @if($permissions->contains('Manajemen Pengaduan') && $data['recent_complaints']->isNotEmpty())
        <div class="col-lg-6 col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Pengaduan Terbaru (Menunggu)</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Kategori</th>
                                    <th>Tgl. Aduan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data['recent_complaints'] as $complaint)
                                <tr>
                                    <td>{{ $complaint->name }}</td>
                                    <td>
                                        {{-- Ganti 'pengaduan.staffShow' jika nama route-nya berbeda --}}
                                        <a href="{{ route('pengaduan.staffIndex') }}?complaint_id={{ $complaint->id }}">
                                            {{ $complaint->subject }}
                                        </a>
                                    </td>
                                    <td>{{ $complaint->created_at->diffForHumans() }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @endif

    </section>
</div>
@endsection