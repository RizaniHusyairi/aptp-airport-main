@extends('layouts-V2.master-layouts-v2')
@section('title', 'Detail sewa')
@section('styles_admin')
    
@endsection
@section('content')

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Detail Sewa</h3>
                    <p class="text-subtitle text-muted">Detail informasi pengajuan sewa.</p>
            </div>

            <div class="col-12 col-md-6 order-md-2 order-first">
                <x-breadcrumb2 :items="[
                    ['label' => 'Menu', 'url' => route('profile')],
                    ['label' => 'Sewa', 'url' => route('staffSewa.index')],
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
                            <img src="{{ asset('../assetsv2/compiled/jpg/2.jpg') }}" alt="{{  $slot->users->first()?->name ?? '-'  }}" srcset="">
                        </div>
                    </div>
                    <div class="col-md-9 col-12">
                        <div class="row">
                            <div class="col-12">
                                <h6>Nama</h6>
                                <p>{{ $slot->users->first()?->name ?? '-' }}</p>
                            </div>
                            <div class="col-12">
                                <h6>Email</h6>
                                <p>{{ $slot->users->first()?->email ?? '-' }}</p>
                            </div>
                            <div class="col-12">
                                <h6>Tanggal Pengajuan</h6>
                                <p>{{ $slot->created_at->format('d M Y - H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Informasi Slot Charter</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 ">
                        <h6>Nomor Registrasi Pesawat</h6>
                        <p>{{ $slot->aircraft_registration }}</p>
                    </div>
                    <div class="col-md-6 ">
                        <h6>Tipe Pesawat</h6>
                        <p>{{ $slot->aircraft_type }}</p>
                    </div>
                    <div class="col-md-6 ">
                        <h6>Jadwal Keberangkatan</h6>
                        <p>{{ $slot->departure_schedule }}</p>
                    </div>
                    <div class="col-md-6 ">
                        <h6>Jadwal Kedatangan</h6>
                        <p>{{ $slot->arrival_schedule }}</p>
                    </div>
                    <div class="col-md-6 ">
                        <h6>Bandara Asal</h6>
                        <p>{{ $slot->origin_airport }}</p>
                    </div>
                    <div class="col-md-6 ">
                        <h6>Bandara Tujuan</h6>
                        <p>{{ $slot->destination_airport }}</p>
                    </div>
                    <div class="col-md-6 ">
                        <h6>Jenis Penerbangan</h6>
                        <p>{{ $slot->flight_type == "lainnya" ?  $slot->flight_more : $slot->flight_type }}</p>
                    </div>
                    
                    
                    @if ($slot->documents)
                        <div class="col-md-6">
                            <h6>Dokumen Terlampir</h6>
                            <a href="{{ asset('uploads/documents/slot/' . basename($slot->documents)) }}" class="btn btn-sm btn-primary" id="lihat-dokumen" data-bs-toggle="tooltip" title="Lihat Dokumen"><i class="bi bi-file-earmark-pdf"></i> {{ basename($slot->documents) }}</a>
                        </div>
                    @else
                        <div class="col-md-6">
                            <h6>Tidak Ada Dokumen</h6>
                        </div>
                    @endif
                    <div class="col-md-6">
                        <h6>Status Pengajuan</h6>
                        @php
                        $status = $slot->status;
                        $badgeClass = match($status) {
                            'disetujui' => 'bg-success',
                            'ditolak' => 'bg-danger',
                            default => 'bg-info',
                        };
                        @endphp
                        <span class="badge {{ $badgeClass }}">{{ ucfirst($status) }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-end gap-2">
            <a href="{{ route('slot.staffIndex') }}" class="btn btn-secondary">Kembali</a>
            @if ($slot->status === 'diajukan')
                <div class="">
                  <form action="{{ route('slot.approve', $slot->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-success" id="setujui-pengajuan">Setujui Pengajuan</button>
                  </form>
                </div>
                <div class="">
                  <form action="{{ route('slot.reject', $slot->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-danger" id="tolak-pengajuan">Tolak Pengajuan</button>
                  </form>
                </div>
              @endif
        </div>
    </section>
</div>
@endsection
@section('scripts_admin')

@endsection