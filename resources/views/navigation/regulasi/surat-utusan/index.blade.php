@extends('layouts.laravel-default')

@section('title', 'Profil Bandara | APT PRANOTO')
@push('styles')
    <style>
        .letters-container {
            padding: 40px 0;
        }
        .table-letters {
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        .table-letters th, .table-letters td {
            vertical-align: middle;
        }
        .btn-download {
            font-size: 0.85rem;
            padding: 5px 10px;
        }
        .btn-download i {
            margin-right: 5px;
        }
        @media (max-width: 576px) {
            .table-letters th, .table-letters td {
                font-size: 0.8rem;
            }
            .btn-download {
                font-size: 0.75rem;
                padding: 4px 8px;
            }
        }
    </style>
@endpush


@section('content')
{{-- <section class="pb-5">
  <div class="container">
    <h2 class="mb-4 fw-bold fs-1 text-center">Surat Edaran</h2>



  </div>
</section> --}}

<div class="container letters-container">
        <h2 class="text-center mb-4"><i class='bx bx-file'></i> Daftar Surat Utusan</h2>
        <div class="table-responsive">
            <table class="table table-striped table-hover table-letters">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">No Surat</th>
                        <th scope="col">Judul</th>
                        <th scope="col">Tanggal Terbit</th>
                        <th scope="col">Unduh</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($letters as $letter)
                        <tr>
                            <td>{{ $letter->number }}</td>
                            <td>{{ $letter->title }}</td>
                            <td>{{ \Carbon\Carbon::parse($letter->issue_date)->translatedFormat('d F Y') }}</td>
                            <td>
                                <a href="{{ asset($letter->file_path) }}" class="btn btn-primary btn-download" download>
                                    <i class='bx bx-download'></i> Unduh
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">Tidak ada surat edaran tersedia.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
