@extends('layouts-V2.master-layouts-v2')
@section('title', 'Detail Event Posko')

@section('styles_admin')
    <link rel="stylesheet" href="{{ asset('assetsv2/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assetsv2/compiled/css/table-datatable-jquery.css') }}">
@endsection

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>{{ $nataruEvent->name }}</h3>
                <p class="text-subtitle text-muted">
                    Periode: {{ $nataruEvent->start_date->format('d M Y') }} - {{ $nataruEvent->end_date->format('d M Y') }}
                    @if($nataruEvent->is_active)
                        <span class="badge bg-success ms-2">Aktif</span>
                    @else
                        <span class="badge bg-secondary ms-2">Selesai</span>
                    @endif
                </p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                 <x-breadcrumb2 :items="[
                        ['label' => 'Dashboard', 'url' => route('staff.dashboard.index')],
                        ['label' => 'Event Posko', 'url' => route('staff.nataru-events.index')],
                        ['label' => 'Detail Event', 'active' => true]
                    ]" />
            </div>
        </div>
    </div>
</div>

<section class="section">
    
    {{-- 1. Info Link & Ringkasan --}}
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-body">
                    <h6 class="text-muted mb-2">Link Input Data (Publik)</h6>
                    <div class="input-group">
                        <input type="text" class="form-control" value="{{ route('public.nataru.form', $nataruEvent->public_token) }}" id="publicLink" readonly>
                        <button class="btn btn-primary" onclick="copyLink()">
                            <i class="bi bi-clipboard"></i> Salin
                        </button>
                        <a href="{{ route('public.nataru.form', $nataruEvent->public_token) }}" target="_blank" class="btn btn-outline-secondary">
                            <i class="bi bi-box-arrow-up-right"></i> Buka
                        </a>
                    </div>
                    <small class="text-muted mt-2 d-block">Bagikan link ini kepada petugas di lapangan untuk input data.</small>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-body">
                    <h6 class="text-muted mb-3">Ringkasan Sementara</h6>
                    <div class="row text-center">
                        <div class="col-4 border-end">
                            <h4 class="mb-0">{{ number_format($summary['total_flights']) }}</h4>
                            <small class="text-muted">Penerbangan</small>
                        </div>
                        <div class="col-4 border-end">
                            <h4 class="mb-0">{{ number_format($summary['total_pax']) }}</h4>
                            <small class="text-muted">Penumpang</small>
                        </div>
                        <div class="col-4">
                            <h4 class="mb-0">{{ number_format($summary['total_cargo']) }}</h4>
                            <small class="text-muted">Kargo (Kg)</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 2. Tabel Data Penerbangan --}}
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Data Penerbangan Masuk</h5>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-hover table-striped" id="table-flights">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Jam</th>
                            <th>Maskapai / Flight</th>
                            <th>Rute</th>
                            <th>Arah</th>
                            <th>Pax</th>
                            <th>Kargo</th>
                            <th>Harga (H/L)</th>
                            <th>Petugas</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($nataruEvent->flights as $flight)
                        <tr>
                            <td>{{ $flight->flight_date->format('d/m/Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($flight->flight_time)->format('H:i') }}</td>
                            <td>
                                <strong>{{ $flight->airline }}</strong><br>
                                <small class="text-muted">{{ $flight->flight_number }}</small>
                            </td>
                            <td>{{ $flight->route }}</td>
                            <td>
                                @if($flight->direction == 'arrival')
                                    <span class="badge bg-success"><i class="bi bi-arrow-down-left"></i> Datang</span>
                                @else
                                    <span class="badge bg-info"><i class="bi bi-arrow-up-right"></i> Berangkat</span>
                                @endif
                            </td>
                            <td>
                                <strong>{{ $flight->pax_total }}</strong>
                                @if($flight->load_factor > 0)
                                    <br><small class="text-muted">LF: {{ $flight->load_factor }}%</small>
                                @endif
                            </td>
                            <td>{{ number_format($flight->cargo) }}</td>
                            <td>
                                <small>
                                    T: {{ number_format($flight->ticket_price_high) }}<br>
                                    R: {{ number_format($flight->ticket_price_low) }}
                                </small>
                            </td>
                            <td>
                                <span class="d-inline-block text-truncate" style="max-width: 100px;" title="{{ $flight->officer_name }}">
                                    {{ $flight->officer_name }}
                                </span>
                            </td>
                            <td>
                                <form action="{{ route('staff.nataru.destroy', $flight->id) }}" method="POST" onsubmit="return confirm('Hapus data penerbangan ini?')">
                                    @csrf @method('DELETE')
                                    {{-- Kita tambahkan input hidden untuk redirect back ke halaman event ini --}}
                                    <input type="hidden" name="redirect_to_event" value="{{ $nataruEvent->id }}">
                                    <button class="btn btn-sm btn-danger" title="Hapus Data"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<script>
    function copyLink() {
        var copyText = document.getElementById("publicLink");
        copyText.select();
        copyText.setSelectionRange(0, 99999); 
        navigator.clipboard.writeText(copyText.value);
        alert("Link berhasil disalin!");
    }
</script>
@endsection

@section('scripts_admin')
    <script src="{{ asset('assetsv2/extensions/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assetsv2/extensions/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assetsv2/extensions/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#table-flights').DataTable({
                order: [[0, 'desc'], [1, 'desc']], // Urutkan berdasarkan tanggal & jam terbaru
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.13.4/i18n/id.json"
                }
            });
        });
    </script>
@endsection