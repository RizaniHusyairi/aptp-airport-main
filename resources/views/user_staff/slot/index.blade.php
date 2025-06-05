@extends('layouts.master')

@section('title')
  Pengajuan Slot Charter
@endsection

@section('content')
  @component('components.breadcrumb')
    @slot('li_1') Slot Charter @endslot
    @slot('title') Pengajuan Slot Charter @endslot
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

          <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-header d-flex justify-content-between bg-transparent">
                  <h4 class="card-title">Daftar Slot Charter</h4>
                  @notadmin
                    @notstaff
                      <a href='{{ route("slot.create") }}' class="btn btn-success btn-sm">+ Tambah Pengajuan</a>
                    @endnotstaff
                  @endnotadmin
                </div>
                <div class="card-body">
                  <table id="submission-table" class="table table-bordered table-hover">
                    <thead>
                      <tr>
                        <th>#</th>
                          <th>No Regis</th>
                          <th>Tipe pesawat</th>
                          <th>jadwal keberangkatan - kedatangan</th>
                          <th>Bandara Asal - Tujuan</th>
                          <th>Jenis Penerbangan</th>
                          <th>Status</th>
                          <th>Aksi</th>
                      </tr>
                    </thead>
                    @notadmin
                      @notstaff
                        <tbody>
                          @forelse ($slots as $index => $slot)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $slot->aircraft_registration }}</td>
                                <td>{{ $slot->aircraft_type }}</td>
                                <td>{{ $slot->departure_schedule }} - {{ $slot->arrival_schedule}}</td>
                                <td>{{ $slot->origin_airport }} - {{ $slot->destination_airport}}</td>
                                <td>{{ $slot->flight_type }}</td>
                                <td>
                                    <span class="badge {{ $slot->status == 'disetujui' ? 'bg-success' : ($slot->status == 'ditolak' ? 'bg-danger' : 'bg-info') }}">
                                        {{ ucfirst($slot->status) }}
                                    </span>
                                </td>
                                <td>
                                  <div class="row g-1">
                                    <div class="col-6">
                                      <a href="{{ asset('uploads/documents/slot/' . basename($slot->documents)) }}" class="btn btn-sm btn-primary w-100" target="_blank">Lihat Berkas</a>

                                    </div>
                                        @if($slot->status == 'diajukan')
                                            
                                            <div class="col-6">
                                                <form action="{{ route('slot.destroy', $slot->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus pengajuan ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm w-100">Hapus</button>
                                                </form>
                                            </div>
                                        @endif
                                    </div>
                          
                              </td>
                          
                            </tr>
                        @empty
                            <tr>
                                <td colspan="12" class="text-center">Belum ada pengajuan slot charter</td>
                            </tr>
                        @endforelse
                        </tbody>
                      @endnotstaff
                    @endnotadmin

                    @staff

                      <tbody>
                        @forelse ($slots as $index => $slot)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $slot->aircraft_registration }}</td>
                                <td>{{ $slot->aircraft_type }}</td>
                                <td>{{ $slot->departure_schedule }} - {{ $slot->arrival_schedule}}</td>
                                <td>{{ $slot->origin_airport }} - {{ $slot->destination_airport}}</td>
                                <td>{{ $slot->flight_type }}</td>
                                <td>
                                    <span class="badge {{ $slot->status == 'disetujui' ? 'bg-success' : ($slot->status == 'ditolak' ? 'bg-danger' : 'bg-info') }}">
                                        {{ ucfirst($slot->status) }}
                                    </span>
                                </td>
                                <td>
                                  <div class="row g-1">
                                    <div class="col-12 mb-1">
                                      <a href="{{  route('slot.show', $slot->id) }}" class="btn btn-sm btn-primary w-100">
                                        Lihat
                                      </a>
                                    </div>
                                  </div>
                          
                              </td>
                          
                            </tr>
                        @empty
                            <tr>
                                <td colspan="12" class="text-center">Belum ada pengajuan slot charter</td>
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
