@extends('layouts-V2.master-layouts-v2')
@section('title', 'Manajemen Informasi Publik')
@section('styles_admin')
    <link rel="stylesheet" href="{{ asset('assetsv2/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assetsv2/compiled/css/table-datatable-jquery.css') }}">
@endsection
@section('content')
<div class="page-heading">
                    <div class="page-title">
                        <div class="row">
                            <div class="col-12 col-md-6 order-md-1 order-last">
                                <h3>Manajemen Informasi Publik</h3>
                                <p class="text-subtitle text-muted">Lihat data pengajuan Informasi Publik.</p>
                            </div>
                            <div class="col-12 col-md-6 order-md-2 order-first">
                                <x-breadcrumb2 :items="[
                                        ['label' => 'Menu', 'url' => route('profile')],
                                        ['label' => 'Informasi Publik', 'active' => true]
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
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="card-title">Daftar pengajuan Informasi Publik</h5>
                                
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped" id="table-license">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Nama</th>
                                                <th>Pekerjaan</th>
                                                <th>Email</th>
                                                <th>Status</th>
                                                <th>Link Balasan</th>
                                                <th>Dibuat</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                            @staff
                                        <tbody>
                                            @forelse ($publicInformation as $index => $item)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $item->nama }}</td>
                                                <td>{{ $item->pekerjaan }}</td>
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
                                            

                                            @endforelse
                                        
                                        </tbody>
                                        @endstaff
                                            
                                    </table>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>

@endsection
@section('scripts_admin')
    <script src="{{ asset('assetsv2/extensions/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assetsv2/extensions/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>    
    <script src="{{ asset('assetsv2/compiled/js/staff-license.js') }}"></script>
@endsection