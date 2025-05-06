@extends('layouts.laravel-default')

@section('title', 'Keberangkatan | APT PRANOTO')

@section('content')
    <section class="min-vh-100 hubud-secondary">
        <h2>Daftar Keberangkatan Pesawat</h2>
        
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Penerbangan</th>                     
                        <th>Maskapai</th>                     
                        <th>Tujuan Bandara</th>
                        <th>Tujuan Kota</th>
                        <th>Waktu Keberangkatan</th>
                        <th>Gate</th>                     
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($keberangkatan['data']['result']['data']) && count($keberangkatan['data']['result']['data']) > 0)
                        @foreach($keberangkatan['data']['result']['data'] as $index => $flight)
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
                                <td>{{ $flight['bandara_tujuan']['nama'] }} ({{ $flight['bandara_tujuan']['iata'] }})</td>
                                <td>{{ $flight['bandara_tujuan']['kota_provinsi'] }}</td>
                                <td>{{ \Carbon\Carbon::parse($flight['tanggal'] . ' ' . $flight['jam'])->format('d M Y H:i') }}</td>
                                <td>{{ $flight['gate']['nama'] }}</td>
                                <td>
                                    @php
                                        $statusClass = 'badge bg-secondary';
                                        if($flight['remark']['status'] == 'Check In Open') {
                                            $statusClass = 'badge bg-primary';
                                        } elseif($flight['remark']['status'] == 'Boarding') {
                                            $statusClass = 'badge bg-warning text-dark';
                                        } elseif($flight['remark']['status'] == 'Departured') {
                                            $statusClass = 'badge bg-success';
                                        } elseif($flight['remark']['status'] == 'Delayed') {
                                            $statusClass = 'badge bg-danger';
                                        }
                                    @endphp
                                    <span class="{{ $statusClass }}">{{ $flight['remark']['status'] }}</span>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="7" class="text-center">Tidak ada data penerbangan</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </section>
@endsection
