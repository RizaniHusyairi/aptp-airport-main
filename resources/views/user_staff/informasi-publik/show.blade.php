@extends('layouts.master')

@section('title')
  Detail Permintaan Informasi Publik
@endsection

@section('content')
  @component('components.breadcrumb')
    @slot('li_1') Informasi Publik @endslot
    @slot('title') Detail Permintaan Informasi Publik @endslot
  @endcomponent

  @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
  @endif

  <div class="row">
    <div class="col-xl-12">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title mb-4">Informasi Pemohon</h4>

          <div class="mb-3">
            <label class="form-label">Nama</label>
            <input type="text" class="form-control" value="{{ $publicInformation->nama }}" disabled>
          </div>

          <div class="mb-3">
            <label class="form-label">Alamat</label>
            <textarea class="form-control" rows="2" disabled>{{ $publicInformation->alamat }}</textarea>
          </div>

          <div class="mb-3">
            <label class="form-label">Pekerjaan</label>
            <input type="text" class="form-control" value="{{ $publicInformation->pekerjaan }}" disabled>
          </div>

          <div class="mb-3">
            <label class="form-label">NPWP</label>
            <input type="text" class="form-control" value="{{ $publicInformation->npwp }}" disabled>
          </div>

          <div class="mb-3">
            <label class="form-label">No. HP</label>
            <input type="text" class="form-control" value="{{ $publicInformation->no_hp }}" disabled>
          </div>

          <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" class="form-control" value="{{ $publicInformation->email }}" disabled>
          </div>

          <div class="mb-3">
            <label class="form-label">Tanggal Permintaan</label>
            <input type="text" class="form-control" value="{{ \Carbon\Carbon::parse($publicInformation->created_at)->format('d M Y - H:i') }}" disabled>
          </div>

          <div class="mb-3">
            <label class="form-label">Status Pengajuan</label>
            <input type="text" class="form-control" value="{{ $publicInformation->status }}" disabled>
          </div>

          <hr>

          <h5 class="mb-3">Informasi yang Diminta</h5>

          <div class="mb-3">
            <label class="form-label">Rincian Informasi</label>
            <textarea class="form-control" rows="3" disabled>{{ $publicInformation->rincian_informasi }}</textarea>
          </div>

          <div class="mb-3">
            <label class="form-label">Tujuan Informasi</label>
            <textarea class="form-control" rows="3" disabled>{{ $publicInformation->tujuan_informasi }}</textarea>
          </div>

          <div class="mb-3">
            <label class="form-label">Cara Memperoleh</label>
            <input type="text" class="form-control" value="{{ $publicInformation->cara_memperoleh }}" disabled>
          </div>

          <div class="mb-3">
            <label class="form-label">Cara Salinan</label>
            <input type="text" class="form-control" value="{{ $publicInformation->cara_salinan }}" disabled>
          </div>

          <hr>

          <h5 class="mb-3">Dokumen Terlampir</h5>

          <div class="mb-3 d-flex flex-column">
            <label class="form-label">KTP</label>
            @if ($publicInformation->ktp)
              <a href="{{ asset('Uploads/' . $publicInformation->ktp) }}" class="btn btn-outline-primary" target="_blank">Lihat KTP</a>
            @else
              <input type="text" class="form-control" value="Tidak ada file" disabled>
            @endif
          </div>

          <div class="mb-3 d-flex flex-column">
            <label class="form-label">Surat Pertanggungjawaban</label>
            @if ($publicInformation->surat_pertanggungjawaban)
              <a href="{{ asset('Uploads/' . $publicInformation->surat_pertanggungjawaban) }}" class="btn btn-outline-primary" target="_blank">Lihat Surat</a>
            @else
              <input type="text" class="form-control" value="Tidak ada file" disabled>
            @endif
          </div>

          <div class="mb-3 d-flex flex-column">
            <label class="form-label">Surat Permintaan Dari</label>
            <input type="text" class="form-control" value="{{ $publicInformation->surat_permintaan }}" disabled>
          </div>

          <hr>

          <h5 class="mb-3">Balasan Pengajuan</h5>

          @if($publicInformation->status == 'Belum dibalas')
            <form action="{{ route('informasiPublik.reply', $publicInformation->id) }}" method="POST">
              @csrf
              @method('PATCH')
              <div class="mb-3">
                <label for="link_balasan" class="form-label">Link Balasan</label>
                <input type="url" class="form-control @error('link_balasan') is-invalid @enderror" id="link_balasan" name="link_balasan" value="{{ old('link_balasan') }}" placeholder="Masukkan URL balasan">
                @error('link_balasan')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="mb-3">
                <label for="replied_at" class="form-label">Tanggal Balasan</label>
                <input type="date" class="form-control @error('replied_at') is-invalid @enderror" id="replied_at" name="replied_at" value="{{ old('replied_at', now()->format('Y-m-d')) }}">
                @error('replied_at')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <button type="submit" class="btn btn-primary">Simpan Balasan</button>
            </form>
          @else
            <div class="mb-3">
              <label class="form-label">Link Balasan</label>
              <input type="url" class="form-control" value="{{ $publicInformation->link_balasan ?? 'Tidak ada link' }}" disabled>
            </div>

            <div class="mb-3">
              <label class="form-label">Tanggal Balasan</label>
              <input type="text" class="form-control" value="{{ $publicInformation->replied_at ? \Carbon\Carbon::parse($publicInformation->replied_at)->format('d M Y') : 'Belum dibalas' }}" disabled>
            </div>
          @endif

          <div class="d-flex justify-content-between mt-4">
            <a href="{{ route('informasiPublik.staffIndex') }}" class="btn btn-secondary">Kembali</a>
          </div>

        </div>
      </div>
    </div>
  </div>
@endsection