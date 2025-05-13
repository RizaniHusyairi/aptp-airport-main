@extends('layouts.master')

@section('title')
  Pengaduan
@endsection

@section('content')
  @component('components.breadcrumb')
    @slot('li_1') Pengaduan @endslot
    @slot('title') Kelola Pengaduan @endslot
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
                  <h4 class="card-title">Daftar Pengaduan</h4>
                </div>
                <div class="card-body">
                  <table id="submission-table" class="table table-bordered table-hover">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Subjek</th>
                        <th>Pesan</th>
                        <th>Status</th>
                        <th>Aksi</th>                      
                      </tr>
                    </thead>

                    @staff
                      <tbody>
                        @forelse ($complaints as $index => $complaint)
                          <tr>
                              <td>{{ $index + 1 }}</td>
                              <td>{{ $complaint->name }}</td>
                              <td>{{ $complaint->email }}</td>
                              <td>{{ $complaint->subject }}</td>
                              <td>{{ Str::limit($complaint->message, 50) }}</td>
                              <td>
                                  <form action="{{ route('pengaduan.staffUpdate', $complaint->id) }}" method="POST">
                                      @csrf
                                      @method('PATCH')
                                      <select name="status" class="form-control" onchange="this.form.submit()">
                                          <option value="pending" {{ $complaint->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                          <option value="processed" {{ $complaint->status == 'processed' ? 'selected' : '' }}>Diproses</option>
                                          <option value="resolved" {{ $complaint->status == 'resolved' ? 'selected' : '' }}>Selesai</option>
                                      </select>
                                  </form>
                              </td>
                              <td>
                                  <form action="{{ route('pengaduan.Staffdestroy', $complaint->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus pengaduan ini?')">
                                      @csrf
                                      @method('DELETE')
                                      <button type="submit" class="btn btn-danger btn-sm"><i class='bx bx-trash'></i> Hapus</button>
                                  </form>
                              </td>
                          </tr>
                          @empty
                            <tr>
                              <td colspan="7" class="text-center">Belum ada pengaduan</td>
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
