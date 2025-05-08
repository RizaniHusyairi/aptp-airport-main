@extends('layouts.master')

@section('title')
  Detail Pengajuan Sewa
@endsection

@section('content')
  @component('components.breadcrumb')
    @slot('li_1') Sewa @endslot
    @slot('title') Detail Pengajuan Sewa @endslot
  @endcomponent

  <div class="row">
    <div class="col-xl-12">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title mb-4">Detail Pengajuan Sewa</h4>

          <div class="mb-3">
            <label class="form-label">Nama Pengaju</label>
            <input type="text" class="form-control" value="{{ $rental->users->first()?->name ?? '-' }}" disabled>
          </div>

          <div class="mb-3">
            <label class="form-label">Email Pengaju</label>
            <input type="text" class="form-control" value="{{ $rental->users->first()?->email ?? '-' }}" disabled>
          </div>

          <div class="mb-3">
            <label class="form-label">Tanggal Pengajuan</label>
            <input type="text" class="form-control" value="{{ $rental->created_at->format('d M Y - H:i') }}" disabled>
          </div>

          <hr>

          <h5 class="mb-3">Detail Sewa</h5>

          <div class="mb-3">
            <label for="rental_name" class="form-label">Nama Sewa</label>
            <input type="text" class="form-control" id="rental_name" value="{{ $rental->rental_name }}" disabled>
          </div>

          <div class="mb-3">
            <label for="rental_type" class="form-label">Jenis Sewa</label>
            <input type="text" class="form-control" id="rental_type" value="{{ ucfirst($rental->rental_type) }}" disabled>
          </div>

          <div class="mb-3">
            <label for="description" class="form-label">Deskripsi Sewa</label>
            <textarea class="form-control" id="description" rows="4" disabled>{{ $rental->description }}</textarea>
          </div>
          
          @if($rental->area)
            <div class="mb-3">
              <label for="area" class="form-label">Luas</label>
              <input type="number" class="form-control" id="area" value="{{ $rental->area }}" disabled>
            </div>
          @endif
          @if($rental->location)
            <div class="mb-3">
              <label for="location" class="form-label">Lokasi</label>
              <input type="number" class="form-control" id="location" value="{{ $rental->location }}" disabled>
            </div>
            @endif
          @if($rental->quantity)
            <div class="mb-3">
              <label for="quantity" class="form-label">Jumlah</label>
              <input type="number" class="form-control" id="quantity" value="{{ $rental->quantity }}" disabled>
            </div>
          @endif
          @if($rental->design_file)
            <div class="mb-3 d-flex flex-column">
              <label class="form-label">File Desain</label>
              <a href="{{ asset('storage/' . $rental->design_file) }}" target="_blank">Lihat Desain</a> 
            </div>
          @endif
          <div class="mb-3 d-flex flex-column">
            <label class="form-label">Dokumen Terlampir</label>
            @if ($rental->documents)
              <a href="{{ asset('uploads/documents/rental/' . basename($rental->documents)) }}" class="btn btn-primary" disabled target="_blank">
                Lihat Dokumen
              </a>
            @else
              <input type="text" class="form-control" value="Tidak ada dokumen" disabled>
            @endif
          </div>
          
          <hr>

          <h5 class="mb-3">Status Sewa</h5>
          <div class="mb-3 d-flex flex-column">
            @php
              $status = $rental->submission_status;
              $badgeClass = match($status) {
                  'disetujui' => 'bg-success',
                  'ditolak' => 'bg-danger',
                  default => 'bg-info',
              };
            @endphp
            <span class="badge {{ $badgeClass }}">{{ ucfirst($status) }}</span>
          </div>
          <div class="d-flex justify-content-between">
            <a href="{{ route('staffSewa.index') }}" class="btn btn-secondary">Kembali</a>
            <div class="d-flex gap-3">
              @if ($rental->submission_status === 'diajukan')
                <div class="">
                  <form action="{{ route('sewa.reject', $rental->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-danger">Tolak Pengajuan</button>
                  </form>
                </div>
                <div class="">
                  <form action="{{ route('sewa.approve', $rental->id) }}" method="POST">
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
