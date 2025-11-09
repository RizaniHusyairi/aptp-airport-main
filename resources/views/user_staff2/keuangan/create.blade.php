@extends('layouts-V2.master-layouts-v2')
@section('title', 'Tambah Laporan Keuangan')
@section('styles_admin')
    
@endsection
@section('content')
<div class="page-heading">
                    <div class="page-title">
                        <div class="row">
                            <div class="col-12 col-md-6 order-md-1 order-last">
                                <h3>Tambah Laporan Keuangan</h3>
                                <p class="text-subtitle text-muted">Tambah laporan keuangan baru.</p>
                            </div>
                            <div class="col-12 col-md-6 order-md-2 order-first">
                                <x-breadcrumb2 :items="[
                                    ['label' => 'Menu', 'url' => route('staff.dashboard.index')],
                                    ['label' => 'Laporan Keuangan', 'url' => route('keuangan.index')],
                                    ['label' => 'Tambah Laporan Keuangan', 'active' => true]
                                ]" />
                                
                            </div>
                        </div>
                    </div>
                    <section class="section">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">Form Tambah Laporan Keuangan</h5>
                            </div>
                            <div class="card-body">
                                @if($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                                @endif
                                <form id="form-laporan-keuangan" action="{{ route('keuangan.store') }}" method="POST">
                                    @include('user_staff2.keuangan._form')
                                </form>
                            </div>
                        </div>
                    </section>
                </div>
                @endsection
                
@section('scripts_admin')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script src="{{ asset('assetsv2/extensions/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assetsv2/extensions/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assetsv2/extensions/cleave.js/cleave.min.js') }}"></script>
    
    <script src="{{ asset('../assetsv2/compiled/js/staff-tambah-laporan.js') }}"></script>
@endsection