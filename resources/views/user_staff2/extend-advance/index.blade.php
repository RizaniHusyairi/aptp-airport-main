@extends('layouts-V2.master-layouts-v2')
@section('title', 'Pengajuan Extend Advance')

@section('styles_admin')
    <link rel="stylesheet" href="{{ asset('assetsv2/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assetsv2/compiled/css/table-datatable-jquery.css') }}">
@endsection

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Pengajuan Extend / Advance Hour</h3>
                <p class="text-subtitle text-muted">Daftar pengajuan yang telah Anda buat atau kelola.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <x-breadcrumb2 :items="[
                    ['label' => 'Menu', 'url' => route('profile')],
                    ['label' => 'Extend Advance', 'active' => true],
                ]" />
            </div>
        </div>
    </div>
</div>
<section class="section">
    <div class="card">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show m-4" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Daftar Pengajuan</h5>
            @staff
                {{-- <<< TOMBOL BARU UNTUK STAFF >>> --}}
                <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#statementModal">
                    <i class="bi bi-pencil-square"></i> Ubah Teks Pernyataan
                </button>
                @endstaff
            @notstaff
            <a href="{{ route('extend-advance.create') }}" class="btn btn-primary"><i class="bi bi-plus"></i> Tambah Pengajuan</a>
            @endnotstaff
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped" id="table-data">
                    <thead>
                        <tr>
                            @staff <th>Pengaju</th> @endstaff
                            <th>Operator</th>
                            <th>No. Penerbangan</th>
                            <th>Tanggal Terbang</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($submissions as $submission)
                        <tr>
                            @staff <td>{{ $submission->user->name }}</td> @endstaff
                            <td>{{ $submission->operator }}</td>
                            <td>{{ $submission->registration_and_flight_number }}</td>
                            <td>{{ $submission->flight_date->format('d M Y') }}</td>
                            <td>
                                @php
                                    $statusClass = match($submission->submission_status) {
                                        'Disetujui' => 'bg-success',
                                        'Ditolak' => 'bg-danger',
                                        'Revisi Diperlukan' => 'bg-warning',
                                        default => 'bg-info',
                                    };
                                @endphp
                                <span class="badge {{ $statusClass }}">{{ $submission->submission_status }}</span>
                            </td>
                            <td>
                                <a href="{{ auth()->user()->is_staff ? route('extend-advance.show', $submission->id) : route('extend-advance.userShow', $submission->id) }}" class="btn btn-sm btn-primary">Lihat Detail</a>
                            </td>
                        </tr>
                        @empty
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

@staff
{{-- ========================================================== --}}
{{-- ===          MODAL BARU UNTUK EDIT PERNYATAAN          === --}}
{{-- ========================================================== --}}

<div class="modal fade" id="statementModal" tabindex="-1" aria-labelledby="statementModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form class="modal-content" method="POST" action="{{ route('extend-advance.settings.statement.update') }}">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="statementModalLabel">Ubah Teks Pernyataan Tanggung Jawab</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted">Teks yang Anda ubah di sini akan ditampilkan di formulir pengajuan untuk semua pengguna.</p>
                <div class="form-group">
                    {{-- === PERBAIKAN DI SINI: Menghapus old() agar selalu menampilkan data terbaru === --}}
                    <textarea class="form-control" name="statement_notes" rows="8" required>{{ $statementText ?? '' }}</textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endstaff


@endsection

@section('scripts_admin')
    <script src="{{ asset('assetsv2/extensions/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assetsv2/extensions/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assetsv2/extensions/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assetsv2/compiled/js/staff-extend.js') }}"></script>

@endsection
