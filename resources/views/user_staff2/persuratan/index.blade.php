@extends('layouts-V2.master-layouts-v2')
@section('title', 'Sistem Persuratan')

@section('styles_admin')
    {{-- CSS untuk Datatables --}}
    <link rel="stylesheet" href="{{ asset('assetsv2/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assetsv2/compiled/css/table-datatable-jquery.css') }}">
@endsection

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Sistem Persuratan</h3>
                <p class="text-subtitle text-muted">Kelola surat masuk dan keluar secara digital.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <x-breadcrumb2 :items="[
                    ['label' => 'Dashboard', 'url' => route('root')],
                    ['label' => 'Persuratan', 'active' => true]
                ]" />
            </div>
        </div>
    </div>
</div>
<section class="section">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Kotak Masuk & Surat Keluar</h5>
            <a href="{{ route('persuratan.create') }}" class="btn btn-primary"><i class="bi bi-plus"></i> Buat Surat Baru</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped" id="table-persuratan">
                    <thead>
                        <tr>
                            <th>Judul Surat</th>
                            <th>Pembuat</th>
                            <th>Status</th>
                            <th>Penanggung Jawab</th>
                            <th>Tanggal Update</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($letters as $letter)
                        <tr>
                            <td>{{ $letter->title }}</td>
                            <td>{{ $letter->user->name ?? 'N/A' }}</td>
                            <td>
                                @php
                                    $statusClass = match($letter->status) {
                                        'Disetujui' => 'bg-success',
                                        'Ditolak' => 'bg-danger',
                                        'Revisi Diperlukan' => 'bg-warning',
                                        'Verifikasi Tambahan' => 'bg-info',
                                        'Menunggu Persetujuan Atasan' => 'bg-primary',
                                        default => 'bg-secondary',
                                    };
                                @endphp
                                <span class="badge {{ $statusClass }} text-capitalize">{{ str_replace('_', ' ', $letter->status) }}</span>
                            </td>
                            <td>{{ $letter->assignee->name ?? '-' }}</td>
                            <td>{{ $letter->updated_at->translatedFormat('d M Y') }}</td>
                            <td><a href="{{ route('persuratan.show', $letter->id) }}" class="btn btn-sm btn-primary">Lihat Detail</a></td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center">Tidak ada surat untuk ditampilkan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts_admin')
    {{-- JS untuk Datatables --}}
    <script src="{{ asset('assetsv2/extensions/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assetsv2/extensions/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assetsv2/extensions/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#table-persuratan').DataTable({
                "language": { "url": "{{ asset('assetsv2/extensions/datatables.net-bs5/js/Indonesian.json') }}" }
            });
        });
    </script>
@endsection