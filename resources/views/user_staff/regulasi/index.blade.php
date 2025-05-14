@extends('layouts.master')

@section('title')
  Pengaduan
@endsection

@section('content')
  @component('components.breadcrumb')
    @slot('li_1') Regulasi @endslot
    @slot('title') Kelola Regulasi @endslot
  @endcomponent
  @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
  @endif
  @if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
  @endif
  <div class="row">
    <div class="col-xl-12">
      

      <div class="card">
        <div class="card-body">
          <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-header d-flex justify-content-between bg-transparent">
                  <h4 class="card-title">Daftar Regulasi</h4>
                  <a href="{{ route('letters.staff.create') }}" class="btn btn-primary mb-3">
                    <i class='bx bx-plus'></i> Tambah Surat
                  </a>
                </div>
                <div class="card-body">
                  <table id="submission-table" class="table table-bordered table-hover">
                    <thead>
                      <tr>
                        <th>No Surat</th>
                        <th>Judul</th>
                        <th>Jenis</th>
                        <th>Tanggal Terbit</th>
                        <th>File</th>
                        <th>Aksi</th>
                    </tr>
                    </thead>

                    @staff
                      <tbody>
                        @forelse ($letters as $letter)
                          <tr>
                              <td>{{ $letter->number }}</td>
                            <td>{{ $letter->title }}</td>
                            <td>{{ $letter->type == 'edaran' ? 'Surat Edaran' : 'Surat Utusan' }}</td>
                            <td>{{ \Carbon\Carbon::parse($letter->issue_date)->translatedFormat('d F Y') }}</td>
                            <td><a href="{{ asset($letter->file_path) }}" target="_blank">Lihat</a></td>
                            <td>
                                <a href="{{ route('letters.staff.edit', $letter) }}" class="mb-1 btn btn-warning btn-action">
                                    <i class='bx bx-edit'></i> Edit
                                </a>
                                <form action="{{ route('letters.staff.destroy', $letter) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-action"
                                            onclick="return confirm('Hapus surat ini?')">
                                        <i class='bx bx-trash'></i> Hapus
                                    </button>
                                </form>
                            </td>
                          </tr>
                          @empty
                            <tr>
                              <td colspan="12" class="text-center">Belum ada pengaduan</td>
                            </tr>
                          
                        @endforelse
                      </tbody>
                    @endstaff
                  </table>
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
@endsection

@section('script')
  <script>

  </script>
@endsection
