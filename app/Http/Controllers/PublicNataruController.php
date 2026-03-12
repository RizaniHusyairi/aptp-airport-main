<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\NataruEvent;
use App\Models\NataruFlight;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PublicNataruController extends Controller
{
    /**
     * Menampilkan form input data untuk petugas lapangan.
     * Diakses via link: /posko/input/{token}
     */
    public function showForm($token)
    {
        $event = NataruEvent::where('public_token', $token)->first();

        if (!$event) {
            abort(404, 'Event Posko tidak ditemukan.');
        }

        if (!$event->is_active) {
            return view('public.nataru.closed', compact('event'));
        }

        return view('public.nataru.form', compact('event'));
    }

    /**
     * Menyimpan data penerbangan dari form publik.
     */
    public function store(Request $request, $token)
    {
        $event = NataruEvent::where('public_token', $token)->where('is_active', true)->firstOrFail();

        $validated = $request->validate([
            'flight_date' => 'required|date',
            'flight_time' => 'required',
            'airline' => 'required|string|max:100',
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
            
            'ticket_price_high' => 'nullable|numeric|min:0',
            'ticket_price_low' => 'nullable|numeric|min:0',

            'officer_name' => 'required|string|max:255',
            'remarks' => 'nullable|string',
            'seat_capacity' => 'nullable|integer|min:1', 
        ], [
            'officer_name.required' => 'Nama petugas wajib diisi.',
            'flight_date.required' => 'Tanggal penerbangan wajib diisi.',
        ]);

        // Hitung Total Pax
        $totalPax = $validated['pax_adult'] + $validated['pax_child'] + $validated['pax_infant'];
        
        // Hitung Load Factor
        $loadFactor = 0;
        if ($request->filled('seat_capacity') && $request->seat_capacity > 0) {
            $occupiedSeats = $validated['pax_adult'] + $validated['pax_child'];
            $loadFactor = ($occupiedSeats / $request->seat_capacity) * 100;
        }

        DB::beginTransaction();
        try {
            NataruFlight::create([
                'nataru_event_id' => $event->id,
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
                'user_id' => null,
            ]);

            DB::commit();
            return back()->with('success', 'Data penerbangan ' . $validated['flight_number'] . ' berhasil disimpan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan Dashboard TV Publik.
     * Diakses via: /posko/tv/{token}
     */
    public function tvDashboard($token)
    {
       $nataruEvent = NataruEvent::where('public_token', $token)->firstOrFail();
        
        // Setup Tanggal Hari Ini & Helper Carbon
        $todayDate = Carbon::today()->format('Y-m-d');
        $today = Carbon::today();

        // 1. Data Penerbangan Hari Ini (Untuk Tabel & Statistik)
        $todaysFlights = $nataruEvent->flights()
                                     ->whereDate('flight_date', $todayDate)
                                     ->orderBy('flight_time', 'desc')
                                     ->get();

        // 2. Hitung Statistik Arrival/Departure HARI INI
        // Kita bisa hitung manual dari collection $todaysFlights agar hemat query
        $dailyStats = [
            'flights_arr' => $todaysFlights->where('direction', 'arrival')->count(),
            'flights_dep' => $todaysFlights->where('direction', 'departure')->count(),
            
            'pax_arr'     => $todaysFlights->where('direction', 'arrival')->sum('pax_total'),
            'pax_dep'     => $todaysFlights->where('direction', 'departure')->sum('pax_total'),
            
            'cargo_arr'   => $todaysFlights->where('direction', 'arrival')->sum('cargo'),
            'cargo_dep'   => $todaysFlights->where('direction', 'departure')->sum('cargo'),
            
            // Comparison placeholders
            'comp_flights_arr' => 0, 'comp_flights_dep' => 0,
            'comp_pax_arr' => 0,     'comp_pax_dep' => 0,
            'comp_cargo_arr' => 0,   'comp_cargo_dep' => 0,

            'label_h' => 'Hari H',
        ];

        // 3. Logika Perbandingan (H-x yang sama)
        if ($nataruEvent->compare_event_id) {
            $event2 = $nataruEvent->compareEvent;
        
        // --- MENGGUNAKAN PEAK DATE DINAMIS ---
        if ($nataruEvent->peak_date) {
            $refDate1 = Carbon::parse($nataruEvent->peak_date)->startOfDay();
        } else {
            // Fallback ke titik tengah event jika tidak di-set
            $start1 = Carbon::parse($nataruEvent->start_date)->startOfDay();
            $end1   = Carbon::parse($nataruEvent->end_date)->startOfDay();
            $refDate1 = $start1->copy()->addDays(ceil($start1->diffInDays($end1) / 2)); 
        }

        $refDate2 = null;
        if ($event2) {
            if ($event2->peak_date) {
                $refDate2 = Carbon::parse($event2->peak_date)->startOfDay();
            } else {
                // Fallback ke titik tengah event pembanding
                $start2 = Carbon::parse($event2->start_date)->startOfDay();
                $end2   = Carbon::parse($event2->end_date)->startOfDay();
                $refDate2 = $start2->copy()->addDays(ceil($start2->diffInDays($end2) / 2)); 
            }
        }

        // Cari H-berapa hari ini?
        $diffDays = $today->diffInDays($refDate1, false) * -1; // -1 karena jika today sebelum ref, kita ingin H- (negatif)
        $hIndex = $diffDays;

            // Tentukan Tanggal Pembanding yang "H-indeks"-nya sama
            $compDate = $refDate2->copy()->addDays($hIndex);

            // Query Data Pembanding
            $compFlights = $event2->flights()->whereDate('flight_date', $compDate->format('Y-m-d'))->get();
            
            // Isi Data Comparison
            $dailyStats['comp_flights_arr'] = $compFlights->where('direction', 'arrival')->count();
            $dailyStats['comp_flights_dep'] = $compFlights->where('direction', 'departure')->count();
            
            $dailyStats['comp_pax_arr'] = $compFlights->where('direction', 'arrival')->sum('pax_total');
            $dailyStats['comp_pax_dep'] = $compFlights->where('direction', 'departure')->sum('pax_total');
            
            $dailyStats['comp_cargo_arr'] = $compFlights->where('direction', 'arrival')->sum('cargo');
            $dailyStats['comp_cargo_dep'] = $compFlights->where('direction', 'departure')->sum('cargo');
            
            // Set Label H untuk ditampilkan
            if ($hIndex == 0) $dailyStats['label_h'] = "Hari H";
            elseif ($hIndex < 0) $dailyStats['label_h'] = "H" . $hIndex;
            else $dailyStats['label_h'] = "H+" . $hIndex;
        }

        // ... (Kode Current Stats & Comparison Total Event tetap sama seperti sebelumnya) ...
        // ... Pastikan variabel 'max_flight_data' dll yang tadi sudah ditambahkan tetap ada ...
        // Ambil OBJECT penerbangan dengan harga tertinggi hari ini
        $maxFlight = $nataruEvent->flights()->whereDate('flight_date', $todayDate)->orderBy('ticket_price_high', 'desc')->first();
        $minFlight = $nataruEvent->flights()->whereDate('flight_date', $todayDate)->where('ticket_price_low', '>', 0)->orderBy('ticket_price_low', 'asc')->first();

        $currentStats = [
            'total_flights' => $nataruEvent->flights()->count(),
            'total_pax' => $nataruEvent->flights()->sum('pax_total'),
            'total_cargo' => $nataruEvent->flights()->sum('cargo'),
            'avg_lf' => $nataruEvent->flights()->avg('load_factor') ?? 0,
            'max_flight_data' => $maxFlight, 
            'min_flight_data' => $minFlight,
        ];
        
        // Hitung Perbandingan Total Event (Kode Lama)
        $comparison = null;
        if ($nataruEvent->compare_event_id) {
             // ... (Copy logika comparison total event yang lama) ...
             // Agar tidak kepanjangan, saya asumsikan kode ini masih ada sesuai instruksi sebelumnya
             $compareQuery = $nataruEvent->compareEvent->flights();
             $compStats = [ 'flights' => $compareQuery->count(), 'pax' => $compareQuery->sum('pax_total'), 'cargo' => $compareQuery->sum('cargo'), 'lf' => $compareQuery->avg('load_factor') ?? 0 ];
             $comparison = [ 'flights' => $currentStats['total_flights'] - $compStats['flights'], 'pax' => $currentStats['total_pax'] - $compStats['pax'], 'cargo' => $currentStats['total_cargo'] - $compStats['cargo'], 'lf' => $currentStats['avg_lf'] - $compStats['lf'] ];
        }

        // Jangan lupa kirim $dailyStats ke view
        return view('public.nataru.tv_dashboard', compact('nataruEvent', 'currentStats', 'comparison','todaysFlights', 'dailyStats'));
    }

    /**
     * API Endpoint untuk data grafik di TV (Real-time & Publik).
     * H-0 Dinamis berdasarkan Mean (Rata-rata/Tengah) dari durasi event.
     */
    public function getTvChartData($token)
    {
        // 1. Cari Event Utama
        $event1 = NataruEvent::where('public_token', $token)->firstOrFail();

        // 2. Validasi Event Pembanding
        if (!$event1->compare_event_id) {
            return response()->json([
                'error' => 'No comparison event selected.',
                'status' => 'error'
            ], 404);
        }

        $event2 = $event1->compareEvent;

        // 3. Hitung H-0 (Ref Date) Dinamis untuk Event 1
        $start1 = Carbon::parse($event1->start_date)->startOfDay();
        $end1   = Carbon::parse($event1->end_date)->startOfDay();
        
        if ($event1->peak_date) {
            $refDate1 = Carbon::parse($event1->peak_date)->startOfDay();
        } else {
            $refDate1 = $start1->copy()->addDays(ceil($start1->diffInDays($end1) / 2)); 
        }

        // 4. Hitung H-0 (Ref Date) Dinamis untuk Event 2
        if ($event2->peak_date) {
            $refDate2 = Carbon::parse($event2->peak_date)->startOfDay();
        } else {
            $start2 = Carbon::parse($event2->start_date)->startOfDay();
            $end2   = Carbon::parse($event2->end_date)->startOfDay();
            $refDate2 = $start2->copy()->addDays(ceil($start2->diffInDays($end2) / 2)); 
        }

        // 5. Tentukan Range Loop (Start Index s/d End Index)
        $startIndex = $start1->diffInDays($refDate1) * -1; // Karena start pasti sebelum ref, kita kalikan -1
        $endIndex   = $end1->diffInDays($refDate1); 
        
        // Pastikan end index positif jika end date > ref date
        if ($end1->lessThan($refDate1)) {
            $endIndex = $endIndex * -1;
        }

        // 6. Generate Loop
        $labels = [];      
        $dates1 = [];     
        $dates2 = [];      
        
        for ($i = $startIndex; $i <= $endIndex; $i++) {
            // Label H
            if ($i == 0) {
                $label = "H";
            } elseif ($i < 0) {
                $label = "H" . $i; 
            } else {
                $label = "H+" . $i; 
            }
            $labels[] = $label;

            // Tanggal Riil untuk Tooltip
            $dates1[] = $refDate1->copy()->addDays($i)->translatedFormat('d M Y');
            $dates2[] = $refDate2->copy()->addDays($i)->translatedFormat('d M Y');
        }

        // 7. Ambil Data Statistik
        $data1 = $this->getEventDailyStats($event1, $refDate1);
        $data2 = $this->getEventDailyStats($event2, $refDate2);

        // 8. Return JSON
        return response()->json([
            'status' => 'success',
            'event1_name' => $event1->name,
            'event2_name' => $event2->name,
            // Kirim info range untuk ditampilkan di UI (misal: "H-9 s/d H+8")
            'range_label' => $labels[0] . ' s/d ' . end($labels), 
            'categories' => $labels,
            'dates_event1' => $dates1,
            'dates_event2' => $dates2,
            'dataset1' => $this->mapDataToLabels($data1, $labels),
            'dataset2' => $this->mapDataToLabels($data2, $labels),
        ]);
    }

    /**
     * Helper: Mengambil statistik harian event dengan referensi tanggal.
     * Dipisahkan berdasarkan Arrival dan Departure.
     */
    private function getEventDailyStats($event, $referenceDate)
    {
        $stats = NataruFlight::where('nataru_event_id', $event->id)
            ->select(
                'flight_date',
                // PAX
                DB::raw('SUM(CASE WHEN direction = "arrival" THEN pax_total ELSE 0 END) as pax_arrival'),
                DB::raw('SUM(CASE WHEN direction = "departure" THEN pax_total ELSE 0 END) as pax_departure'),
                // CARGO
                DB::raw('SUM(CASE WHEN direction = "arrival" THEN cargo ELSE 0 END) as cargo_arrival'),
                DB::raw('SUM(CASE WHEN direction = "departure" THEN cargo ELSE 0 END) as cargo_departure'),
                // FLIGHTS (Count Rows)
                DB::raw('COUNT(CASE WHEN direction = "arrival" THEN 1 END) as flights_arrival'),
                DB::raw('COUNT(CASE WHEN direction = "departure" THEN 1 END) as flights_departure')
            )
            ->groupBy('flight_date')
            ->get();

        $formattedData = [];

        foreach ($stats as $stat) {
            $flightDate = Carbon::parse($stat->flight_date);
            
            // Hitung selisih hari dari referensi (25 Des)
            $diff = $flightDate->diffInDays($referenceDate, false) * -1;
            
            // Mapping ke Label H-x untuk array key
            if ($diff == 0) $label = "H";
            elseif ($diff < 0) $label = "H" . $diff; 
            else $label = "H+" . $diff; 
            
            $formattedData[$label] = [
                'pax_arrival' => $stat->pax_arrival,
                'pax_departure' => $stat->pax_departure,
                'cargo_arrival' => $stat->cargo_arrival,
                'cargo_departure' => $stat->cargo_departure,
                'flights_arrival' => $stat->flights_arrival,
                'flights_departure' => $stat->flights_departure,
            ];
        }

        return $formattedData;
    }

    /**
     * Helper: Memetakan data harian ke array berurutan sesuai Label (H-10 s/d H+10).
     */
    private function mapDataToLabels($data, $labels)
    {
        $result = [
            'pax_arrival' => [],
            'pax_departure' => [],
            'cargo_arrival' => [],
            'cargo_departure' => [],
            'flights_arrival' => [],
            'flights_departure' => [],
        ];

        foreach ($labels as $label) {
            $item = $data[$label] ?? [
                'pax_arrival' => 0, 'pax_departure' => 0,
                'cargo_arrival' => 0, 'cargo_departure' => 0,
                'flights_arrival' => 0, 'flights_departure' => 0
            ];

            $result['pax_arrival'][] = (int) $item['pax_arrival'];
            $result['pax_departure'][] = (int) $item['pax_departure'];
            $result['cargo_arrival'][] = (int) $item['cargo_arrival'];
            $result['cargo_departure'][] = (int) $item['cargo_departure'];
            $result['flights_arrival'][] = (int) $item['flights_arrival'];
            $result['flights_departure'][] = (int) $item['flights_departure'];
        }
        
        return $result;
    }
}