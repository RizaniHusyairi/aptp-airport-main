@extends('layouts-V2.master-layouts-v2')
@section('title', 'Edit Data Penerbangan')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Edit Data Penerbangan</h3>
                <p class="text-subtitle text-muted">Perbarui data penerbangan untuk event: <b>{{ $flight->nataruEvent->name }}</b></p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                 <x-breadcrumb2 :items="[
                        ['label' => 'Dashboard', 'url' => route('staff.dashboard.index')],
                        ['label' => 'Detail Event', 'url' => route('staff.nataru-events.show', $flight->nataru_event_id)],
                        ['label' => 'Edit Penerbangan', 'active' => true]
                    ]" />
            </div>
        </div>
    </div>
</div>

<section class="section">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Form Edit Data</h5>
        </div>
        <div class="card-body">
            
            {{-- Tampilkan Error Validasi --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('nataru.flight.update', $flight->id) }}" method="POST" id="nataruForm">
                @csrf
                @method('PUT')
                
                {{-- SEKSI 1: WAKTU & STATUS --}}
                <div class="divider divider-left"><div class="divider-text fw-bold">1. Informasi Waktu & Status</div></div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Tanggal Penerbangan <span class="text-danger">*</span></label>
                        <input type="date" name="flight_date" class="form-control" value="{{ old('flight_date', $flight->flight_date->format('Y-m-d')) }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Jam (WITA) <span class="text-danger">*</span></label>
                        {{-- Format jam dari H:i:s ke H:i agar sesuai input type time --}}
                        <input type="time" name="flight_time" class="form-control" value="{{ old('flight_time', \Carbon\Carbon::parse($flight->flight_time)->format('H:i')) }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Status Penerbangan <span class="text-danger">*</span></label>
                        <select name="status_flight" class="form-select" required>
                            <option value="Berjadwal" {{ old('status_flight', $flight->status_flight) == 'Berjadwal' ? 'selected' : '' }}>Berjadwal (Scheduled)</option>
                            <option value="Tidak Berjadwal" {{ old('status_flight', $flight->status_flight) == 'Tidak Berjadwal' ? 'selected' : '' }}>Tidak Berjadwal (Charter/Extra)</option>
                            <option value="Perintis" {{ old('status_flight', $flight->status_flight) == 'Perintis' ? 'selected' : '' }}>Perintis</option>
                        </select>
                    </div>
                </div>

                {{-- SEKSI 2: IDENTITAS PENERBANGAN --}}
                <div class="divider divider-left"><div class="divider-text fw-bold">2. Identitas Penerbangan</div></div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Maskapai (Airline) <span class="text-danger">*</span></label>
                        <select name="airline_select" id="airline_select" class="form-select" required>
                            <option value="" disabled>-- Pilih Maskapai --</option>
                            <option value="Batik Air">Batik Air</option>
                            <option value="Garuda Indonesia">Garuda Indonesia</option>
                            <option value="Citilink">Citilink</option>
                            <option value="Super Air Jet">Super Air Jet</option>
                            <option value="Wings Air">Wings Air</option>
                            <option value="Smart Aviation">Smart Aviation</option>
                            <option value="Lainnya">Lainnya..</option>
                        </select>
                        
                        <div id="other_airline_container" class="mt-2" style="display: none;">
                            <input type="text" name="other_airline" id="other_airline" class="form-control" placeholder="Tulis nama maskapai..." value="{{ old('other_airline') }}">
                        </div>
                        <input type="hidden" name="airline" id="airline_final" value="{{ old('airline', $flight->airline) }}">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nomor Penerbangan <span class="text-danger">*</span></label>
                        <input type="text" name="flight_number" class="form-control" value="{{ old('flight_number', $flight->flight_number) }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Arah (Direction) <span class="text-danger">*</span></label>
                        <select name="direction" class="form-select" required>
                            <option value="arrival" {{ old('direction', $flight->direction) == 'arrival' ? 'selected' : '' }}>Arrival (Kedatangan)</option>
                            <option value="departure" {{ old('direction', $flight->direction) == 'departure' ? 'selected' : '' }}>Departure (Keberangkatan)</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Rute (From - To) <span class="text-danger">*</span></label>
                        <input type="text" name="route" class="form-control" value="{{ old('route', $flight->route) }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tipe Pesawat <span class="text-danger">*</span></label>
                        <input type="text" name="aircraft_type" class="form-control" value="{{ old('aircraft_type', $flight->aircraft_type) }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Registrasi Pesawat (PK) <span class="text-danger">*</span> </label>
                        <input type="text" name="aircraft_registration" class="form-control" value="{{ old('aircraft_registration', $flight->aircraft_registration) }}" required>
                    </div>
                </div>

                {{-- SEKSI 3: DATA MUATAN --}}
                <div class="divider divider-left"><div class="divider-text fw-bold">3. Data Muatan (Payload)</div></div>
                <div class="row">
                    <div class="col-md-3 col-6 mb-3">
                        <label class="form-label">Dewasa (Adult)</label>
                        <input type="number" name="pax_adult" id="pax_adult" class="form-control pax-input" value="{{ old('pax_adult', $flight->pax_adult) }}" min="0" required>
                    </div>
                    <div class="col-md-3 col-6 mb-3">
                        <label class="form-label">Anak (Child)</label>
                        <input type="number" name="pax_child" id="pax_child" class="form-control pax-input" value="{{ old('pax_child', $flight->pax_child) }}" min="0" required>
                    </div>
                    <div class="col-md-3 col-6 mb-3">
                        <label class="form-label">Bayi (Infant)</label>
                        <input type="number" name="pax_infant" id="pax_infant" class="form-control pax-input" value="{{ old('pax_infant', $flight->pax_infant) }}" min="0" required>
                    </div>
                    <div class="col-md-3 col-6 mb-3">
                        <label class="form-label fw-bold">Total Pax</label>
                        <input type="text" id="total_pax_display" class="form-control fw-bold" value="{{ $flight->pax_total }}" readonly>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Kargo (Kg)</label>
                        <input type="number" name="cargo" class="form-control" value="{{ old('cargo', $flight->cargo) }}" min="0" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Bagasi (Kg)</label>
                        <input type="number" name="baggage" class="form-control" value="{{ old('baggage', $flight->baggage) }}" min="0" required>
                    </div>
                    {{-- Kapasitas Kursi perlu dihitung balik dari LF jika data seat_capacity tidak tersimpan di DB (asumsi fieldnya ada tapi nullable) --}}
                    {{-- Di controller store sebelumnya tidak ada 'seat_capacity' di Model create, jika tidak ada di DB, kita biarkan kosong --}}
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Kapasitas Kursi (Seat Cap)</label>
                        @php
                            // Coba hitung balik seat capacity dari LF jika tidak tersimpan, hanya estimasi
                            $estimatedSeat = 0;
                            if($flight->load_factor > 0 && ($flight->pax_adult + $flight->pax_child) > 0) {
                                $estimatedSeat = round(($flight->pax_adult + $flight->pax_child) / ($flight->load_factor / 100));
                            }
                        @endphp
                        <input type="number" name="seat_capacity" id="seat_capacity" class="form-control" 
                               value="{{ old('seat_capacity', $estimatedSeat > 0 ? $estimatedSeat : '') }}" min="1">
                        <small class="text-primary" id="lf_preview">LF: {{ $flight->load_factor }}%</small>
                    </div>
                </div>

                {{-- SEKSI 4: DATA EKONOMI --}}
                <div class="divider divider-left"><div class="divider-text fw-bold">4. Data Ekonomi (Tiket)</div></div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Harga Tiket Tertinggi</label>
                        <input type="text" name="ticket_price_high" id="ticket_price_high" class="form-control rupiah-input" 
                            {{-- PERBAIKAN: Tambahkan (int) --}}
                            value="{{ old('ticket_price_high', (int) $flight->ticket_price_high) }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Harga Tiket Terendah</label>
                        <input type="text" name="ticket_price_low" id="ticket_price_low" class="form-control rupiah-input" 
                            {{-- PERBAIKAN: Tambahkan (int) --}}
                            value="{{ old('ticket_price_low', (int) $flight->ticket_price_low) }}">
                    </div>
                </div>

                {{-- SEKSI 5: DATA PETUGAS --}}
                <div class="divider divider-left"><div class="divider-text fw-bold">5. Validasi Petugas</div></div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nama Petugas Posko <span class="text-danger">*</span></label>
                        <input type="text" name="officer_name" class="form-control" value="{{ old('officer_name', $flight->officer_name) }}" required>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Catatan Tambahan</label>
                        <textarea name="remarks" class="form-control" rows="2">{{ old('remarks', $flight->remarks) }}</textarea>
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <a href="{{ route('staff.nataru-events.show', $flight->nataru_event_id) }}" class="btn btn-secondary me-2">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>

            </form>
        </div>
    </div>
