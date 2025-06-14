@extends('layouts-V2.master-layouts-v2')
@section('title', 'Manajemen Pengaduan')
@section('styles_admin')
    <link rel="stylesheet" href="{{ asset('assetsv2/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assetsv2/compiled/css/table-datatable-jquery.css') }}">
@endsection
@section('content')

<div class="page-heading">
                    <div class="page-title">
                        <div class="row">
                            <div class="col-12 col-md-6 order-md-1 order-last">
                                <h3>Manajemen Pengaduan</h3>
                                <p class="text-subtitle text-muted">Kelola pengaduan untuk staff.</p>
                            </div>
                            <div class="col-12 col-md-6 order-md-2 order-first">
                                <x-breadcrumb2 :items="[
                                        ['label' => 'Menu', 'url' => route('profile')],
                                        ['label' => 'Pengaduan', 'active' => true]
                                    ]" />
                                
                            </div>
                        </div>
                    </div>
                    <section class="section">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">Daftar Pengaduan</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped" id="table-pengaduan">
                                        <thead>
                                            <tr>
                                                <th>Nama</th>
                                                <th>Email</th>
                                                <th>Kategori</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                                <th>Pesan</th>
                                            </tr>
                                        </thead>
                                        @staff()
                                        <tbody>
                                            @forelse ($complaints as $index => $complaint)
                                                <tr data-id="{{ $complaint->id }}" data-telepon="{{ $complaint->phone_number }}" data-url="{{ route('pengaduan.staffUpdate', $complaint->id) }}">
                                                    <td>{{ $complaint->name }}</td>
                                                    <td>{{ $complaint->email }}</td>
                                                    <td>{{ $complaint->subject }}</td>
                                                    <td><span class="badge bg-{{ $complaint->status == 'Selesai' ? 'success' : ($complaint->status == 'Diproses' ? 'primary' : 'warning') }}">{{ $complaint->status }}</span></td>
                                                    <td class="d-flex">
                                                        <button class="btn btn-sm btn-info me-1 change-status" data-bs-toggle="tooltip" title="Ubah Status" aria-label="Ubah status pengaduan {{ $complaint->name }}"><i class="bi bi-pencil"></i></button>
                                                        <form action="{{ route('pengaduan.Staffdestroy', $complaint->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus pengaduan ini?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button class="btn btn-sm btn-danger delete-complaint" data-bs-toggle="tooltip" title="Hapus" aria-label="Hapus pengaduan {{ $complaint->name }}"><i class="bi bi-trash"></i></button>
                                                        </form>
                                                    </td>
                                                    <td>
                                                        <button class="btn btn-sm btn-link text-primary show-message" data-message="{{ $complaint->message }}" data-bs-toggle="tooltip" title="Lihat Pesan" aria-label="Lihat pesan pengaduan {{ $complaint->name }}">
                                                            <i class="bi bi-chevron-down"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @empty
                                                
                                            @endforelse
                                        @endstaff
                                        
                                    </table>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
                <!-- Modal untuk Ubah Status -->
                <div class="modal fade" id="modal-change-status" tabindex="-1" aria-labelledby="modal-change-status-label" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modal-change-status-label">Ubah Status Pengaduan</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="" method="POST" id="form-change-status">
                                      @csrf
                                      @method('PATCH')
                            
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Nama Pengadu</label>
                                                <p id="modal-nama" class="form-control-static"></p>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Email</label>
                                                <p id="modal-email" class="form-control-static"></p>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Nomor Telepon</label>
                                                <p id="modal-telepon" class="form-control-static"></p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Kategori</label>
                                                <p id="modal-kategori" class="form-control-static"></p>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Pesan Pengaduan</label>
                                                <div id="modal-pesan" class="form-control" style="height: 100px; overflow-y: auto;"></div>
                                            </div>
                                            <div class="mb-3">
                                                <label for="status" class="form-label">Pilih Status</label>
                                                <select id="status" name="status" class="form-select" required>
                                                    <option value="Menunggu">Menunggu</option>
                                                    <option value="Diproses">Diproses</option>
                                                    <option value="Selesai">Selesai</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" id="complaint-id" name="complaint_id">
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    

                                <button type="submit" class="btn btn-primary">Simpan</button>
                                
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                @endsection
@section('scripts_admin')
    <script src="{{ asset('assetsv2/extensions/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assetsv2/extensions/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
    
    <script src="{{ asset('../assetsv2/compiled/js/staff-pengaduan.js') }}"></script>
@endsection