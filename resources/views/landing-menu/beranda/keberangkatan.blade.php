@extends('layouts_landing.landing_app')

@section('title', 'Keberangkatan Pesawat - Bandara APT Pranoto')

@section('content')
<section id="jadwal-keberangkatan" class="section pt-6">
    <div class="container section-title" data-aos="fade-up">
        <h2>Jadwal<br></h2>
        <p><span>Keberangkatan Pesawat</span> <span class="description-title">Bandara A.P.T. Pranoto Samarinda</span></p>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <!-- Indikator Loading -->
                <div id="loadingIndicator" class="text-center py-4" style="display: none;">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Memuat...</span>
                    </div>
                    <p>Memuat data keberangkatan...</p>
                </div>
                <!-- Placeholder untuk Pesan Error -->
                <div id="errorMessage" class="alert alert-danger" style="display: none;"></div>
                <div class="table-responsive">
                    <table id="departureTable" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>Kode Penerbangan</th>
                                <th>Maskapai</th>
                                <th>Tujuan Bandara (Kota)</th>
                                <th>Waktu Keberangkatan</th>
                                <th>Gate</th>
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
    <!-- Toast Container untuk Pesan Error -->
    <div class="toast-container position-fixed top-0 end-0 p-3">
        <div id="toastNotification" class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="true" data-bs-delay="5000">
            <div class="toast-header bg-danger text-white">
                <strong class="me-auto">Error</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body"></div>
        </div>
    </div>
</section>
@endsection

@push('page-styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<link href="{{ asset('assets_landing/css/keberangkatan.css') }}" rel="stylesheet">
@endpush

@push('page-scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="{{ asset('assets_landing/js/keberangkatan.js') }}"></script>
@endpush