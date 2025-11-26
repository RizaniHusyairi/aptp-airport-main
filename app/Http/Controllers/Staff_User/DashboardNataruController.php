<?php

namespace App\Http\Controllers\Staff_User;

use App\Http\Controllers\Controller;
use App\Models\NataruEvent;
use App\Models\NataruFlight;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardNataruController extends Controller
{
    public function index()
    {
        // Ambil semua event untuk dropdown filter
        $events = NataruEvent::orderBy('start_date', 'desc')->get();
        
        return view('user_staff2.nataru.dashboard.index', compact('events'));
    }

    /**
     * API untuk mengambil data perbandingan 2 event.
     */
    public function getComparisonData(Request $request)
    {
        $eventId1 = $request->event_id_1; // Event Utama (misal 2024)
        $eventId2 = $request->event_id_2; // Event Pembanding (misal 2023)

        if (!$eventId1 || !$eventId2) {
            return response()->json(['error' => 'Pilih dua event untuk dibandingkan'], 400);
        }

        $event1 = NataruEvent::find($eventId1);
        $event2 = NataruEvent::find($eventId2);

        // Helper function untuk mengambil data H-x s/d H+x
        $data1 = $this->getEventDailyStats($event1);
        $data2 = $this->getEventDailyStats($event2);

        // Kita gabungkan datanya berdasarkan index "H-x"
        // List standar H-10 sampai H+10 (atau dinamis sesuai range event)
        $labels = $this->generateHLabels(); 

        return response()->json([
            'event1_name' => $event1->name,
            'event2_name' => $event2->name,
            'labels' => $labels,
            'dataset1' => $this->mapDataToLabels($data1, $labels),
            'dataset2' => $this->mapDataToLabels($data2, $labels),
        ]);
    }

    /**
     * Mengambil statistik harian event dan mengelompokkannya ke H-index
     */
    private function getEventDailyStats($event)
    {
        if (!$event) return [];

        // Ambil data penerbangan, group by tanggal
        $stats = NataruFlight::where('nataru_event_id', $event->id)
            ->select(
                'flight_date',
                DB::raw('SUM(pax_total) as total_pax'),
                DB::raw('SUM(cargo) as total_cargo'),
                DB::raw('COUNT(*) as total_flights')
            )
            ->groupBy('flight_date')
            ->get();


        
        $formattedData = [];
        $targetDate = Carbon::parse($event->start_date); // Hari pertama posko

       
        $referenceDate = $this->determineReferenceDate($event); 

        foreach ($stats as $stat) {
            $flightDate = Carbon::parse($stat->flight_date);
            $diff = $flightDate->diffInDays($referenceDate, false); // false agar dapat nilai negatif
            
            // Diff negatif = Sebelum hari H (H-x)
            // Diff positif = Setelah hari H (H+x)
            // Diff 0 = Hari H
            
            // Format label: H-1, H+1, H
            $label = $this->formatHLabel(-$diff); // diffInDays return positif jika date < ref, jadi di-negatifkan logic-nya sesuaikan
            
            $formattedData[$label] = [
                'pax' => $stat->total_pax,
                'cargo' => $stat->total_cargo,
                'flights' => $stat->total_flights
            ];
        }

        return $formattedData;
    }

    private function determineReferenceDate($event)
    {
        // Logika sederhana deteksi Nataru
        // Jika start_date di bulan Desember, asumsikan ini Nataru -> Ref = 25 Des tahun start_date
        if ($event->start_date->month == 12) {
            return Carbon::create($event->start_date->year, 12, 25);
        }
        
        // Jika Lebaran (Idul Fitri berubah tiap tahun), idealnya ada input 'main_date' di tabel Event.
        // Untuk sekarang, fallback ke start_date + 7 hari (asumsi posko mulai H-7)
        return $event->start_date->copy()->addDays(7);
    }

    private function formatHLabel($diff)
    {
        // Note: diffInDays(ref, false):
        // if date < ref (sebelum): result positif (misal 10) -> H-10
        // if date > ref (sesudah): result negatif (misal -5) -> H+5
        
        // Koreksi logika diffInDays Carbon:
        // $date->diffInDays($ref, false) -> jika date=15, ref=25 -> hasil = 10 (positif)
        // Jadi logikanya:
        
        if ($diff > 0) return "H-{$diff}";
        if ($diff < 0) return "H+" . abs($diff);
        return "H";
    }

    private function generateHLabels()
    {
        // Generate array label standar H-10 s/d H+10
        $labels = [];
        for ($i = 10; $i >= 1; $i--) $labels[] = "H-{$i}";
        $labels[] = "H";
        for ($i = 1; $i <= 10; $i++) $labels[] = "H+{$i}";
        return $labels;
    }

    private function mapDataToLabels($data, $labels)
    {
        $result = [
            'pax' => [],
            'cargo' => [],
            'flights' => []
        ];

        foreach ($labels as $label) {
            $item = $data[$label] ?? ['pax' => 0, 'cargo' => 0, 'flights' => 0];
            $result['pax'][] = $item['pax'];
            $result['cargo'][] = $item['cargo'];
            $result['flights'][] = $item['flights'];
        }
        
        return $result;
    }
}