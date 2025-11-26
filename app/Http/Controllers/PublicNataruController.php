<?php

namespace App\Http\Controllers;

use App\Models\NataruEvent;
use App\Models\NataruFlight;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PublicNataruController extends Controller
{
    /**
     * Menampilkan form input data untuk petugas lapangan.
     * Diakses via link: /posko/input/{token}
     */
    public function showForm($token)
    {
        // Cari event berdasarkan token
        $event = NataruEvent::where('public_token', $token)->first();

        // Validasi: Apakah event ada?
        if (!$event) {
            abort(404, 'Event Posko tidak ditemukan.');
        }

        // Validasi: Apakah event masih aktif?
        if (!$event->is_active) {
            return view('public.nataru.closed', compact('event'));
        }

        // Kirim data event ke view
        return view('public.nataru.form', compact('event'));
    }

    /**
     * Menyimpan data penerbangan dari form publik.
     */
    public function store(Request $request, $token)
    {
        // 1. Validasi Token & Event Lagi (Security Check)
        $event = NataruEvent::where('public_token', $token)->where('is_active', true)->firstOrFail();

        // 2. Validasi Input Form
        $validated = $request->validate([
            'flight_date' => 'required|date',
            'flight_time' => 'required',
            'airline' => 'required|string|max:100',
            'flight_number' => 'required|string|max:20',
            'status_flight' => 'required|in:Berjadwal,Perintis,Tidak Berjadwal',
            'route' => 'required|string|max:100', // Destination (From-To)
            'direction' => 'required|in:arrival,departure',
            'aircraft_type' => 'nullable|string|max:50',
            'aircraft_registration' => 'nullable|string|max:20',
            
            // Data Muatan (Pax & Cargo)
            'pax_adult' => 'required|integer|min:0',
            'pax_child' => 'required|integer|min:0',
            'pax_infant' => 'required|integer|min:0',
            'cargo' => 'required|integer|min:0',
            'baggage' => 'required|integer|min:0',
            
            // Ekonomi (Harga Tiket)
            'ticket_price_high' => 'nullable|numeric|min:0',
            'ticket_price_low' => 'nullable|numeric|min:0',

            // Identitas Petugas
            'officer_name' => 'required|string|max:255',
            'remarks' => 'nullable|string',
            
            // Kapasitas Pesawat (Untuk hitung Load Factor di backend jika perlu)
            'seat_capacity' => 'nullable|integer|min:1', 
        ], [
            'officer_name.required' => 'Nama petugas wajib diisi untuk data log.',
            'flight_date.required' => 'Tanggal penerbangan wajib diisi.',
        ]);

        // 3. Hitung Data Turunan
        $totalPax = $validated['pax_adult'] + $validated['pax_child'] + $validated['pax_infant'];
        
        // Hitung Load Factor (Opsional, jika kapasitas diisi)
        $loadFactor = 0;
        if ($request->filled('seat_capacity') && $request->seat_capacity > 0) {
            // Rumus sederhana: (Total Pax / Kapasitas) * 100
            // Catatan: Infant biasanya tidak menghabiskan seat (dipangku), 
            // jadi rumus load factor kadang mengecualikan infant atau menghitungnya beda.
            // Di sini kita pakai (Adult + Child) / Capacity sesuai standar umum.
            $occupiedSeats = $validated['pax_adult'] + $validated['pax_child'];
            $loadFactor = ($occupiedSeats / $request->seat_capacity) * 100;
        }

        // 4. Simpan ke Database
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
                'load_factor' => $loadFactor, // Simpan hasil hitungan

                'ticket_price_high' => $validated['ticket_price_high'],
                'ticket_price_low' => $validated['ticket_price_low'],

                'officer_name' => $validated['officer_name'],
                'remarks' => $validated['remarks'],
                'user_id' => null, // Null karena ini input publik
            ]);

            DB::commit();
            return back()->with('success', 'Data penerbangan ' . $validated['flight_number'] . ' berhasil disimpan. Terima kasih atas laporan Anda.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
        }
    }
}