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

        // 1. Load Data Penerbangan Event Ini (HANYA HARI INI)
        // Kita gunakan relasi 'flights' tapi dengan kondisi tambahan
        $todayDate = Carbon::today()->format('Y-m-d');

        // Load data flights HARI INI untuk tabel (Limit bisa diperbesar atau dihapus jika pakai auto-scroll)
        // Kita load terpisah agar tidak mengganggu perhitungan statistik total event
        $todaysFlights = $nataruEvent->flights()
                                     ->whereDate('flight_date', $todayDate)
                                     ->orderBy('flight_time', 'desc')
                                     ->get(); // Ambil semua data hari ini untuk di-scroll


        // Load event pembanding untuk keperluan statistik
        $nataruEvent->load('compareEvent'); 
        
        // 2. Hitung Statistik Saat Ini
        $currentStats = [
            'total_flights' => $nataruEvent->flights()->count(),
            'total_pax' => $nataruEvent->flights()->sum('pax_total'),
            'total_cargo' => $nataruEvent->flights()->sum('cargo'),
            'avg_lf' => $nataruEvent->flights()->avg('load_factor') ?? 0,
            // Data Harga Tiket (Ambil max dari ticket_price_high dan min dari ticket_price_low)
            'max_ticket' => $nataruEvent->flights()->max('ticket_price_high') ?? 0,
            'min_ticket' => $nataruEvent->flights()->where('ticket_price_low', '>', 0)->min('ticket_price_low') ?? 0,
        ];

        // 3. Hitung Perbandingan
        $comparison = null;
        if ($nataruEvent->compare_event_id) {
            $compareQuery = $nataruEvent->compareEvent->flights();
            
            $compStats = [
                'flights' => $compareQuery->count(),
                'pax' => $compareQuery->sum('pax_total'),
                'cargo' => $compareQuery->sum('cargo'),
                'lf' => $compareQuery->avg('load_factor') ?? 0,
                // Perbandingan harga tiket (opsional, bisa di-skip jika tidak perlu diff badge untuk harga)
                'max_ticket' => $compareQuery->max('ticket_price_high') ?? 0,
                'min_ticket' => $compareQuery->where('ticket_price_low', '>', 0)->min('ticket_price_low') ?? 0,
            ];

            $comparison = [
                'flights' => $currentStats['total_flights'] - $compStats['flights'],
                'pax' => $currentStats['total_pax'] - $compStats['pax'],
                'cargo' => $currentStats['total_cargo'] - $compStats['cargo'],
                'lf' => $currentStats['avg_lf'] - $compStats['lf'],
                // Diff harga (Current - Past)
                'max_ticket' => $currentStats['max_ticket'] - $compStats['max_ticket'],
                'min_ticket' => $currentStats['min_ticket'] - $compStats['min_ticket'],
            ];
        }

        return view('public.nataru.tv_dashboard', compact('nataruEvent', 'currentStats', 'comparison','todaysFlights'));
    }

    /**
     * API Endpoint untuk data grafik di TV (Real-time & Publik).
     * Mengembalikan data H-10 s/d H+10 beserta tanggal riilnya.
     */
    public function getTvChartData($token)
    {
        // 1. Cari Event Utama berdasarkan Token
        $event1 = NataruEvent::where('public_token', $token)->firstOrFail();

        // 2. Validasi: Harus ada event pembanding
        if (!$event1->compare_event_id) {
            return response()->json([
                'error' => 'No comparison event selected for this event.',
                'status' => 'error'
            ], 404);
        }

        // 3. Ambil Event Pembanding
        $event2 = $event1->compareEvent;

        // 4. Tentukan Tanggal Referensi (H-0 / Hari Raya)
        $refDate1 = Carbon::create($event1->start_date->year, 12, 25);
        $refDate2 = Carbon::create($event2->start_date->year, 12, 25);

        // 5. Generate Data H-10 s/d H+10
        $labels = [];       // Array label sumbu X (H-10, H-9...)
        $dates1 = [];       // Array tanggal riil Event 1
        $dates2 = [];       // Array tanggal riil Event 2
        
        for ($i = -10; $i <= 10; $i++) {
            if ($i == 0) {
                $label = "H";
            } elseif ($i < 0) {
                $label = "H" . $i; 
            } else {
                $label = "H+" . $i; 
            }
            $labels[] = $label;

            $dates1[] = $refDate1->copy()->addDays($i)->translatedFormat('d M Y');
            $dates2[] = $refDate2->copy()->addDays($i)->translatedFormat('d M Y');
        }

        // 6. Ambil Data Statistik Harian dari Database
        $data1 = $this->getEventDailyStats($event1, $refDate1);
        $data2 = $this->getEventDailyStats($event2, $refDate2);

        // 7. Format Response JSON
        return response()->json([
            'status' => 'success',
            'event1_name' => $event1->name,
            'event2_name' => $event2->name,
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