@extends('layouts_landing.landing_app')

@section('title', 'Kedatangan Pesawat - Bandara APT Pranoto')

@section('content')

<section id="jadwal-kedatangan" class="section pt-6">
      <div class="container section-title" data-aos="fade-up">
        <h2>Jadwal<br></h2>
        <p><span>Kedatangan Pesawat</span> <span class="description-title">Bandara A.P.T. Pranoto Samarinda</span></p>
      </div>
      <div class="container">
        <div class="row">
          <div class="col-12">
            <div class="table-responsive">
              <table id="arrivalTable" class="table table-striped table-bordered" style="width:100%">
                <thead>
                  <tr>
                    <th>Kode Penerbangan</th>
                    <th>Maskapai</th>
                    <th>Asal Bandara (Kota)</th>
                    <th>Waktu Kedatangan</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody>
                  <!-- Data akan diisi oleh JavaScript -->
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </section>
@endsection


@push('page-styles')
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">

<link href="{{ asset('assets_landing/css/kedatangan.css') }}" rel="stylesheet">
@endpush

@push('page-scripts')
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
  <!-- Main JS File -->
  <script src="{{ asset('assets_landing/js/kedatangan.js') }}"></script>
@endpush