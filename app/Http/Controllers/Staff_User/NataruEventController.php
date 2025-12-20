<?php

namespace App\Http\Controllers\Staff_User;

use App\Http\Controllers\Controller;
use App\Models\NataruEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Exports\NataruFlightExport; // Import Class Export
use Maatwebsite\Excel\Facades\Excel; // Import Facade Excel
use App\Models\NataruFlight;

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
}