@extends('layouts-V2.master-layouts-v2')
@section('title', 'Permintaan Suku Cadang')

@section('styles_admin')
    <link rel="stylesheet" href="{{ asset('assetsv2/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assetsv2/compiled/css/table-datatable-jquery.css') }}">
@endsection

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Permintaan Suku Cadang</h3>
                <p class="text-subtitle text-muted">Daftar permintaan suku cadang yang telah dibuat.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <x-breadcrumb2 :items="[
                    ['label' => 'Dashboard', 'url' => route('root')],
                    ['label' => 'Permintaan Suku Cadang', 'active' => true]
                ]" />
            </div>
        </div>
    </div>
</div>
<section class="section">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Daftar Permintaan</h5>
            <a href="{{ route('staff.spare-part-requests.create') }}" class="btn btn-primary"><i class="bi bi-plus-circle me-2"></i> Buat Permintaan Baru</a>
        </div>
        <div class="card-body">
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif
            <div class="table-responsive">
                <table class="table table-striped" id="table-data">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Pemohon</th>
                            <th>Perihal</th>
                            <th>Suku Cadang Diminta</th>
                            <th>Nota Dinas</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($requests as $req)
                        <tr>
                            <td>{{ $req->created_at->translatedFormat('d M Y H:i') }}</td>
                            <td>{{ $req->user->name ?? 'N/A' }}</td>
                            <td>{{ $req->subject }}</td>
                            <td>{{ $req->sparePart->name ?? 'N/A' }}</td>
                            <td>
                                <a href="{{ $req->memo_link }}" target="_blank" class="btn btn-sm btn-outline-primary">Lihat Nota</a>
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    {{-- Tombol Detail/Edit jika diperlukan nanti --}}
                                    <form action="{{ route('staff.spare-part-requests.destroy', $req->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus permintaan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">Belum ada permintaan suku cadang.</td>
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
    <script src="{{ asset('assetsv2/extensions/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assetsv2/extensions/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    
    <script src="{{ asset('assetsv2/extensions/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assetsv2/compiled/js/staff-permintaanSukuCadang.js') }}"></script>
    
    <script>
        
    </script>
@endsection
