@extends('layouts.master')

@section('title')
  Aktivitas Bandara
@endsection

@section('content')
  @component('components.breadcrumb')
    @slot('li_1') Aktivitas Bandara @endslot
    @slot('title') Lalu Lintas Angkutan Udara @endslot
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
                  <h4 class="card-title">Daftar Lalu Lintas</h4>
                    <a href='{{ route("laluLintas.create") }}' class="btn btn-success btn-sm">+ Tambah Data</a>
                </div>
                <div class="card-body">
                  <table id="submission-table" class="table table-bordered table-hover">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Tanggal</th>
                        <th>Jenis / Angkutan</th>
                        <th>Datang</th>
                        <th>Berangkat</th>
                        <th>Dibuat</th>
                        <!-- <th>Aksi</th> -->
                      </tr>
                    </thead>

                    @staff
                      <tbody>
                        @forelse ($traffics as $index => $traffic)
                          <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $traffic->date }}</td>
                            <td>{{ $traffic->type }}</td>
                            <td>{{ $traffic->arrival }}</td>
                            <td>{{ $traffic->departure }}</td>
                            <td>{{ $traffic->created_at->format('d M Y H:i') }}</td>
                          </tr>
                        @empty
                          <tr>
                            <td colspan="6" class="text-center">Belum ada data</td>
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
