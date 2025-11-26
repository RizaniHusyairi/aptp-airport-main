@extends('layouts-V2.master-layouts-v2')
@section('title', 'Manajemen Event Posko')
@section('styles_admin')
    <link rel="stylesheet" href="{{ asset('assetsv2/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assetsv2/compiled/css/table-datatable-jquery.css') }}">
@endsection

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Daftar Event Posko</h3>
                <p class="text-subtitle text-muted">Kelola periode posko (Nataru, Lebaran, dll).</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                 <x-breadcrumb2 :items="[
                        ['label' => 'Dashboard', 'url' => route('staff.dashboard.index')],
                        ['label' => 'Event Posko', 'active' => true]
                    ]" />
            </div>
        </div>
    </div>
</div>

<section class="section">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Daftar Event</h5>
            <a href="{{ route('staff.nataru-events.create') }}" class="btn btn-primary"><i class="bi bi-plus"></i> Buat Event Baru</a>
        </div>
        <div class="card-body">
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Nama Event</th>
                            <th>Periode</th>
                            <th>Status</th>
                            <th>Link Publik (Untuk Petugas)</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($events as $event)
                        <tr>
                            <td>
                                <strong>{{ $event->name }}</strong><br>
                                <small class="text-muted">{{ Str::limit($event->description, 50) }}</small>
                            </td>
                            <td>
                                {{ $event->start_date->format('d M Y') }} - {{ $event->end_date->format('d M Y') }}
                            </td>
                            <td>
                                <span class="badge bg-{{ $event->is_active ? 'success' : 'secondary' }}">
                                    {{ $event->is_active ? 'Aktif' : 'Selesai' }}
                                </span>
                            </td>
                            <td>
                                {{-- Kita akan buat route publiknya di langkah selanjutnya, sementara pakai placeholder --}}
                                <div class="input-group input-group-sm">
                                    {{-- Asumsi route publik nanti bernama 'public.nataru.form' --}}
                                    <input type="text" class="form-control" value="{{ url('posko/input/' . $event->public_token) }}" readonly id="link-{{ $event->id }}">
                                    <button class="btn btn-outline-secondary" type="button" onclick="copyLink('link-{{ $event->id }}')">
                                        <i class="bi bi-clipboard"></i>
                                    </button>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('staff.nataru-events.edit', $event->id) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                                    <form action="{{ route('staff.nataru-events.destroy', $event->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus event ini? Data penerbangan di dalamnya akan ikut terhapus!')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                    {{-- TOMBOL DETAIL (YANG KITA UBAH) --}}
                                    <a href="{{ route('staff.nataru-events.show', $event->id) }}" class="btn btn-sm btn-info text-white" title="Lihat Data Penerbangan">
                                        <i class="bi bi-eye"></i> Detail Data
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">Belum ada event posko yang dibuat.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

@endsection
@section('scripts_admin')
<script src="{{ asset('assetsv2/extensions/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assetsv2/extensions/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>

<script>
    function copyLink(elementId) {
        var copyText = document.getElementById(elementId);
        copyText.select();
        copyText.setSelectionRange(0, 99999); /* For mobile devices */
        navigator.clipboard.writeText(copyText.value);
        alert("Link berhasil disalin: " + copyText.value);
    }
</script>
@endsection