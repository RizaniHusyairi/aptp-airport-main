@extends('layouts-V2.master-layouts-v2')
@section('title', 'Detail Inventaris: ' . $inventory->name)

@section('styles_admin')
    <link rel="stylesheet" href="{{ asset('assets_landing/vendor/glightbox/css/glightbox.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assetsv2/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assetsv2/compiled/css/table-datatable-jquery.css') }}">
    <style>
        /* Gaya untuk timeline */
        .timeline { position: relative; padding-left: 1.5rem; }
        .timeline::before { content: ''; position: absolute; left: 6px; top: 0; bottom: 0; width: 2px; background: #e9ecef; }
        .timeline-item { position: relative; margin-bottom: 1.5rem; }
        .timeline-item::before { content: ''; position: absolute; left: -1.45rem; top: .3rem; width: 1rem; height: 1rem; border-radius: 50%; border: 2px solid #fff;}
        .timeline-item.status-baik::before { background: var(--bs-success); }
        .timeline-item.status-pemeliharaan::before { background: var(--bs-warning); }

        html[data-bs-theme=dark] .logbook-documentation {
            background: #101316;
        }
        /* Gaya untuk dokumentasi logbook */
        .logbook-documentation {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            padding: 10px;
            background: #d9d9da;
            border-radius: 8px;
        }
        .logbook-documentation img {
            height: 80px;
            width: 80px;
            object-fit: cover;
            border-radius: 5px;
        }
    </style>
@endsection

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Detail Inventaris</h3>
                <p class="text-subtitle text-muted">Informasi lengkap untuk {{ $inventory->name }}.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <x-breadcrumb2 :items="[
                    ['label' => 'Dashboard', 'url' => route('root')],
                    ['label' => 'Inventaris', 'url' => route('staff.inventories.index')],
                    ['label' => 'Detail', 'active' => true]
                ]" />
            </div>
        </div>
    </div>
</div>

<section class="section">
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
     @if(session('info'))
    <div class="alert alert-info alert-dismissible fade show" role="alert">
        {{ session('info') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul> @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach </ul>
    </div>
    @endif

    <div class="row">
        <div class="col-md-5">
            {{-- Card Informasi Alat --}}
            <div class="card">
                <div class="card-header"><h5 class="card-title mb-0">Informasi Alat</h5></div>
                <div class="card-body">
                    <div class="mb-3 text-center">
                        <a href="{{ asset('uploads/' . $inventory->photo_path) }}" class="glightbox" data-gallery="inventaris-gallery">
                            <img src="{{ asset('uploads/' . $inventory->photo_path) }}" alt="{{ $inventory->name }}" style="width: 100%; max-height: 250px; object-fit: cover;" class="rounded img-thumbnail">
                        </a>
                    </div>
                    <p><strong>Nama Alat:</strong> {{ $inventory->name }}</p>
                    <p><strong>Tanggal Input:</strong> {{ $inventory->input_date?->translatedFormat('d F Y') }}</p>
                    <p><strong>Status Saat Ini:</strong>
                        @php $statusClass = $inventory->status == 'Baik' ? 'success' : 'warning'; @endphp
                        <span class="badge bg-{{ $statusClass }}">{{ $inventory->status }}</span>
                    </p>
                    @if($inventory->status == 'Pemeliharaan' && $inventory->maintenance_report_link)
                    <p><strong>Laporan Pemeliharaan:</strong> <a href="{{ $inventory->maintenance_report_link }}" target="_blank">Lihat Laporan</a></p>
                    @endif

                    <div class="d-flex gap-2 mt-4 flex-wrap">
                        <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#statusModal-{{ $inventory->id }}">
                            Ubah Status Kondisi
                        </button>
                         <a href="{{ route('staff.inventories.edit', $inventory->id) }}" class="btn btn-warning btn-sm">Edit Detail Alat</a>
                    </div>
                </div>
            </div>

            
        </div>
        <div class="col-md-7">
            {{-- Card Riwayat Status --}}
            <div class="card">
                <div class="card-header"><h5 class="card-title mb-0">Riwayat Status Kondisi</h5></div>
                <div class="card-body">
                    <div class="timeline">
                        @forelse($inventory->statusLogs as $log)
                        <div class="timeline-item status-{{ strtolower($log->new_status ?? 'baik') }}">
                            <div class="fw-semibold">Status diubah menjadi "{{ $log->new_status }}"</div>
                            <div class="small text-muted">
                                {{ $log->created_at?->translatedFormat('d M Y H:i') }}
                                @if($log->user)
                                    • oleh {{ $log->user->name }}
                                @endif
                        </div>
                            @if($log->notes)
                            <div class="mt-1 small">
                                <strong>Laporan:</strong> <a href="{{ $log->notes }}" target="_blank">Lihat</a>
                            </div>
                            @endif
                        </div>
                        @empty
                        <p class="text-muted">Belum ada riwayat perubahan status.</p>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>
        <div class="col-md-12">
            {{-- ================================================ --}}
            {{-- ===            BAGIAN LOGBOOK BARU           === --}}
            {{-- ================================================ --}}
            
            <div class="card">
                <div class="card-header">
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                        <h5 class="card-title mb-0">Riwayat Logbook Peralatan</h5>
                        <div class="d-flex gap-2">
                            <form action="{{ route('staff.inventories.exportLogbookPdf', $inventory->id) }}" method="GET" class="d-flex gap-2">
                                <input type="month" name="month_year" class="form-control form-control-sm" value="{{ now()->format('Y-m') }}" required>
                                <button type="submit" class="btn btn-secondary btn-sm flex-shrink-0">
                                    <i class="bi bi-file-earmark-pdf"></i> Ekspor PDF
                                </button>
                            </form>
                            <a href="{{ route('staff.inventories.createLogbook', $inventory->id) }}" class="btn btn-primary btn-sm flex-shrink-0">
                                <i class="bi bi-plus-circle"></i> Tambah Log
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="table-logbook">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Tanggal</th>
                                    <th>Jadwal</th>
                                    <th>Catatan</th>
                                    <th>Teknisi</th>
                                    <th>Dokumentasi</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($inventory->logbooks as $log)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $log->log_date->format('d/m/Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($log->schedule_time)->format('H:i') }}</td>
                                    <td>{{ Str::limit($log->notes, 30) }}</td>
                                    <td>{{ $log->user->name ?? '-' }}</td>
                                    <td>
                                        @if($log->documentation && count($log->documentation) > 0)
                                            <button class="btn btn-sm btn-outline-info py-0 px-2" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $log->id }}">
                                                Lihat ({{ count($log->documentation) }})
                                            </button>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <a href="{{ route('staff.inventories.editLogbook', ['inventory' => $inventory->id, 'logbook' => $log->id]) }}" class="btn btn-warning btn-sm" data-bs-toggle="tooltip" title="Edit Logbook">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('staff.inventories.destroyLogbook', ['inventory' => $inventory->id, 'logbook' => $log->id]) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus logbook ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" data-bs-toggle="tooltip" title="Hapus Logbook"><i class="bi bi-trash"></i></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @if($log->documentation && count($log->documentation) > 0)
                                <tr class="collapse" id="collapse-{{ $log->id }}">
                                    <td colspan="7">
                                        <div class="logbook-documentation p-3">
                                            @foreach ($log->documentation as $photoPath)
                                                <a href="{{ asset('uploads/' . $photoPath) }}" class="glightbox" data-gallery="log-{{ $log->id }}">
                                                    <img src="{{ asset('uploads/' . $photoPath) }}" alt="Dokumentasi Logbook">
                                                </a>
                                            @endforeach
                                        </div>
                                    </td>
                                </tr>
                                @endif
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

{{-- Modal Ubah Status (Sama seperti di index) --}}
<div class="modal fade" id="statusModal-{{ $inventory->id }}" tabindex="-1" aria-labelledby="statusModalLabel-{{ $inventory->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <form class="modal-content" method="POST" action="{{ route('staff.inventories.updateStatus', $inventory->id) }}">
            @csrf
            @method('PATCH')
            <div class="modal-header">
                <h5 class="modal-title" id="statusModalLabel-{{ $inventory->id }}">Ubah Status: {{ $inventory->name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="status-{{ $inventory->id }}" class="form-label">Status Kondisi</label>
                    <select class="form-select status-select" id="status-{{ $inventory->id }}" name="status" data-target="#report-link-container-{{ $inventory->id }}">
                        <option value="Baik" @selected($inventory->status == 'Baik')>Baik</option>
                        <option value="Pemeliharaan" @selected($inventory->status == 'Pemeliharaan')>Pemeliharaan</option>
                    </select>
                </div>
                <div class="mb-3 report-link-container" id="report-link-container-{{ $inventory->id }}" style="display: {{ $inventory->status == 'Pemeliharaan' ? 'block' : 'none' }};">
                    <label for="maintenance_report_link-{{ $inventory->id }}" class="form-label">Link Laporan Pemeliharaan (Google Drive) <span class="text-danger">*</span></label>
                    <input type="url" class="form-control" id="maintenance_report_link-{{ $inventory->id }}" name="maintenance_report_link" value="{{ old('maintenance_report_link', $inventory->maintenance_report_link) }}" placeholder="https://...">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Status</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts_admin')
    <script src="{{ asset('assetsv2/extensions/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assetsv2/extensions/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assetsv2/extensions/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets_landing/vendor/glightbox/js/glightbox.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            // Inisialisasi DataTable

            // Inisialisasi GLightbox
            const lightbox = GLightbox({ selector: '.glightbox' });

            // Logika modal status
            $('.status-select').on('change', function() {
                const targetContainer = $(this).data('target');
                const inputField = $(targetContainer).find('input');
                if ($(this).val() === 'Pemeliharaan') {
                    $(targetContainer).slideDown();
                    inputField.prop('required', true);
                } else {
                    $(targetContainer).slideUp();
                    inputField.prop('required', false);
                }
            });

            // Inisialisasi tooltip Bootstrap
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            })
        });
    </script>
@endsection