</section>
@endsection

@section('scripts_admin')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // 1. Logika Dropdown Maskapai (Pintar mendeteksi 'Lainnya')
        const airlineSelect = document.getElementById('airline_select');
        const otherAirlineContainer = document.getElementById('other_airline_container');
        const otherAirlineInput = document.getElementById('other_airline');
        const airlineFinalInput = document.getElementById('airline_final');

        // Daftar maskapai standar sesuai option HTML
        const standardAirlines = ["Batik Air", "Garuda Indonesia", "Citilink", "Super Air Jet", "Wings Air", "Smart Aviation"];
        const currentDbAirline = "{{ $flight->airline }}"; // Ambil dari PHP

        // Set Initial Value
        if (standardAirlines.includes(currentDbAirline)) {
            airlineSelect.value = currentDbAirline;
        } else {
            airlineSelect.value = 'Lainnya';
            otherAirlineInput.value = currentDbAirline;
        }

        function handleAirlineChange() {
            if (airlineSelect.value === 'Lainnya') {
                otherAirlineContainer.style.display = 'block';
                otherAirlineInput.setAttribute('required', 'required');
                // Jika user baru klik 'Lainnya', jangan timpa input text jika sudah ada isinya dari DB
                if(airlineSelect.value !== 'Lainnya') airlineFinalInput.value = otherAirlineInput.value;
            } else {
                otherAirlineContainer.style.display = 'none';
                otherAirlineInput.removeAttribute('required');
                airlineFinalInput.value = airlineSelect.value;
            }
        }

        // Jalankan sekali saat load
        handleAirlineChange();

        // Event Listeners
        airlineSelect.addEventListener('change', function() {
            handleAirlineChange();
            if(this.value !== 'Lainnya') airlineFinalInput.value = this.value;
        });

        otherAirlineInput.addEventListener('input', function() {
            if (airlineSelect.value === 'Lainnya') {
                airlineFinalInput.value = this.value;
            }
        });


        // 2. Logika Hitung Pax & LF
        const adultInput = document.getElementById('pax_adult');
        const childInput = document.getElementById('pax_child');
        const infantInput = document.getElementById('pax_infant');
        const totalDisplay = document.getElementById('total_pax_display');
        const seatCapInput = document.getElementById('seat_capacity');
        const lfPreview = document.getElementById('lf_preview');

        function calculate() {
            const adult = parseInt(adultInput.value) || 0;
            const child = parseInt(childInput.value) || 0;
            const infant = parseInt(infantInput.value) || 0;
            
            const total = adult + child + infant;
            totalDisplay.value = total;

            const seatsOccupied = adult + child;
            const capacity = parseInt(seatCapInput.value) || 0;

            if (capacity > 0) {
                const lf = (seatsOccupied / capacity) * 100;
                lfPreview.innerText = 'LF Estimasi: ' + lf.toFixed(2) + '%';
            } else {
                lfPreview.innerText = 'LF: -';
            }
        }

        [adultInput, childInput, infantInput, seatCapInput].forEach(input => {
            input.addEventListener('input', calculate);
        });


        // 3. Logika Format Rupiah
        const rupiahInputs = document.querySelectorAll('.rupiah-input');

        function formatRupiah(angka, prefix) {
            var number_string = angka.replace(/[^,\d]/g, '').toString(),
                split = number_string.split(','),
                sisa = split[0].length % 3,
                rupiah = split[0].substr(0, sisa),
                ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }
            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            return prefix == undefined ? rupiah : (rupiah ? 'Rp ' + rupiah : '');
        }

        rupiahInputs.forEach(input => {
            // Format saat user mengetik
            input.addEventListener('keyup', function(e) {
                this.value = formatRupiah(this.value, 'Rp');
            });
            
            // Format Nilai Awal dari DB (Penting!)
            if(input.value) {
                input.value = formatRupiah(input.value, 'Rp');
            }
        });

        // 4. Bersihkan Rupiah sebelum Submit
        const form = document.getElementById('nataruForm');
        form.addEventListener('submit', function() {
            // Pastikan nilai airline 'Lainnya' terisi benar
            if (airlineSelect.value === 'Lainnya') {
                airlineFinalInput.value = otherAirlineInput.value;
            }

            // Bersihkan Rupiah jadi angka murni
            rupiahInputs.forEach(input => {
                input.value = input.value.replace(/[^0-9]/g, '');
            });
        });
    });
</script>
@endsection