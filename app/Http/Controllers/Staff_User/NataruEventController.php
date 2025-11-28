<?php

namespace App\Http\Controllers\Staff_User;

use App\Http\Controllers\Controller;
use App\Models\NataruEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
}