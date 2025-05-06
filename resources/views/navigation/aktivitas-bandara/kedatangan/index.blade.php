@extends('layouts.laravel-default')

@section('title', 'Kedatangan | APT PRANOTO')

@section('content')
    <section class="min-vh-100 hubud-secondary">
        <h2>Daftar Kedatangan Pesawat</h2>
        
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Penerbangan</th>                     
                        <th>Maskapai</th>                     
                        <th>Asal Bandara</th>                     
                        <th>Asal Kota</th>                     
                        <th>Waktu Kedatangan</th>                     
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($kedatangan['data']['result']['data']) && count($kedatangan['data']['result']['data']) > 0)
                        @foreach($kedatangan['data']['result']['data'] as $index => $flight)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $flight['pesawat']['kode_penerbangan'] }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img 
                                            src="http://103.210.122.2/storage/logo/{{ $flight['maskapai']['logo']}}" 
                                            class="img-fluid"
                                            alt="{{ $flight['maskapai']['nama'] }}"
                                            style="max-width: 150px; height: auto;"
                                        >
                                    </div>
                                </td>
                                <td>{{ $flight['bandara_asal']['nama'] }}</td>
                                <td>{{ $flight['bandara_asal']['kota_provinsi'] }}</td>
                                <td>{{ \Carbon\Carbon::parse($flight['tanggal'] . ' ' . $flight['jam'])->format('d M Y H:i') }}</td>
                                <td>{{ $flight['remark']['status'] }}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5" class="text-center">Tidak ada data penerbangan</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </section>
@endsection
