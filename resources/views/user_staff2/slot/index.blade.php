@extends('layouts-V2.master-layouts-v2')
@section('title', 'Manajemen Slot Charter')
@section('styles_admin')
    <link rel="stylesheet" href="{{ asset('assetsv2/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assetsv2/compiled/css/table-datatable-jquery.css') }}">
@endsection
@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Pengajuan Slot Charter</h3>
                <p class="text-subtitle text-muted">Daftar pengajuan slot charter yang telah diajukan</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <x-breadcrumb2 :items="[
                        ['label' => 'Menu', 'url' => route('profile')],
                        ['label' => 'Slot Charter', 'active' => true],
                    ]" />
                    
                </nav>
            </div>
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
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title">Daftar Pengajuan Slot Charter</h5>
            @notadmin
            @notstaff
            <a href="{{ route('slot.create') }}" class="btn btn-success"><i class="bi bi-plus"></i> Tambah Pengajuan</a>
            @endnotstaff
            @endnotadmin
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table" id="table-slot-charter">
                    <thead>
                        <tr>
                            <th>No Regis</th>
                            <th>Tipe Pesawat</th>
                            <th>Jadwal Keberangkatan - Kedatangan</th>
                            <th>Bandara Asal-Tujuan</th>
                            <th>Jenis Penerbangan</th>
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
                            
                        @endforelse
                      </tbody>
                    @endstaff
                </table>
            </div>
        </div>
    </div>
</section>


@endsection
@section('scripts_admin')
    <script src="{{ asset('assetsv2/extensions/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assetsv2/extensions/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>    
    <script src="{{ asset('assetsv2/compiled/js/staff-ajukan-slot.js') }}"></script>
@endsection
