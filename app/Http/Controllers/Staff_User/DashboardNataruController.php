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

    public function getComparisonData(Request $request)
    {
        $eventId1 = $request->event_id_1; // Event Utama
        $eventId2 = $request->event_id_2; // Event Pembanding

        if (!$eventId1 || !$eventId2) {
            return response()->json(['error' => 'Pilih dua event untuk dibandingkan'], 400);
        }

        $event1 = NataruEvent::find($eventId1);
        $event2 = NataruEvent::find($eventId2);

        // Reference Dates
        $refDate1 = $this->determineReferenceDate($event1);
        $refDate2 = $this->determineReferenceDate($event2);
        
        // Start Dates to determine range
        $start1 = Carbon::parse($event1->start_date)->startOfDay();
        $end1   = Carbon::parse($event1->end_date)->startOfDay();
        
        $startIndex = $start1->diffInDays($refDate1) * -1;
        $endIndex   = $end1->diffInDays($refDate1);
        if ($end1->lessThan($refDate1)) {
            $endIndex = $endIndex * -1;
        }

        // Generate Labels
        $labels = [];
        for ($i = $startIndex; $i <= $endIndex; $i++) {
            if ($i == 0) $labels[] = "H";
            elseif ($i < 0) $labels[] = "H" . $i;
            else $labels[] = "H+" . $i;
        }

        // Fetch Data
        $data1 = $this->getEventDailyStats($event1, $refDate1);
        $data2 = $this->getEventDailyStats($event2, $refDate2);

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
    private function getEventDailyStats($event, $referenceDate)
    {
        if (!$event) return [];

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

        foreach ($stats as $stat) {
            $flightDate = Carbon::parse($stat->flight_date)->startOfDay();
            $diff = $flightDate->diffInDays($referenceDate, false) * -1;
            
            if ($diff == 0) $label = "H";
            elseif ($diff < 0) $label = "H" . $diff; 
            else $label = "H+" . $diff; 
            
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
        if (!$event) return Carbon::now()->startOfDay();

        if ($event->peak_date) {
            return Carbon::parse($event->peak_date)->startOfDay();
        }

        $start = Carbon::parse($event->start_date)->startOfDay();
        $end   = Carbon::parse($event->end_date)->startOfDay();
        return $start->copy()->addDays(ceil($start->diffInDays($end) / 2));
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