@extends('layouts-V2.master-layouts-v2')
@section('title', 'Detail Rapat')

@section('styles_admin')
    {{-- CSS GLightbox --}}
    <link rel="stylesheet" href="{{ asset('assets_landing/vendor/glightbox/css/glightbox.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assetsv2/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assetsv2/compiled/css/table-datatable-jquery.css') }}">
    <style>
        /* Sedikit styling agar tanda tangan terlihat jelas di tabel */
        .signature-thumb {
            height: 40px;
            width: auto;
            max-width: 100px;
            background-color: #fff; /* Latar putih agar tinta hitam terlihat */
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 2px;
            cursor: pointer;
            transition: transform 0.2s;
        }
        .signature-thumb:hover {
            transform: scale(1.1);
            border-color: #aaa;
        }

        /* Memaksa gambar di dalam lightbox memiliki background putih */
        .gslide-image img {
            background-color: #ffffff !important;
        }
    </style>
@endsection

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Detail Rapat & Absensi</h3>
                <p class="text-subtitle text-muted">{{ $meeting->title }}</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <x-breadcrumb2 :items="[
                    ['label' => 'Rapat', 'url' => route('staff.meetings.index')],
                    ['label' => 'Detail', 'active' => true]
                ]" />
            </div>
        </div>
    </div>
</div>

<section class="section">
    <div class="row">
        {{-- KOLOM KIRI: Detail & QR Code --}}
        <div class="col-md-4">
            <div class="card">
                <div class="card-header text-center">
                    <h4>QR Code Absensi</h4>
                </div>
                <div class="card-body text-center">
                    {{-- Container QR Code --}}
                    <div id="qrcode" class="d-flex justify-content-center mb-3"></div>
                    
                    <p class="text-muted small mb-1">Scan untuk isi absen</p>
                    <div class="input-group input-group-sm mb-3">
                        <input type="text" class="form-control" value="{{ $publicUrl }}" id="meetingLink" readonly>
                        <button class="btn btn-primary" onclick="copyLink()"><i class="bi bi-clipboard"></i></button>
                    </div>

                    <hr>
                    
                    <div class="text-start">
                        <p><strong>Tanggal:</strong> {{ $meeting->date->translatedFormat('d F Y') }}</p>
                        <p><strong>Jam:</strong> {{ \Carbon\Carbon::parse($meeting->start_time)->format('H:i') }} WITA</p>
                        <p><strong>Lokasi:</strong> {{ $meeting->location }}</p>
                        <p><strong>Penyelenggara:</strong> {{ $meeting->organizer }}</p>
                        <p><strong>Status:</strong> 
                            <span class="badge bg-{{ $meeting->is_active ? 'success' : 'danger' }}">
                                {{ $meeting->is_active ? 'Dibuka' : 'Ditutup' }}
                            </span>
                        </p>
                    </div>

                    <form action="{{ route('staff.meetings.toggle', $meeting->id) }}" method="POST" class="d-grid gap-2 mt-3">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-{{ $meeting->is_active ? 'danger' : 'success' }}">
                            {{ $meeting->is_active ? 'Tutup Absensi' : 'Buka Absensi' }}
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- KOLOM KANAN: Daftar Hadir --}}
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Daftar Peserta Hadir ({{ $meeting->attendances->count() }})</h5>
                    <div class="d-flex gap-2">
                        {{-- === PERUBAHAN: Tombol Export PDF === --}}
                        <a href="{{ route('staff.meetings.exportPdf', $meeting->id) }}" class="btn btn-sm btn-danger" target="_blank">
                            <i class="bi bi-file-earmark-pdf"></i> Export PDF
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped" id="table-attendance">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Peserta</th>
                                    <th>Instansi / Unit</th>
                                    <th>Waktu Absen</th>
                                    <th>Tanda Tangan</th> {{-- <<< KOLOM BARU --}}
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($meeting->attendances as $attendance)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $attendance->name }}</td>
                                    <td>
                                        {{ $attendance->department }}<br>
                                        <small class="text-muted">{{ $attendance->phone ?? '-' }}</small>
                                    </td>
                                    <td>{{ $attendance->created_at->format('H:i') }}</td>
                                    <td>
                                        @if($attendance->signature)
                                            {{-- Tampilkan Thumbnail + Lightbox --}}
                                            <a href="{{ asset('uploads/' . $attendance->signature) }}" class="glightbox" data-description="Tanda tangan: {{ $attendance->name }}">
                                                <img src="{{ asset('uploads/' . $attendance->signature) }}" class="signature-thumb" alt="TTD">
                                            </a>
                                        @else
                                            <span class="badge bg-light-secondary text-muted">Tidak ada</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{-- TOMBOL HAPUS --}}
                                        <form action="{{ route('staff.meetings.destroyAttendance', $attendance->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus peserta ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" data-bs-toggle="tooltip" title="Hapus Peserta">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty

                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts_admin')
    {{-- Library QR Code JS --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    {{-- JS GLightbox --}}
    <script src="{{ asset('assets_landing/vendor/glightbox/js/glightbox.min.js') }}"></script> 
    
    <script src="{{ asset('assetsv2/extensions/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assetsv2/extensions/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assetsv2/extensions/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            // Init DataTable
            $('#table-attendance').DataTable({
                "language": { "url": "https://cdn.datatables.net/plug-ins/1.13.1/i18n/id.json" },
                "ordering": false // Matikan sorting agar nomor urut tetap rapi
            });

            // Init GLightbox untuk tanda tangan
            const lightbox = GLightbox({
                selector: '.glightbox',
                touchNavigation: true,
                loop: false
            });

            // Generate QR Code
            new QRCode(document.getElementById("qrcode"), {
                text: "{{ $publicUrl }}",
                width: 180,
                height: 180,
                colorDark : "#000000",
                colorLight : "#ffffff",
                correctLevel : QRCode.CorrectLevel.H
            });
        });

        function copyLink() {
            var copyText = document.getElementById("meetingLink");
            copyText.select();
            copyText.setSelectionRange(0, 99999);
            navigator.clipboard.writeText(copyText.value);
            alert("Link berhasil disalin: " + copyText.value);
        }
    </script>
@endsection