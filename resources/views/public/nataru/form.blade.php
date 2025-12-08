<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Data Posko - {{ $event->name }}</title>
    
    <link rel="stylesheet" href="{{ asset('assetsv2/compiled/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('assetsv2/compiled/css/app-dark.css') }}">
    <link rel="stylesheet" href="{{ asset('assetsv2/compiled/css/iconly.css') }}">
    
    <style>
        body { background-color: #f2f7ff; }
        .header-posko {
            background: linear-gradient(45deg, #0d2c4a, #435ebe);
            color: white;
            padding: 2rem 1rem;
            text-align: center;
            border-radius: 0 0 20px 20px;
            margin-bottom: -30px;
        }
        .card-form {
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border: none;
            border-radius: 15px;
        }
        .form-section-title {
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #435ebe;
            border-bottom: 2px solid #eef2f7;
            padding-bottom: 10px;
            margin-bottom: 20px;
            margin-top: 10px;
            font-weight: 700;
        }
        .required-star { color: red; }
    </style>
</head>
<body>

    {{-- Header --}}
    <div class="header-posko">
        <h2 class="text-white mb-0">{{ $event->name }}</h2>
        <p class="opacity-75">Bandara A.P.T. Pranoto Samarinda</p>
        <span class="badge bg-success mt-2">Periode: {{ $event->start_date->format('d M') }} - {{ $event->end_date->format('d M Y') }}</span>
    </div>

    <div class="container" style="max-width: 800px; padding-bottom: 50px;">
        <div class="card card-form mt-5">
            <div class="card-body p-4">
                
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-circle me-2"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('public.nataru.store', $event->public_token) }}" method="POST" id="nataruForm">
                    @csrf
                    
                    {{-- SEKSI 1: WAKTU & STATUS --}}
                    <div class="form-section-title">1. Informasi Waktu & Status</div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Tanggal Penerbangan <span class="required-star">*</span></label>
                            <input type="date" name="flight_date" class="form-control" value="{{ old('flight_date', date('Y-m-d')) }}" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Jam (WITA) <span class="required-star">*</span></label>
                            <input type="time" name="flight_time" class="form-control" value="{{ old('flight_time') }}" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Status Penerbangan <span class="required-star">*</span></label>
                            <select name="status_flight" class="form-select" required>
                                <option value="Berjadwal" {{ old('status_flight') == 'Berjadwal' ? 'selected' : '' }}>Berjadwal (Scheduled)</option>
                                <option value="Tidak Berjadwal" {{ old('status_flight') == 'Tidak Berjadwal' ? 'selected' : '' }}>Tidak Berjadwal (Charter/Extra)</option>
                                <option value="Perintis" {{ old('status_flight') == 'Perintis' ? 'selected' : '' }}>Perintis</option>
                            </select>
                        </div>
                    </div>

                    {{-- SEKSI 2: IDENTITAS PENERBANGAN --}}
                    <div class="form-section-title">2. Identitas Penerbangan</div>
                    <div class="row">
                        {{-- === UPDATE INPUT MASKAPAI === --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Maskapai (Airline) <span class="required-star">*</span></label>
                            <select name="airline_select" id="airline_select" class="form-select" required>
                                <option value="" selected disabled>-- Pilih Maskapai --</option>
                                <option value="Batik Air" {{ old('airline_select') == 'Batik Air' ? 'selected' : '' }}>Batik Air</option>
                                <option value="Garuda Indonesia" {{ old('airline_select') == 'Garuda Indonesia' ? 'selected' : '' }}>Garuda Indonesia</option>
                                <option value="Citilink" {{ old('airline_select') == 'Citilink' ? 'selected' : '' }}>Citilink</option>
                                <option value="Super Air Jet" {{ old('airline_select') == 'Super Air Jet' ? 'selected' : '' }}>Super Air Jet</option>
                                <option value="Wings Air" {{ old('airline_select') == 'Wings Air' ? 'selected' : '' }}>Wings Air</option>
                                <option value="Smart Aviation" {{ old('airline_select') == 'Smart Aviation' ? 'selected' : '' }}>Smart Aviation</option>
                                
                                <option value="Lainnya" {{ old('airline_select') == 'Lainnya' ? 'selected' : '' }}>Lainnya..</option>
                            </select>
                            
                            {{-- Input Tambahan untuk Lainnya --}}
                            <div id="other_airline_container" class="mt-2" style="display: none;">
                                <input type="text" name="other_airline" id="other_airline" class="form-control" placeholder="Tulis nama maskapai..." value="{{ old('other_airline') }}">
                            </div>
                            
                            {{-- Input Hidden yang akan dikirim ke server sebagai 'airline' --}}
                            <input type="hidden" name="airline" id="airline_final" value="{{ old('airline') }}">
                        </div>
                        {{-- ============================ --}}

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nomor Penerbangan <span class="required-star">*</span></label>
                            <input type="text" name="flight_number" class="form-control" placeholder="Contoh: ID-6257" value="{{ old('flight_number') }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Status <span class="required-star">*</span></label>
                            <select name="direction" class="form-select" required>
                                <option value="" selected disabled>-- Pilih Arah --</option>
                                <option value="arrival" {{ old('direction') == 'arrival' ? 'selected' : '' }}>Arrival (Kedatangan)</option>
                                <option value="departure" {{ old('direction') == 'departure' ? 'selected' : '' }}>Departure (Keberangkatan)</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Rute (From - To) <span class="required-star">*</span></label>
                            <input type="text" name="route" class="form-control" placeholder="Contoh: CGK-AAP" value="{{ old('route') }}" required>
                            <small class="text-muted">Gunakan kode bandara (misal: SUB-AAP)</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tipe Pesawat</label>
                            <input type="text" name="aircraft_type" class="form-control" placeholder="Contoh: A320, B737-800" value="{{ old('aircraft_type') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Registrasi Pesawat (PK)</label>
                            <input type="text" name="aircraft_registration" class="form-control" placeholder="Contoh: PK-LUI" value="{{ old('aircraft_registration') }}">
                        </div>
                    </div>

                    {{-- SEKSI 3: DATA MUATAN --}}
                    <div class="form-section-title">3. Data Muatan (Payload)</div>
                    <div class="row">
                        <div class="col-md-3 col-6 mb-3">
                            <label class="form-label">Dewasa (Adult)</label>
                            <input type="number" name="pax_adult" id="pax_adult" class="form-control pax-input" value="{{ old('pax_adult', 0) }}" min="0" required>
                        </div>
                        <div class="col-md-3 col-6 mb-3">
                            <label class="form-label">Anak (Child)</label>
                            <input type="number" name="pax_child" id="pax_child" class="form-control pax-input" value="{{ old('pax_child', 0) }}" min="0" required>
                        </div>
                        <div class="col-md-3 col-6 mb-3">
                            <label class="form-label">Bayi (Infant)</label>
                            <input type="number" name="pax_infant" id="pax_infant" class="form-control pax-input" value="{{ old('pax_infant', 0) }}" min="0" required>
                        </div>
                        <div class="col-md-3 col-6 mb-3">
                            <label class="form-label fw-bold">Total Pax</label>
                            <input type="text" id="total_pax_display" class="form-control bg-light fw-bold" value="0" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Kargo (Kg)</label>
                            <input type="number" name="cargo" class="form-control" value="{{ old('cargo', 0) }}" min="0" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Bagasi (Kg)</label>
                            <input type="number" name="baggage" class="form-control" value="{{ old('baggage', 0) }}" min="0" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Kapasitas Kursi (Seat Cap)</label>
                            <input type="number" name="seat_capacity" id="seat_capacity" class="form-control" placeholder="Untuk hitung LF" value="{{ old('seat_capacity') }}" min="1">
                            <small class="text-primary" id="lf_preview">LF: -</small>
                        </div>
                    </div>

                    {{-- SEKSI 4: DATA EKONOMI --}}
                    <div class="form-section-title">4. Data Ekonomi (Tiket)</div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Harga Tiket Tertinggi</label>
                            {{-- Ganti type="number" dengan "text" agar bisa format rupiah --}}
                            <input type="text" name="ticket_price_high" id="ticket_price_high" class="form-control rupiah-input" placeholder="Rp 0" value="{{ old('ticket_price_high') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Harga Tiket Terendah</label>
                            {{-- Ganti type="number" dengan "text" agar bisa format rupiah --}}
                            <input type="text" name="ticket_price_low" id="ticket_price_low" class="form-control rupiah-input" placeholder="Rp 0" value="{{ old('ticket_price_low') }}">
                        </div>
                    </div>

                    {{-- SEKSI 5: DATA PETUGAS --}}
                    <div class="form-section-title">5. Validasi Petugas</div>
                    <div class="mb-3">
                        <label class="form-label">Nama Petugas Posko <span class="required-star">*</span></label>
                        <input type="text" name="officer_name" class="form-control" placeholder="Masukkan nama lengkap Anda" value="{{ old('officer_name') }}" required>
                        <small class="text-muted">Nama ini akan tercatat sebagai penanggung jawab data ini.</small>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Catatan Tambahan (Opsional)</label>
                        <textarea name="remarks" class="form-control" rows="2" placeholder="Keterangan delay, kendala, dll.">{{ old('remarks') }}</textarea>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-send-fill me-2"></i> Kirim Data Penerbangan
                        </button>
                    </div>

                </form>
            </div>
            <div class="card-footer text-center bg-light">
                <small class="text-muted">&copy; {{ date('Y') }} Bandara APT Pranoto - Sistem Informasi Posko</small>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 1. Logika Dropdown Maskapai
            const airlineSelect = document.getElementById('airline_select');
            const otherAirlineContainer = document.getElementById('other_airline_container');
            const otherAirlineInput = document.getElementById('other_airline');
            const airlineFinalInput = document.getElementById('airline_final');

            function handleAirlineChange() {
                if (airlineSelect.value === 'Lainnya') {
                    otherAirlineContainer.style.display = 'block';
                    otherAirlineInput.setAttribute('required', 'required');
                    airlineFinalInput.value = otherAirlineInput.value;
                } else {
                    otherAirlineContainer.style.display = 'none';
                    otherAirlineInput.removeAttribute('required');
                    airlineFinalInput.value = airlineSelect.value;
                }
            }

            // Event saat dropdown berubah
            airlineSelect.addEventListener('change', handleAirlineChange);

            // Event saat input "Lainnya" diketik
            otherAirlineInput.addEventListener('input', function() {
                if (airlineSelect.value === 'Lainnya') {
                    airlineFinalInput.value = this.value;
                }
            });

            // Jalankan sekali saat load (untuk handle old input saat validasi gagal)
            handleAirlineChange();


            // 2. Logika Hitung Pax & LF (Yang sudah ada sebelumnya)
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
            
            // Hitung awal
            calculate();

            // 3. Logika Format Rupiah
            const rupiahInputs = document.querySelectorAll('.rupiah-input');

            // Fungsi Format Rupiah
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

            // Pasang event listener untuk input rupiah
            rupiahInputs.forEach(input => {
                // Saat diketik
                input.addEventListener('keyup', function(e) {
                    this.value = formatRupiah(this.value, 'Rp');
                });
                
                // Format awal jika ada old input
                if(input.value) {
                    input.value = formatRupiah(input.value, 'Rp');
                }
            });

            // Bersihkan format rupiah sebelum submit agar data bersih (integer/decimal)
            const form = document.getElementById('nataruForm');
            form.addEventListener('submit', function() {
                rupiahInputs.forEach(input => {
                    // Hapus karakter non-digit (termasuk "Rp", titik, spasi)
                    // Sisakan koma jika ingin support desimal, lalu ganti jadi titik
                    // Di sini kita asumsikan harga bulat (integer)
                    input.value = input.value.replace(/[^0-9]/g, '');
                });
            });
        });
    </script>
</body>
</html>