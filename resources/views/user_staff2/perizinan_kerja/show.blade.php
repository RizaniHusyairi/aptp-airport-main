@extends('layouts-V2.master-layouts-v2')
@section('title', 'Detail Izin Kerja')

@push('styles_admin')
    <link href="{{ asset('assetsv2/compiled/css/perizinan-kerja.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Detail Pengajuan Izin Kerja</h3>
                <p class="text-subtitle text-muted">Lihat detail pengajuan izin kerja Anda.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <x-breadcrumb2 :items="[
                    ['label' => 'Menu', 'url' => route('profile')],
                    ['label' => 'Izin Kerja', 'url' => auth()->user()->is_staff ? route('kerja.index') : route('kerja.userindex')],
                    ['label' => 'Detail Pengajuan', 'active' => true]
                ]" />
            </div>
        </div>
    </div>
</div>

<section class="section">
    <div class="row">
        <!-- Kolom Kiri: Detail Pengajuan -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Informasi Pengajuan</h5>
                </div>
                <div class="card-body">
                    <!-- Info Pengaju -->
                    <div class="detail-section">
                        <h6>Diajukan oleh:</h6>
                        <div class="d-flex align-items-center">
                            <div class="avatar avatar-md me-3">
                                <img src="{{ asset('../assetsv2/compiled/jpg/2.jpg') }}" alt="Avatar">
                            </div>
                            <div>
                                <p class="mb-0 fw-bold">{{ $workPermit->user->name }}</p>
                                <p class="text-muted mb-0">{{ $workPermit->user->email }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Detail Pekerjaan -->
                    <div class="detail-section">
                        <h6>Detail Pekerjaan:</h6>
                        <ul class="detail-list">
                            <li><strong>Jenis Pekerjaan:</strong> {{ $workPermit->work_type }}</li>
                            <li><strong>Lokasi:</strong> {{ $workPermit->location }}</li>
                            <li><strong>Jadwal:</strong> {{ \Carbon\Carbon::parse($workPermit->start_date)->translatedFormat('d M Y, H:i') }} s/d {{ \Carbon\Carbon::parse($workPermit->end_date)->translatedFormat('d M Y, H:i') }}</li>
                        </ul>
                        <p class="mt-3"><strong>Deskripsi:</strong><br>{{ $workPermit->description }}</p>
                    </div>

                    <!-- Daftar Pekerja & Peralatan -->
                    
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Status & Dokumen -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Status & Dokumen</h5>
                </div>
                <div class="card-body">
                    <!-- Dokumen Terlampir -->
                    <div class="detail-section">
                        <h6>Dokumen Pendukung:</h6>
                        @forelse($workPermit->documents as $docPath)
                            <a href="{{ Storage::url($docPath) }}" target="_blank" class="document-link">
                                <i class="bi bi-file-earmark-pdf-fill"></i>
                                <span>{{ basename($docPath) }}</span>
                            </a>
                        @empty
                            <p class="text-muted">Tidak ada dokumen terlampir.</p>
                        @endforelse
                    </div>

                    {{-- Tampilkan catatan staff jika ada (untuk Pengaju) --}}
                    @notstaff
                        @if($workPermit->staff_notes)
                        <div class="detail-section">
                            <h6>Catatan dari Staff:</h6>
                            <div class="alert alert-light-warning">
                                <p class="mb-0">{{ $workPermit->staff_notes }}</p>
                            </div>
                        </div>
                        @endif
                    @endnotstaff

                    {{-- Form Tindakan hanya untuk Staff --}}
                    @staff
                    <div class="detail-section">
                        <h6>Tindakan</h6>
                        <form action="{{ route('kerja.updateStatus', $workPermit->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <div class="form-group">
                                <label for="status" class="form-label">Ubah Status</label>
                                <select name="status" id="status" class="form-select">
                                    <option value="Diajukan" @selected($workPermit->status == 'Diajukan')>Diajukan</option>
                                    <option value="Disetujui" @selected($workPermit->status == 'Disetujui')>Setujui</option>
                                    <option value="Ditolak" @selected($workPermit->status == 'Ditolak')>Tolak</option>
                                    <option value="Revisi Diperlukan" @selected($workPermit->status == 'Revisi Diperlukan')>Minta Revisi</option>
                                </select>
                            </div>
                            <div class="form-group" id="staff-notes-container" style="display: none;">
                                <label for="staff_notes" class="form-label">Catatan Revisi / Alasan Penolakan</label>
                                <textarea name="staff_notes" id="staff_notes" class="form-control" rows="4" placeholder="Berikan catatan jika pengajuan ditolak atau memerlukan revisi...">{{ $workPermit->staff_notes }}</textarea>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Simpan Status</button>
                            </div>
                        </form>
                    </div>
                    @endstaff
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('scripts_admin')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const statusSelect = document.getElementById('status');
        const notesContainer = document.getElementById('staff-notes-container');

        // Fungsi untuk menampilkan atau menyembunyikan kolom catatan
        const toggleNotesVisibility = () => {
            const selectedValue = statusSelect.value;
            // Tampilkan jika statusnya 'Ditolak' atau 'Revisi Diperlukan'
            if (selectedValue === 'Ditolak' || selectedValue === 'Revisi Diperlukan') {
                notesContainer.style.display = 'block';
            } else {
                notesContainer.style.display = 'none';
            }
        };

        // Jalankan fungsi saat halaman pertama kali dimuat
        toggleNotesVisibility();

        // Tambahkan event listener untuk memantau perubahan pada dropdown
        statusSelect.addEventListener('change', toggleNotesVisibility);
    });
</script>
@endsection
