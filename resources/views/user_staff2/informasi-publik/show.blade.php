@extends('layouts-V2.master-layouts-v2')
@section('title', 'Detail Informasi Publik')
@section('styles_admin')
    
@endsection
@section('content')

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Detail Pengajuan Informasi Publik</h3>
                    <p class="text-subtitle text-muted">Detail informasi Publik.</p>
            </div>

            <div class="col-12 col-md-6 order-md-2 order-first">
                <x-breadcrumb2 :items="[
                    ['label' => 'Menu', 'url' => route('profile')],
                    ['label' => 'Informasi Publik', 'url' => route('informasiPublik.staffIndex')],
                    ['label' => 'Detail', 'active' => true]
                ]" />
            </div>
        </div>
    </div>
    
    <section class="section">
        <div class="card">
            
            @if(session('success'))
                <div class="alert alert-primary alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <div class="card-header">
                <h5 class="card-title">Informasi Pengaju</h5>
            </div>
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-3 col-12 text-center mb-3 mb-md-0">
                        <div class="avatar avatar-xl me-3">
                            <img src="{{ asset('../assetsv2/compiled/jpg/2.jpg') }}" alt="-" srcset="">
                        </div>
                    </div>
                    <div class="col-md-9 col-12">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Nama</h6>
                                <p>{{ $publicInformation->nama }}</p>
                            </div>
                            <div class="col-md-6">
                                <h6>Email</h6>
                                <p>{{ $publicInformation->email }}</p>
                            </div>
                            <div class="col-md-6">
                                <h6>No. hp</h6>
                                <p>{{ $publicInformation->no_hp }}</p>
                            </div>
                            <div class="col-md-6">
                                <h6>Pekerjaan</h6>
                                <p>{{ $publicInformation->pekerjaan }}</p>
                            </div>
                            <div class="col-md-6">
                                <h6>NPWP</h6>
                                <p>{{ $publicInformation->npwp }}</p>
                            </div>
                            <div class="col-md-6">
                                <h6>Tanggal Pengajuan</h6>
                                <p>{{ \Carbon\Carbon::parse($publicInformation->created_at)->format('d M Y - H:i') }}</p>
                            </div>
                            <div class="col-md-12">
                                <h6>Alamat</h6>
                                <p>{{ $publicInformation->alamat }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Informasi yang Diminta</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 ">
                        <h6>Surat Permintaan dari</h6>
                        <p>{{ $publicInformation->surat_permintaan }}</p>
                    </div>
                    <div class="col-md-6 ">
                        <h6>Rincian Informasi</h6>
                        <p>{{ $publicInformation->rincian_informasi }}</p>
                    </div>
                    <div class="col-md-6 ">
                        <h6>Tujuan Informasi</h6>
                        <p>{{ $publicInformation->tujuan_informasi }}</p>
                    </div>
                    
                    <div class="col-12 mt-3">
                        <h6>Cara Memperoleh</h6>
                        <p>{{ $publicInformation->cara_memperoleh }}</p>
                    </div>
                    <div class="col-12 mt-3">
                        <h6>Cara Salinan</h6>
                        <p>{{ $publicInformation->cara_salinan }}</p>
                    </div>
                    <div class="col-12 mt-3">
                        <h6>Status Pengajuan</h6>
                        @php
                        $status = $publicInformation->status;
                        $badgeClass = match($status) {
                            'Belum dibalas' => 'bg-warning',
                            'Sudah dibalas' => 'bg-success',
                            default => 'bg-info',
                        };
                        @endphp
                        <p class="badge {{ $badgeClass }}">
                            {{ $publicInformation->status }}
                        </p>
                    </div>
                    
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Dokumen Terlampir</h5>

            </div>
            <div class="card-body">
                <div class="row">

                    <div class="col-md-6">
                        <h6>KTP</h6>
                        @if ($publicInformation->ktp)
                            <a href="{{ asset('Uploads/' . $publicInformation->ktp) }}" class="btn btn-sm btn-primary" target="_blank" id="lihat-dokumen" data-bs-toggle="tooltip" title="Lihat Dokumen"><i class="bi bi-file-earmark-pdf"></i> {{ basename($publicInformation->ktp) }}</a>
                        @else
                            <p>Tidak ada file</p>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <h6>Surat Pertanggung jawaban</h6>
                        @if ($publicInformation->surat_pertanggungjawaban)
                            <a href="{{ asset('Uploads/' . $publicInformation->surat_pertanggungjawaban) }}" class="btn btn-sm btn-primary" id="lihat-dokumen" data-bs-toggle="tooltip" title="Lihat Dokumen"><i class="bi bi-file-earmark-pdf"></i> {{ basename($publicInformation->surat_pertanggungjawaban) }}</a>
                        @else
                            <p>Tidak ada File</p>    
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Balasan Pengajuan</h5>
            </div>
            <div class="card-body">
                @if($publicInformation->status == 'Belum dibalas')
                    <form action="{{ route('informasiPublik.reply', $publicInformation->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="row">
                        
                        <div class="col-12 mb-3">
                            <label for="link_balasan" class="form-label">Link Balasan</label>
                            <input type="url" class="form-control @error('link_balasan') is-invalid @enderror" id="link_balasan" name="link_balasan" value="{{ old('link_balasan') }}" placeholder="Masukkan URL balasan">
                            @error('link_balasan')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 mb-3">
                            <label for="replied_at" class="form-label">Tanggal Balasan</label>
                            <input type="date" class="form-control @error('replied_at') is-invalid @enderror" id="replied_at" name="replied_at" value="{{ old('replied_at', now()->format('Y-m-d')) }}">
                            @error('replied_at')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>
                    <button type="submit" class="btn btn-primary mb-2">Simpan Balasan</button>
                    </form>
                @else
                <div class="mb-3">
                    <h6>Link Balasan</h6>
                    <a href="{{ $publicInformation->link_balasan }}" target="_blank" class="btn btn-outline-info btn-sm">Lihat Balasan</a>

                </div>

                <div class="mb-3">
                    <h6>Tanggal Balasan</h6>
                    <p>{{ $publicInformation->replied_at ? \Carbon\Carbon::parse($publicInformation->replied_at)->format('d M Y') : 'Belum dibalas' }}</p>
                </div>
                @endif
                <a href="{{ route('informasiPublik.staffIndex') }}" class="btn btn-secondary">Kembali</a>

            </div>
        </div>
        
    </section>
</div>
@endsection
@section('scripts_admin')

@endsection