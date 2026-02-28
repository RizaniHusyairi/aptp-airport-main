@extends('layouts-V2.master-layouts-v2')
@section('title', 'Manajemen Rapat & Absensi')

@section('styles_admin')
    <link rel="stylesheet" href="{{ asset('assetsv2/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assetsv2/compiled/css/table-datatable-jquery.css') }}">
@endsection

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Manajemen Rapat & Absensi</h3>
                <p class="text-subtitle text-muted">Kelola jadwal rapat dan pantau kehadiran peserta.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <x-breadcrumb2 :items="[
                    ['label' => 'Dashboard', 'url' => route('root')],
                    ['label' => 'Rapat', 'active' => true]
                ]" />
            </div>
        </div>
    </div>
</div>

<section class="section">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Daftar Rapat</h5>
            <a href="{{ route('staff.meetings.create') }}" class="btn btn-primary"><i class="bi bi-plus-circle me-2"></i> Buat Rapat Baru</a>
        </div>
        <div class="card-body">
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            <div class="table-responsive">
                <table class="table table-striped" id="table-meetings">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Judul Rapat</th>
                            <th>Lokasi</th>
                            <th>Peserta Hadir</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($meetings as $meeting)
                        <tr>
                            <td data-sort="{{ $meeting->date->format('Y-m-d') }} {{ $meeting->start_time }}">
                                {{ $meeting->date->translatedFormat('d M Y') }}<br>
                                <small class="text-muted">{{ \Carbon\Carbon::parse($meeting->start_time)->format('H:i') }} WITA</small>
                            </td>
                            <td>
                                <span class="fw-bold">{{ $meeting->title }}</span><br>
                                <small class="text-muted">Oleh: {{ $meeting->organizer }}</small>
                            </td>
                            <td>{{ $meeting->location }}</td>
                            <td class="text-center">
                                <span class="badge bg-info">{{ $meeting->attendances_count }} Orang</span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $meeting->is_active ? 'success' : 'secondary' }}">
                                    {{ $meeting->is_active ? 'Aktif' : 'Selesai' }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    {{-- Tombol Salin Link (Nanti akan berfungsi setelah kita buat route publiknya) --}}
                                  
                                    <a href="{{ route('staff.meetings.show', $meeting->id) }}" class="btn btn-sm btn-info text-white" data-bs-toggle="tooltip" title="Detail & QR Code">
                                        <i class="bi bi-eye"></i>
                                    </a>

                                    {{-- === TOMBOL EDIT & HAPUS DITAMBAHKAN === --}}
                                    <a href="{{ route('staff.meetings.edit', $meeting->id) }}" class="btn btn-sm btn-warning text-white" data-bs-toggle="tooltip" title="Edit Agenda">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>

                                    <form action="{{ route('staff.meetings.destroy', $meeting->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus rapat ini? Data absensi terkait juga akan terhapus.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" data-bs-toggle="tooltip" title="Hapus Agenda">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
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
@endsection

@section('scripts_admin')
    <script src="{{ asset('assetsv2/extensions/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assetsv2/extensions/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assetsv2/extensions/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#table-meetings').DataTable({
                "language": { "url": "https://cdn.datatables.net/plug-ins/1.13.1/i18n/id.json" },
                "order": [[ 0, "desc" ]]
            });

            // Script Salin Link
            $('.copy-link').click(function() {
                const link = $(this).data('link');
                navigator.clipboard.writeText(link).then(() => {
                    alert('Link absensi berhasil disalin!');
                });
            });

            // Tooltip
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            })
        });
    </script>
@endsection