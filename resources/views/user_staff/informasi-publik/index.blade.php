@extends('layouts.master')

@section('title')
  Daftar Pengajuan Informasi Publik
@endsection

@section('content')
  @component('components.breadcrumb')
    @slot('li_1') Informasi Publik @endslot
    @slot('title') Daftar Pengajuan Informasi Publik @endslot
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
          <h4 class="card-title mb-4">Daftar Pengajuan</h4>
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Nama</th>
                  <th>Alamat</th>
                  <th>Pekerjaan</th>
                  <th>No. HP</th>
                  <th>Email</th>
                  <th>Status</th>
                  <th>Link Balasan</th>
                  <th>Dibuat</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                @forelse ($publicInformation as $index => $item)
                  <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->nama }}</td>
                    <td>{{ $item->alamat }}</td>
                    <td>{{ $item->pekerjaan }}</td>
                    <td>{{ $item->no_hp }}</td>
                    <td>{{ $item->email }}</td>
                    <td>
                      <span class="badge {{ $item->status == 'Sudah dibalas' ? 'bg-success' : 'bg-warning' }}">
                        {{ $item->status }}
                      </span>
                    </td>
                    <td>
                      @if($item->link_balasan)
                        <a href="{{ $item->link_balasan }}" target="_blank" class="btn btn-outline-info btn-sm">Lihat</a>
                      @else
                        -
                      @endif
                    </td>
                    <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d M Y - H:i') }}</td>
                    <td>
                      <a href="{{ route('informasiPublik.show', $item->id) }}" class="btn btn-primary btn-sm">Lihat Berkas</a>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="10" class="text-center">Belum ada data pengajuan</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection