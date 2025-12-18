@extends('layouts-V2.master-layouts-v2')
@section('title', 'Manajemen Data OJT')

@section('styles_admin')
    <link rel="stylesheet" href="{{ asset('assetsv2/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assetsv2/compiled/css/table-datatable-jquery.css') }}">
    <style>
        .avatar-ojt {
            width: 50px;
            height: 60px;
            object-fit: cover;
            border-radius: 4px;
            border: 1px solid #ddd;
        }
        .unit-badge {
            font-size: 0.75rem;
            margin-right: 2px;
            margin-bottom: 2px;
            display: inline-block;
        }
    </style>
@endsection

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Data Peserta OJT</h3>
                <p class="text-subtitle text-muted">Kelola data anak magang dan cetak sertifikat.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <x-breadcrumb2 :items="[
                    ['label' => 'Dashboard', 'url' => route('root')],
                    ['label' => 'Data OJT', 'active' => true]
                ]" />
            </div>
        </div>
    </div>
</div>

<section class="section">
    <div class="card">
        {{-- === PERUBAHAN DI SINI: Tombol Tambah Data di Header === --}}
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Daftar Peserta OJT</h5>

            {{-- Tombol Mengarah ke Formulir Create --}}
            <a href="{{ route('staff.ojt.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-2"></i> Tambah Data
            </a>
        </div>
        {{-- ======================================================== --}}

        <div class="card-body">
            {{-- Alert Notifikasi --}}
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            <div class="table-responsive">
                <table class="table table-striped" id="table-ojt">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Foto</th>
                            <th>Nama / NIK</th>
                            <th>Institusi / Jurusan</th>
                            <th>Periode OJT</th>
                            <th>Unit Kerja</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($students as $student)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                @if($student->photo_path)
                                    <img src="{{ asset('uploads/' . $student->photo_path) }}" alt="Foto" class="avatar-ojt">
                                @else
                                    <div class="avatar-ojt bg-secondary d-flex align-items-center justify-content-center text-white small">No Pic</div>
                                @endif
                            </td>
                            <td>
                                <div class="fw-bold">{{ $student->name }}</div>
                                <div class="small text-muted">{{ $student->id_number }}</div>
                            </td>
                            <td>
                                <div>{{ $student->institution }}</div>
                                <div class="small text-muted text-uppercase">{{ $student->major }}</div>
                            </td>
                            <td>
                                <div class="fw-bold">{{ $student->duration }}</div>
                                <small class="text-muted">
                                    {{ $student->start_date->format('d/m/y') }} - {{ $student->end_date->format('d/m/y') }}
                                </small>
                            </td>
                            <td style="max-width: 250px;">
                                {{-- Loop Unit Kerja (JSON Array) --}}
                                @if(is_array($student->work_units))
                                    @foreach($student->work_units as $unit)
                                        <span class="badge bg-info unit-badge">{{ $unit }}</span>
                                    @endforeach
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    {{-- === TOMBOL DETAIL BARU === --}}
                                    <a href="{{ route('staff.ojt.show', $student->id) }}" class="btn btn-sm btn-info text-white" title="Lihat Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    {{-- Tombol Cetak Sertifikat --}}
                                    <a href="{{ route('staff.ojt.certificate', $student->id) }}" target="_blank" class="btn btn-sm btn-success" title="Cetak Sertifikat">
                                        <i class="bi bi-printer"></i>
                                    </a>

                                    {{-- Tombol Hapus --}}
                                    <form action="{{ route('staff.ojt.destroy', $student->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data {{ $student->name }}? File foto dan KTP juga akan terhapus.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Hapus Data">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        {{-- Tidak perlu baris kosong manual, Datatable akan handle "No data available" --}}
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
            $('#table-ojt').DataTable({
                "language": { "url": "https://cdn.datatables.net/plug-ins/1.13.1/i18n/id.json" },
                "columnDefs": [
                    { "orderable": false, "targets": [1, 6] } // Matikan sorting di kolom Foto & Aksi
                ]
            });
        });
    </script>
@endsection
