@extends('layouts.master')

@section('title')
  Pengajuan Lelang/Beauty Contest
@endsection

@section('content')
  @component('components.breadcrumb')
    @slot('li_1') Lelang/Beauty Contest @endslot
    @slot('title') Pengajuan Lelang/Beauty Contest @endslot
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
                  <h4 class="card-title">Daftar Lelang/Beauty Contest</h4>
                  @notadmin
                    @notstaff
                      <a href='{{ route("lelang.create") }}' class="btn btn-success btn-sm">+ Tambah Pengajuan</a>
                    @endnotstaff
                  @endnotadmin
                </div>
                <div class="card-body">
                  <table id="submission-table" class="table table-bordered table-hover">
                    <thead>
                      <tr>
                        <th>#</th>
                          <th>Nama Pengajuan</th>
                          <th>Jenis</th>
                          <th>Dokumen</th>
                          <th>Dokumen Tambahan</th>
                          <th>Status</th>
                          <th>Aksi</th>
                      </tr>
                    </thead>
                    @notadmin
                      @notstaff
                        <tbody>
                          @forelse ($lelangs as $index => $lelang)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $lelang->name }}</td>
                                <td>{{ $lelang->lelang_type }}</td>
                                <td>
                                    <a href="{{ asset('storage/' . $lelang->documents) }}" target="_blank">
                                        {{ preg_replace('/^\d+_/', '', basename($lelang->documents)) }}
                                    </a>
                                </td>
                                <td>
                                  @if($lelang->additional_documents)
                                      <a href="{{ asset('storage/' . $lelang->additional_documents) }}" target="_blank">
                                          {{ preg_replace('/^\d+_additional_/', '', basename($lelang->additional_documents)) }}
                                      </a>
                                  @else
                                      -
                                  @endif
                              </td>
                                <td>
                                    <span class="badge {{ $lelang->submission_status == 'disetujui' ? 'bg-success' : ($lelang->submission_status == 'ditolak' ? 'bg-danger' : 'bg-info') }}">
                                        {{ ucfirst($lelang->submission_status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="row g-1">
                                        @if($lelang->submission_status == 'diajukan')
                                            {{-- <div class="col">
                                                <a href="{{ route('lelang.edit', $lelang->id) }}" class="btn btn-sm btn-warning w-100">Edit</a>
                                            </div> --}}
                                            <div class="col">
                                                <form action="{{ route('lelang.destroy', $lelang->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus pengajuan ini?')">
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
                                <td colspan="7" class="text-center">Belum ada pengajuan lelang/beauty contest</td>
                            </tr>
                        @endforelse
                        </tbody>
                      @endnotstaff
                    @endnotadmin

                    @staff
                      <tbody>
                        @forelse ($lelangs as $index => $lelang)
                          <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $lelang->documents ? preg_replace('/^\d+_/', '', basename($lelang->documents)) : '-' }}</td>
                            <td>{{ $lelang->created_at->format('d M Y H:i') }}</td>
                            <td>
                              @php
                                $status = $lelang->submission_status;
                                $badgeClass = match($status) {
                                    'disetujui' => 'bg-success',
                                    'ditolak' => 'bg-danger',
                                    default => 'bg-info',
                                };
                              @endphp
                              <span class="badge {{ $badgeClass }}">{{ ucfirst($status) }}</span>
                            </td>
                            <td>
                              @foreach ($lelang->users as $user)
                                <span class="badge bg-secondary">{{ $user->name }}</span>
                              @endforeach
                            </td>
                            <td>
                              <div class="row g-1">
                                <div class="col-12 mb-1">
                                  <a href="{{  route('perijinan.show', $lelang->id) }}" class="btn btn-sm btn-primary w-100">
                                    Lihat
                                  </a>
                                </div>
                              </div>
                            </td>
                          </tr>
                        @empty
                          <tr>
                            <td colspan="12" class="text-center">Belum ada pengajuan Lelang</td>
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
