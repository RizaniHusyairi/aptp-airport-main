@extends('layouts-V2.master-layouts-v2')
@section('title', 'Pengajuan Sertifikat Saya')

@section('content')
<section class="section">
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h4>Riwayat Pengajuan OJT</h4>
            <a href="{{ route('user.ojt.create') }}" class="btn btn-primary"><i class="bi bi-plus"></i> Ajukan Baru</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Tanggal Pengajuan</th>
                            <th>Institusi</th>
                            <th>Status</th>
                            <th>Sertifikat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($applications as $app)
                        <tr>
                            <td>{{ $app->created_at->format('d M Y') }}</td>
                            <td>{{ $app->institution }}</td>
                            <td>
                                @if($app->status == 'Menunggu Verifikasi')
                                    <span class="badge bg-warning">Menunggu Verifikasi</span>
                                @elseif($app->status == 'Selesai')
                                    <span class="badge bg-success">Selesai / Terbit</span>
                                @else
                                    <span class="badge bg-secondary">{{ $app->status }}</span>
                                @endif
                            </td>
                            <td>
                                @if($app->status == 'Selesai' && $app->final_certificate_path)
                                    <a href="{{ asset('uploads/' . $app->final_certificate_path) }}" target="_blank" class="btn btn-sm btn-success">
                                        <i class="bi bi-download"></i> Download
                                    </a>
                                @elseif($app->status == 'Selesai')
                                    {{-- Jika staff generate via sistem TTE, arahkan ke route cetak --}}
                                    <a href="{{ route('staff.ojt.certificate', ['student' => $app->id]) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-printer"></i> Cetak Digital
                                    </a>
                                @else
                                    <span class="text-muted small">Belum tersedia</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('user.ojt.show', $app->id) }}" class="btn btn-sm btn-info text-white me-1">
                                    <i class="bi bi-eye"></i> Detail
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center">Belum ada pengajuan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
@endsection