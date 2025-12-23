<?php

namespace App\Http\Controllers\Staff_User;

use App\Http\Controllers\Controller;
use App\Models\NataruEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Exports\NataruFlightExport; // Import Class Export
use Maatwebsite\Excel\Facades\Excel; // Import Facade Excel
use App\Models\NataruFlight;
use Barryvdh\DomPDF\Facade\Pdf; // Import PDF
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;


class NataruEventController extends Controller
{
    public function index()
    {
        // Eager load compareEvent untuk optimasi query
        $events = NataruEvent::with('compareEvent')->latest()->get();
        return view('user_staff2.nataru.event.index', compact('events'));
    }

    public function create()
    {
        // Ambil semua event untuk dijadikan pilihan pembanding
        $events = NataruEvent::orderBy('start_date', 'desc')->get();
        return view('user_staff2.nataru.event.create', compact('events'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'description' => 'nullable|string',
            'compare_event_id' => 'nullable|exists:nataru_events,id', // Validasi baru
        ], [
            'name.required' => 'Nama event wajib diisi.',
            'start_date.required' => 'Tanggal mulai wajib diisi.',
            'end_date.after_or_equal' => 'Tanggal selesai tidak boleh sebelum tanggal mulai.',
        ]);

        NataruEvent::create([
            'name' => $validated['name'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'description' => $validated['description'],
            'compare_event_id' => $validated['compare_event_id'], // Simpan pembanding
            'is_active' => true, 
        ]);

        return redirect()->route('staff.nataru-events.index')->with('success', 'Event berhasil dibuat.');
    }

    /**
     * Menampilkan detail event, data penerbangan, dan statistik perbandingan.
     */
    public function show(NataruEvent $nataruEvent)
    {
        // 1. Load Data Penerbangan Event Ini
        $nataruEvent->load(['flights' => function($query) {
            $query->orderBy('flight_date', 'desc')->orderBy('flight_time', 'desc');
        }, 'compareEvent']); 
        
        // 2. Hitung Statistik Saat Ini
        $currentStats = [
            'total_flights' => $nataruEvent->flights->count(),
            'total_pax' => $nataruEvent->flights->sum('pax_total'),
            'total_cargo' => $nataruEvent->flights->sum('cargo'),
            'avg_lf' => $nataruEvent->flights->avg('load_factor') ?? 0, // Hitung Rata-rata Load Factor
        ];

        // 3. Hitung Perbandingan (Jika ada event pembanding)
        $comparison = null;
        if ($nataruEvent->compare_event_id) {
            // Kita query langsung ke database untuk event pembanding agar lebih ringan (tidak load model objects)
            $compareQuery = $nataruEvent->compareEvent->flights();
            
            $compStats = [
                'flights' => $compareQuery->count(),
                'pax' => $compareQuery->sum('pax_total'),
                'cargo' => $compareQuery->sum('cargo'),
                'lf' => $compareQuery->avg('load_factor') ?? 0,
            ];

            // Hitung Selisih (Current - Compare)
            $comparison = [
                'flights' => $currentStats['total_flights'] - $compStats['flights'],
                'pax' => $currentStats['total_pax'] - $compStats['pax'],
                'cargo' => $currentStats['total_cargo'] - $compStats['cargo'],
                'lf' => $currentStats['avg_lf'] - $compStats['lf'],
            ];
        }

        // Kita kirim variable $summary (untuk kompatibilitas kode view sebelumnya jika ada) 
        // tapi disarankan pakai $currentStats di view baru
        return view('user_staff2.nataru.event.show', compact('nataruEvent', 'currentStats', 'comparison'));
    }

    public function edit(NataruEvent $nataruEvent)
    {
        // Ambil semua event KECUALI dirinya sendiri (untuk menghindari circular reference)
        $events = NataruEvent::where('id', '!=', $nataruEvent->id)
                             ->orderBy('start_date', 'desc')
                             ->get();
                             
        return view('user_staff2.nataru.event.edit', compact('nataruEvent', 'events'));
    }

    public function update(Request $request, NataruEvent $nataruEvent)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'compare_event_id' => 'nullable|exists:nataru_events,id|not_in:'.$nataruEvent->id, 
        ]);

        $nataruEvent->update($validated);

        return redirect()->route('staff.nataru-events.index')->with('success', 'Event berhasil diperbarui.');
    }

    public function destroy(NataruEvent $nataruEvent)
    {
        try {
            $nataruEvent->delete();
            return redirect()->route('staff.nataru-events.index')->with('success', 'Event berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus event. Pastikan tidak ada data penerbangan terkait.');
        }
    }

    public function exportExcel(NataruEvent $nataruEvent)
    {
        $fileName = 'Data_Posko_' . Str::slug($nataruEvent->name) . '_' . date('Y-m-d_His') . '.xlsx';
        
        return Excel::download(new NataruFlightExport($nataruEvent->id), $fileName);
    }

    public function editFlight($id)
    {
        $flight = NataruFlight::with('nataruEvent')->findOrFail($id);
        return view('user_staff2.nataru.flight.edit', compact('flight'));
    }

    public function updateFlight(Request $request, $id)
    {
        $flight = NataruFlight::findOrFail($id);

        // Validasi sama dengan store public, tapi tanpa captcha/public token
        $validated = $request->validate([
            'flight_date' => 'required|date',
            'flight_time' => 'required',
            'airline' => 'required|string|max:100', // Ini input hidden
            'flight_number' => 'required|string|max:20',
            'status_flight' => 'required|in:Berjadwal,Perintis,Tidak Berjadwal',
            'route' => 'required|string|max:100',
            'direction' => 'required|in:arrival,departure',
            'aircraft_type' => 'nullable|string|max:50',
            'aircraft_registration' => 'nullable|string|max:20',
            
            'pax_adult' => 'required|integer|min:0',
            'pax_child' => 'required|integer|min:0',
            'pax_infant' => 'required|integer|min:0',
            'cargo' => 'required|integer|min:0',
            'baggage' => 'required|integer|min:0',
            
            // Harga tiket sudah disanitasi JS, tapi validasi tetap numeric
            'ticket_price_high' => 'nullable|numeric|min:0',
            'ticket_price_low' => 'nullable|numeric|min:0',

            'officer_name' => 'required|string|max:255',
            'remarks' => 'nullable|string',
            'seat_capacity' => 'nullable|integer|min:1', 
        ],[
            // --- PESAN VALIDASI BAHASA INDONESIA ---
            
            // Waktu & Status
            'flight_date.required' => 'Tanggal penerbangan wajib diisi.',
            'flight_date.date' => 'Format tanggal penerbangan tidak valid.',
            'flight_time.required' => 'Jam penerbangan wajib diisi.',
            
            // Identitas Penerbangan
            'airline.required' => 'Maskapai wajib dipilih.',
            'airline.max' => 'Nama maskapai maksimal 100 karakter.',
            'flight_number.required' => 'Nomor penerbangan wajib diisi.',
            'flight_number.max' => 'Nomor penerbangan maksimal 20 karakter.',
            'status_flight.required' => 'Status penerbangan wajib dipilih.',
            'status_flight.in' => 'Pilihan status penerbangan tidak valid.',
            'route.required' => 'Rute penerbangan wajib diisi.',
            'route.max' => 'Rute penerbangan maksimal 100 karakter.',
            'direction.required' => 'Arah penerbangan (Kedatangan/Keberangkatan) wajib dipilih.',
            'direction.in' => 'Pilihan arah penerbangan tidak valid.',
            'aircraft_type.max' => 'Tipe pesawat maksimal 50 karakter.',
            'aircraft_registration.max' => 'Registrasi pesawat maksimal 20 karakter.',

            // Data Muatan (Pax)
            'pax_adult.required' => 'Jumlah penumpang dewasa wajib diisi.',
            'pax_adult.integer' => 'Jumlah penumpang dewasa harus berupa angka bulat.',
            'pax_adult.min' => 'Jumlah penumpang dewasa tidak boleh kurang dari 0.',
            
            'pax_child.required' => 'Jumlah penumpang anak wajib diisi.',
            'pax_child.integer' => 'Jumlah penumpang anak harus berupa angka bulat.',
            'pax_child.min' => 'Jumlah penumpang anak tidak boleh kurang dari 0.',
            
            'pax_infant.required' => 'Jumlah penumpang bayi wajib diisi.',
            'pax_infant.integer' => 'Jumlah penumpang bayi harus berupa angka bulat.',
            'pax_infant.min' => 'Jumlah penumpang bayi tidak boleh kurang dari 0.',

            // Cargo & Bagasi
            'cargo.required' => 'Jumlah kargo wajib diisi (isi 0 jika tidak ada).',
            'cargo.integer' => 'Jumlah kargo harus berupa angka bulat.',
            'cargo.min' => 'Jumlah kargo tidak boleh kurang dari 0.',
            
            'baggage.required' => 'Jumlah bagasi wajib diisi (isi 0 jika tidak ada).',
            'baggage.integer' => 'Jumlah bagasi harus berupa angka bulat.',
            'baggage.min' => 'Jumlah bagasi tidak boleh kurang dari 0.',

            // Harga Tiket
            'ticket_price_high.numeric' => 'Harga tiket tertinggi harus berupa angka.',
            'ticket_price_high.min' => 'Harga tiket tertinggi tidak boleh kurang dari 0.',
            
            'ticket_price_low.numeric' => 'Harga tiket terendah harus berupa angka.',
            'ticket_price_low.min' => 'Harga tiket terendah tidak boleh kurang dari 0.',

            // Petugas & Lainnya
            'officer_name.required' => 'Nama petugas wajib diisi.',
            'officer_name.max' => 'Nama petugas maksimal 255 karakter.',
            'seat_capacity.integer' => 'Kapasitas kursi harus berupa angka bulat.',
            'seat_capacity.min' => 'Kapasitas kursi minimal 1.',
        ]);

        // Hitung Ulang Total Pax & LF di Server
        $totalPax = $validated['pax_adult'] + $validated['pax_child'] + $validated['pax_infant'];
        
        $loadFactor = 0;
        if ($request->filled('seat_capacity') && $request->seat_capacity > 0) {
            $occupiedSeats = $validated['pax_adult'] + $validated['pax_child'];
            $loadFactor = ($occupiedSeats / $request->seat_capacity) * 100;
        } elseif ($flight->load_factor > 0 && !$request->filled('seat_capacity')) {
            // Opsi: Jika seat capacity tidak diisi user, bisa pertahankan LF lama atau set 0
            // Disini kita hitung baru saja berdasarkan input (jadi 0 kalau kosong)
            $loadFactor = 0; 
        }

        $flight->update([
            'flight_date' => $validated['flight_date'],
            'flight_time' => $validated['flight_time'],
            'airline' => $validated['airline'],
            'flight_number' => $validated['flight_number'],
            'status_flight' => $validated['status_flight'],
            'route' => $validated['route'],
            'direction' => $validated['direction'],
            'aircraft_type' => $validated['aircraft_type'],
            'aircraft_registration' => $validated['aircraft_registration'],
            
            'pax_adult' => $validated['pax_adult'],
            'pax_child' => $validated['pax_child'],
            'pax_infant' => $validated['pax_infant'],
            'pax_total' => $totalPax,
            
            'cargo' => $validated['cargo'],
            'baggage' => $validated['baggage'],
            'load_factor' => $loadFactor,

            'ticket_price_high' => $validated['ticket_price_high'],
            'ticket_price_low' => $validated['ticket_price_low'],

            'officer_name' => $validated['officer_name'],
            'remarks' => $validated['remarks'],
        ]);

        return redirect()->route('staff.nataru-events.show', $flight->nataru_event_id)
                         ->with('success', 'Data penerbangan '.$validated['flight_number'].' berhasil diperbarui.');
    }
    public function exportPdf($id)
    {
        $nataruEvent = NataruEvent::with(['flights', 'compareEvent.flights'])->findOrFail($id);
        
        // --- 1. SETUP DATA UTAMA & PERBANDINGAN ---
        $currentStats = [
            'flights' => $nataruEvent->flights->count(),
            'pax' => $nataruEvent->flights->sum('pax_total'),
            'cargo' => $nataruEvent->flights->sum('cargo'),
            'lf' => $nataruEvent->flights->avg('load_factor') ?? 0,
        ];

        $compStats = ['flights' => 0, 'pax' => 0, 'cargo' => 0, 'lf' => 0];
        
        if ($nataruEvent->compareEvent) {
            $compQuery = $nataruEvent->compareEvent->flights;
            $compStats = [
                'flights' => $compQuery->count(),
                'pax' => $compQuery->sum('pax_total'),
                'cargo' => $compQuery->sum('cargo'),
                'lf' => $compQuery->avg('load_factor') ?? 0,
            ];
        }

        $comparison = [
            'flights' => $currentStats['flights'] - $compStats['flights'],
            'pax' => $currentStats['pax'] - $compStats['pax'],
            'cargo' => $currentStats['cargo'] - $compStats['cargo'],
            'lf' => $currentStats['lf'] - $compStats['lf'],
        ];

        // --- 2. SETUP DATA HARIAN (TABLE & CHARTS) ---
        // Logika alignment H-x (Copy dari logic getTvChartData)
        $start1 = Carbon::parse($nataruEvent->start_date)->startOfDay();
        $end1   = Carbon::parse($nataruEvent->end_date)->startOfDay();
        $offset1 = ceil($start1->diffInDays($end1) / 2); 
        $refDate1 = $start1->copy()->addDays($offset1);

        // Tentukan range H- yang akan ditampilkan
        $startIndex = $start1->diffInDays($refDate1) * -1;
        $endIndex   = $end1->diffInDays($refDate1);
        if ($end1->lessThan($refDate1)) $endIndex = $endIndex * -1;

        // Siapkan Array untuk Looping
        $dailyReport = [];
        
        // Array Data untuk Grafik
        $chartLabels = [];

       // Pesawat
        $flightsArr1 = []; $flightsDep1 = [];
        $flightsArr2 = []; $flightsDep2 = [];
        
        // Penumpang
        $paxArr1 = []; $paxDep1 = [];
        $paxArr2 = []; $paxDep2 = [];
        
        // Kargo
        $cargoArr1 = []; $cargoDep1 = [];
        $cargoArr2 = []; $cargoDep2 = [];

        // Pre-fetch flights grouping by date untuk performa
        $flights1Group = $nataruEvent->flights->groupBy(function($val) {
            return Carbon::parse($val->flight_date)->format('Y-m-d');
        });
        
        $flights2Group = collect([]);
        $refDate2 = null;
        
        if($nataruEvent->compareEvent) {
            $flights2Group = $nataruEvent->compareEvent->flights->groupBy(function($val) {
                return Carbon::parse($val->flight_date)->format('Y-m-d');
            });
            
            $start2 = Carbon::parse($nataruEvent->compareEvent->start_date)->startOfDay();
            $end2   = Carbon::parse($nataruEvent->compareEvent->end_date)->startOfDay();
            $offset2 = ceil($start2->diffInDays($end2) / 2);
            $refDate2 = $start2->copy()->addDays($offset2);
        }

        for ($i = $startIndex; $i <= $endIndex; $i++) {
            // Label H
            if ($i == 0) $hLabel = "Hari H";
            elseif ($i < 0) $hLabel = "H" . $i; 
            else $hLabel = "H+" . $i;

            $chartLabels[] = $hLabel; // Label Sumbu X

            // Tanggal Real
            $date1 = $refDate1->copy()->addDays($i);
            $dateStr1 = $date1->format('Y-m-d');
            
            $date2 = $refDate2 ? $refDate2->copy()->addDays($i) : null;
            $dateStr2 = $date2 ? $date2->format('Y-m-d') : null;

            // Ambil Data Harian
            $dayFlight1 = $flights1Group->get($dateStr1);
            $dayFlight2 = $dateStr2 ? $flights2Group->get($dateStr2) : null;

            // --- PEMECAHAN DATA EVENT 1 (TAHUN INI) ---
            if ($dayFlight1) {
                $flightsArr1[] = $dayFlight1->where('direction', 'arrival')->count();
                $flightsDep1[] = $dayFlight1->where('direction', 'departure')->count();
                
                $paxArr1[] = $dayFlight1->where('direction', 'arrival')->sum('pax_total');
                $paxDep1[] = $dayFlight1->where('direction', 'departure')->sum('pax_total');
                
                $cargoArr1[] = $dayFlight1->where('direction', 'arrival')->sum('cargo');
                $cargoDep1[] = $dayFlight1->where('direction', 'departure')->sum('cargo');
            } else {
                // Isi 0 jika tidak ada data
                $flightsArr1[] = 0; $flightsDep1[] = 0;
                $paxArr1[] = 0; $paxDep1[] = 0;
                $cargoArr1[] = 0; $cargoDep1[] = 0;
            }

            // --- PEMECAHAN DATA EVENT 2 (TAHUN LALU) ---
            if ($dayFlight2) {
                $flightsArr2[] = $dayFlight2->where('direction', 'arrival')->count();
                $flightsDep2[] = $dayFlight2->where('direction', 'departure')->count();
                
                $paxArr2[] = $dayFlight2->where('direction', 'arrival')->sum('pax_total');
                $paxDep2[] = $dayFlight2->where('direction', 'departure')->sum('pax_total');
                
                $cargoArr2[] = $dayFlight2->where('direction', 'arrival')->sum('cargo');
                $cargoDep2[] = $dayFlight2->where('direction', 'departure')->sum('cargo');
            } else {
                $flightsArr2[] = 0; $flightsDep2[] = 0;
                $paxArr2[] = 0; $paxDep2[] = 0;
                $cargoArr2[] = 0; $cargoDep2[] = 0;
            }

            // Hitung Stats Harian
            $stats1 = [
                'flights' => $dayFlight1 ? $dayFlight1->count() : 0,
                'pax' => $dayFlight1 ? $dayFlight1->sum('pax_total') : 0,
                'cargo' => $dayFlight1 ? $dayFlight1->sum('cargo') : 0,
            ];
            $stats2 = [
                'flights' => $dayFlight2 ? $dayFlight2->count() : 0,
                'pax' => $dayFlight2 ? $dayFlight2->sum('pax_total') : 0,
                'cargo' => $dayFlight2 ? $dayFlight2->sum('cargo') : 0,
            ];

            // Masukkan ke Array Table
            $dailyReport[] = [
                'label' => $hLabel,
                'date1' => $date1->translatedFormat('d M Y'),
                'date2' => $date2 ? $date2->translatedFormat('d M Y') : '-',
                'stats1' => $stats1,
                'stats2' => $stats2
            ];

            // Masukkan ke Array Chart
            $dataPax1[] = $stats1['pax']; $dataPax2[] = $stats2['pax'];
            $dataFlight1[] = $stats1['flights']; $dataFlight2[] = $stats2['flights'];
            $dataCargo1[] = $stats1['cargo']; $dataCargo2[] = $stats2['cargo'];
        }

        // --- 3. GENERATE QUICKCHART URLS ---

        // >>> TAMBAHKAN KODE DEBUG DISINI <<<
        // dd([
        //     'Labels (Sumbu X)' => $chartLabels,
        //     'Data Arr Tahun Ini (Total Data: ' . count($flightsArr1) . ')' => $flightsArr1,
        //     'Data Dep Tahun Ini' => $flightsDep1,
        // ]);

        $chartImages = [
            'pax' => $this->generateComparisonChart(
                'Penumpang', $chartLabels, 
                $paxArr1, $paxDep1, $paxArr2, $paxDep2, 
                $nataruEvent->name
            ),
            'flight' => $this->generateComparisonChart(
                'Pesawat', $chartLabels, 
                $flightsArr1, $flightsDep1, $flightsArr2, $flightsDep2, 
                $nataruEvent->name
            ),
            'cargo' => $this->generateComparisonChart(
                'Kargo', $chartLabels, 
                $cargoArr1, $cargoDep1, $cargoArr2, $cargoDep2, 
                $nataruEvent->name
            ),
        ];
        // --- TAMBAHAN UNTUK POIN 4: AMBIL SELURUH DATA PENERBANGAN ---
        $allFlights = $nataruEvent->flights()
                        ->orderBy('flight_date', 'asc') // Urutkan tanggal
                        ->orderBy('flight_time', 'asc') // Urutkan jam
                        ->get();

        // --- 4. RENDER PDF ---
        $pdf = Pdf::loadView('user_staff2.nataru.report.pdf_export', compact(
            'nataruEvent', 'currentStats', 'compStats', 'comparison', 'dailyReport', 'chartImages','allFlights'
        ));

        // Set kertas A4 Landscape agar grafik & tabel muat lega
        $pdf->setPaper('a4', 'landscape');

        // --- PERBAIKAN UTAMA: IZINKAN GAMBAR REMOTE ---
        $pdf->setOption([
            'isRemoteEnabled' => true, 
            'isHtml5ParserEnabled' => true
        ]);
        // ----------------------------------------------

        $cleanName = \Illuminate\Support\Str::slug($nataruEvent->name);

        return $pdf->download('Laporan_Posko_'.$cleanName.'.pdf');
    }

    /**
     * Helper Chart FINAL: POST Only (Tanpa Fallback URL)
     * Memaksa penggunaan Base64. Jika gagal, tampilkan pesan error di gambar.
     */
    private function generateComparisonChart($title, $labels, $arr1, $dep1, $arr2, $dep2, $eventName)
    {
        $config = [
            'type' => 'bar', 
            'data' => [
                'labels' => $labels,
                'datasets' => [
                    [
                        'type' => 'bar', 'label' => 'Tahun ini (Arr)',
                        'backgroundColor' => '#0d6efd', 'borderColor' => '#0d6efd', 'borderWidth' => 1,
                        'data' => $arr1, 'xAxisID' => 'sumbu-x-utama',
                        'categoryPercentage' => 0.6, 'barPercentage' => 0.9,
                    ],
                    [
                        'type' => 'bar', 'label' => 'Tahun ini (Dep)',
                        'backgroundColor' => '#0dcaf0', 'borderColor' => '#0dcaf0', 'borderWidth' => 1,
                        'data' => $dep1, 'xAxisID' => 'sumbu-x-utama',
                        'categoryPercentage' => 0.6, 'barPercentage' => 0.9,
                    ],
                    [
                        'type' => 'bar', 'label' => 'Tahun Lalu (Arr)',
                        'backgroundColor' => '#dc3545', 'borderColor' => '#dc3545', 'borderWidth' => 1,
                        'data' => $arr2, 'xAxisID' => 'sumbu-x-utama',
                    ],
                    [
                        'type' => 'bar', 'label' => 'Tahun Lalu (Dep)',
                        'backgroundColor' => '#fd7e14', 'borderColor' => '#fd7e14', 'borderWidth' => 1,
                        'data' => $dep2, 'xAxisID' => 'sumbu-x-utama',
                    ]
                ]
            ],
            'options' => [
                'title' => [ 'display' => true, 'text' => 'Grafik ' . $title, 'fontSize' => 14 ],
                'legend' => [ 'position' => 'bottom', 'labels' => ['fontSize' => 9, 'boxWidth' => 10] ],
                'scales' => [
                    'xAxes' => [[
                        'id' => 'sumbu-x-utama', 'offset' => true, 'stacked' => false,
                        'gridLines' => [ 'display' => false, 'drawBorder' => true, 'offsetGridLines' => true ],
                        'ticks' => [ 'autoSkip' => true, 'maxRotation' => 0, 'fontSize' => 9 ]
                    ]],
                    'yAxes' => [[ 'ticks' => ['beginAtZero' => true, 'fontSize' => 9] ]]
                ]
            ]
        ];

        try {
            // KIRIM REQUEST POST
            $response = Http::withOptions([
                'verify' => false, // Bypass SSL (Wajib di Localhost/XAMPP)
                'timeout' => 30,   // Perpanjang timeout jadi 30 detik
            ])->post('https://quickchart.io/chart', [
                'chart' => $config,
                'width' => 600,
                'height' => 300,
                'backgroundColor' => 'white',
            ]);

            // CEK RESPONSE
            if ($response->successful()) {
                $imageData = $response->body();
                
                // Validasi: Jika response diawali '{', itu teks JSON error, bukan gambar
                if (substr(trim($imageData), 0, 1) === '{') {
                    // Coba ambil pesan error dari JSON
                    $errMsg = 'QuickChart_JSON_Error';
                    return 'https://placehold.co/600x300?text=' . $errMsg;
                }

                return 'data:image/png;base64,' . base64_encode($imageData);
            } else {
                // Jika status bukan 200 OK (misal 400 atau 500)
                $status = $response->status();
                \Illuminate\Support\Facades\Log::error('QuickChart Error: ' . $response->body());
                return 'https://placehold.co/600x300?text=HTTP+Error+' . $status;
            }

        } catch (\Exception $e) {
            // Jika koneksi gagal total (Timeout / DNS)
            $msg = substr($e->getMessage(), 0, 20); // Ambil potongan pesan error
            \Illuminate\Support\Facades\Log::error('QuickChart Exception: ' . $e->getMessage());
            return 'https://placehold.co/600x300?text=Koneksi+Gagal:+' . urlencode($msg);
        }
    }
}