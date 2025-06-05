@extends('layouts.master')

@section('title')
  Detail Pengajuan Slot Charter
@endsection

@section('content')
  @component('components.breadcrumb')
    @slot('li_1') Slot Charter @endslot
    @slot('title') Detail Slot Charter @endslot
  @endcomponent

  <div class="row">
    <div class="col-xl-12">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title mb-4">Informasi Pengaju</h4>

          <div class="mb-3">
            <label class="form-label">Nama Pengaju</label>
            <input type="text" class="form-control" value="{{ $slot->user->name ?? '-' }}" disabled>
          </div>

          <div class="mb-3">
            <label class="form-label">Email Pengaju</label>
            <input type="text" class="form-control" value="{{ $slot->user->email ?? '-' }}" disabled>
          </div>

          <div class="mb-3">
            <label class="form-label">Tanggal Pengajuan</label>
            <input type="text" class="form-control" value="{{ $slot->created_at->format('d M Y - H:i') }}" disabled>
          </div>

          <hr>

          <h5 class="mb-3">Detail Slot Charter</h5>
          <div class="row">
            <div class="col-lg-6 mb-3">
              <label for="nomorRegistrasi" class="form-label">Nomor Registrasi Pesawat</label>
              <input type="text" class="form-control" id="nomorRegistrasi" value="{{ $slot->aircraft_registration }}" disabled>
            </div>
            <div class="col-lg-6 mb-3">
              <label for="tipePesawat" class="form-label">Tipe Pesawat</label>
              <input type="text" class="form-control" id="tipePesawat" value="{{ $slot->aircraft_type }}" disabled>
            </div>

            <div class="col-lg-6 mb-3">
              <label for="jadwalKeberangkatan" class="form-label">Jadwal Keberangkatan</label>
              <input type="text" class="form-control" id="jadwalKeberangkatan" value="{{ $slot->departure_schedule }}" disabled>
            </div>
            <div class="col-lg-6 mb-3">
              <label for="jadwalKedatangan" class="form-label">Jadwal Kedatangan</label>
              <input type="text" class="form-control" id="jadwalKedatangan" value="{{ $slot->arrival_schedule }}" disabled>
            </div>
            <div class="col-lg-6 mb-3">
              <label for="bandaraAsal" class="form-label">Bandara Asal</label>
              <input type="text" class="form-control" id="bandaraAsal" value="{{ $slot->origin_airport }}" disabled>
            </div>
            <div class="col-lg-6 mb-3">
              <label for="bandaraTujuan" class="form-label">Bandara Tujuan</label>
              <input type="text" class="form-control" id="bandaraTujuan" value="{{ $slot->destination_airport }}" disabled>
            </div>
            <div class="col-lg-12 mb-3">
              <label for="jenisPenerbangan" class="form-label">Jenis Penerbangan</label>
              <input type="text" class="form-control" id="jenisPenerbangan" value="{{ $slot->flight_type == "lainnya" ? "Lainnya (" . $slot->flight_more . ")" : $slot->flight_type }}" disabled>
            </div>
  
            <div class="col-lg-12 mb-3 d-flex flex-column">
              <label class="form-label">Dokumen</label>
              @if ($slot->documents)
                <a href="{{ asset('uploads/documents/slot/' . basename($slot->documents)) }}" class="btn btn-primary" disabled target="_blank">
                  Lihat Dokumen
                </a>
              @else
                <input type="text" class="form-control" value="Tidak ada dokumen" disabled>
              @endif
            </div>
          </div>
          <hr>

          <h5 class="mb-3">Status</h5>
          <div class="mb-3 d-flex flex-column">
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
          <div class="d-flex justify-content-between">
            <a href="{{ route('slot.staffIndex') }}" class="btn btn-secondary">Kembali</a>
            <div class="d-flex gap-3">
              @if ($slot->status === 'diajukan')
                <div class="">
                  <form action="{{ route('slot.reject', $slot->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-danger">Tolak Pengajuan</button>
                  </form>
                </div>
                <div class="">
                  <form action="{{ route('slot.approve', $slot->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-success">Setujui Pengajuan</button>
                  </form>
                </div>
              @endif
            </div>
          </div>
          

          

          
          

        </div>
      </div>
    </div>
  </div>
@endsection
