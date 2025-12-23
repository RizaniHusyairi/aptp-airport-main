@extends('layouts-V2.master-layouts-v2')
@section('title', 'Manajemen Field Trip')
@section('styles_admin')
    <link rel="stylesheet" href="{{ asset('assetsv2/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assetsv2/compiled/css/table-datatable-jquery.css') }}">
@endsection
@section('content')
<div class="page-heading">
                    <div class="page-title">
                        <div class="row">
                            <div class="col-12 col-md-6 order-md-1 order-last">
                                <h3>Manajemen Field Trip</h3>
                                <p class="text-subtitle text-muted">Lihat data pengajuan Field Trip.</p>
                            </div>
                            <div class="col-12 col-md-6 order-md-2 order-first">
                                <x-breadcrumb2 :items="[
                                        ['label' => 'Menu', 'url' => route('staff.dashboard.index')],
                                        ['label' => 'Field Trip', 'active' => true]
                                    ]" />
                                
                            </div>
                        </div>
                    </div>
                    <section class="section">
                        <div class="card">
                            @if(session('success'))
                            <div class="alert alert-primary alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                            @endif
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="card-title">Daftar Pengajuan Field Trip</h5>
                                @notadmin
                                @notstaff
                                    <a href="{{ route('fieldtrip.create') }}" class="btn btn-primary"><i class="bi bi-plus"></i> Tambah Pengajuan</a>
                                @endnotstaff
                                @endnotadmin
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped" id="table-FieldTrip">
                                        <thead>
                                            <tr>
                                                <th>Nama Field Trip</th>
                                                <th>Tanggal Pengajuan</th>
                                                <th>Status</th>
                                                @staff
                                                <th>Pengaju</th>
                                                @endstaff
                                                <th>Aksi</th>
                                            </tr>
                                            
                                        </thead>
                                        <tbody>
                                                    
                                                @forelse ($fieldtrips as $fieldtrip)
                                                <tr>
                                                    {{-- 1. Nama Field Trip --}}
                                                    <td>
                                                        <div class="fw-bold">{{ $fieldtrip->fieldtrip_name }}</div>
                                                        <small class="text-muted">{{ Str::limit($fieldtrip->fieldtrip_type, 30) }}</small>
                                                    </td>

                                                    {{-- 2. Tanggal Pengajuan --}}
                                                    <td>
                                                        {{ $fieldtrip->created_at->format('d M Y') }}
                                                        <div class="small text-muted">{{ $fieldtrip->created_at->format('H:i') }}</div>
                                                    </td>

                                                    {{-- 3. Status --}}
                                                    <td>
                                                        @php
                                                            $status = $fieldtrip->submission_status;
                                                            $badgeClass = match($status) {
                                                                'Disetujui' => 'bg-success',
                                                                'Ditolak'   => 'bg-danger',
                                                                'Revisi Diperlukan' => 'bg-warning',
                                                                default     => 'bg-info', // Diajukan
                                                            };
                                                        @endphp
                                                        <span class="badge {{ $badgeClass }}">{{ ucfirst($status) }}</span>
                                                    </td>

                                                    {{-- 4. Nama Pengaju --}}
                                                    @staff
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="avatar avatar-sm me-2">
                                                                <img src="{{ $fieldtrip->users->first()->avatar_url ?? asset('assetsv2/compiled/jpg/1.jpg') }}" alt="Avatar">
                                                            </div>
                                                            <span>{{ $fieldtrip->users->first()->name ?? 'Tanpa Nama' }}</span>
                                                        </div>
                                                    </td>
                                                    @endstaff
                                                    {{-- 5. Aksi --}}
                                                    <td>
                                                        <div class="d-flex gap-2">
                                                            {{-- Tombol Detail (Untuk Semua) --}}
                                                            @php
                                                                // Tentukan route detail berdasarkan role
                                                                $detailRoute = auth()->user()->is_staff 
                                                                    ? route('fieldtrip.show', $fieldtrip->id) 
                                                                    : route('fieldtrip.userShow', $fieldtrip->id);
                                                            @endphp
                                                            
                                                            <a href="{{ $detailRoute }}" class="btn btn-sm btn-info text-white" title="Lihat Detail">
                                                                <i class="bi bi-eye"></i>
                                                            </a>

                                                            {{-- Tombol Hapus (Hanya untuk User Pengaju jika status masih Diajukan) --}}
                                                            @if(!auth()->user()->is_staff && $fieldtrip->submission_status == 'Diajukan')
                                                                <form action="{{ route('fieldtrip.destroy', $fieldtrip->id) }}" method="POST" onsubmit="return confirm('Yakin ingin membatalkan pengajuan ini?')">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-sm btn-danger" title="Hapus Pengajuan">
                                                                        <i class="bi bi-trash"></i>
                                                                    </button>
                                                                </form>
                                                            @endif
                                                        </div>
                                                    </td>
                                                </tr>
                                                @empty
                                                {{-- Data Kosong handled by Datatable usually, but safe to leave empty here --}}
                                                @endforelse
                                        </tbody>
                                            @staff
                                        <tbody>
                                                    @forelse ($fieldtrips as $index => $fieldtrip)
                                                    <tr data-id="">
                                                        <td>{{ $fieldtrip->documents ? preg_replace('/^\d+_/', '', basename($fieldtrip->documents)) : '-' }}</td>
                                                        <td>{{ $fieldtrip->created_at->format('d M Y H:i') }}</td>
                                                        <td>
                                                        @php
                                                            $status = $fieldtrip->submission_status;
                                                            $badgeClass = match($status) {
                                                                'Disetujui' => 'bg-success',
                                                                'Ditolak' => 'bg-danger',
                                                                'Revisi Diperlukan' => 'bg-warning',
                                                                default => 'bg-info',
                                                            };
                                                        @endphp
                                                        <span class="badge {{ $badgeClass }}">{{ ucfirst($status) }}</span>
                                                        </td>
                                                        <td>
                                                        @foreach ($fieldtrip->users as $user)
                                                            <span class="badge bg-secondary">{{ $user->name }}</span>
                                                        @endforeach
                                                        </td>
                                                        <td>
                                                        <div class="row g-1">
                                                            <div class="col-12 mb-1">
                                                            <a href="{{ route('fieldtrip.show', $fieldtrip->id) }}" class="btn btn-sm btn-primary text-white btn-tooltip" data-bs-toggle="tooltip" title="Lihat Detail">
                                                                <i class="bi bi-eye"></i>
                                                            </a>
                                                            </div>
                                                        </div>
                                                        </td>
                                                    </tr>
                                                    @empty
                                                    
                                                    @endforelse
                                                
                                                </tbody>
                                                @endstaff
                                            
                                    </table>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>

@endsection
@section('scripts_admin')
    <script src="{{ asset('assetsv2/extensions/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assetsv2/extensions/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>    
    <script src="{{ asset('assetsv2/compiled/js/staff-fieldtrip.js') }}"></script>
@endsection