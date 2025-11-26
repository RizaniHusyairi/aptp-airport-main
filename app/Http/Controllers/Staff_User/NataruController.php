<?php

namespace App\Http\Controllers\Staff_User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\NataruFlight;
use App\Models\NataruEvent; // Import Model Event
use Illuminate\Support\Facades\Auth;

class NataruController extends Controller
{
    // Metode index dihapus karena data sekarang ditampilkan via NataruEventController@show
    // Metode create dihapus karena input data dilakukan via link publik atau form di detail event

    public function store(Request $request)
    {
        $validated = $request->validate([
            'flight_date' => 'required|date',
            'airline' => 'required|string',
            'flight_number' => 'required|string',
            'direction' => 'required|in:arrival,departure',
            'route' => 'required|string',
            'flight_time' => 'required',
            'pax_adult' => 'required|integer|min:0',
            'pax_child' => 'required|integer|min:0',
            'pax_infant' => 'required|integer|min:0',
            'cargo' => 'required|integer|min:0',
            'baggage' => 'required|integer|min:0',
            'status_flight' => 'required|string',
        ]);

        // 1. Cari Event yang mencakup tanggal penerbangan ini
        // (Agar data terhubung ke event yang benar di database baru)
        $event = NataruEvent::where('start_date', '<=', $validated['flight_date'])
                            ->where('end_date', '>=', $validated['flight_date'])
                            ->first();

        if (!$event) {
            return back()->withInput()->with('error', 'Tidak ada Event Posko yang aktif pada tanggal tersebut. Silakan buat Event terlebih dahulu.');
        }

        // 2. Hitung total penumpang otomatis
        $totalPax = $validated['pax_adult'] + $validated['pax_child'] + $validated['pax_infant'];

        // 3. Simpan Data
        NataruFlight::create(array_merge($validated, [
            'nataru_event_id' => $event->id, // Hubungkan ke Event ID
            'pax_total' => $totalPax,
            'user_id' => Auth::id(),
            'officer_name' => $request->officer_name ?? Auth::user()->name,
            'ticket_price_high' => $request->ticket_price_high,
            'ticket_price_low' => $request->ticket_price_low,
            'aircraft_type' => $request->aircraft_type,
            'aircraft_registration' => $request->aircraft_registration,
            'remarks' => $request->remarks
        ]));

        // Redirect kembali ke halaman detail event jika input dilakukan oleh staff dari dashboard
        // Atau bisa disesuaikan jika ingin redirect ke tempat lain
        return redirect()->route('staff.nataru-events.show', $event->id)
            ->with('success', 'Data penerbangan berhasil ditambahkan ke event: ' . $event->name);
    }

    /**
     * Menghapus data penerbangan.
     * Menerima Request untuk mengecek apakah harus redirect kembali ke event detail.
     */
    public function destroy(Request $request, $id)
    {
        $flight = NataruFlight::findOrFail($id);
        
        // Simpan data penting sebelum dihapus untuk keperluan redirect
        $eventId = $flight->nataru_event_id;
        
        $flight->delete();
        
        // === LOGIKA BARU: Cek Redirect ===
        // Jika request memiliki input 'redirect_to_event', kembalikan ke halaman detail event
        if ($request->has('redirect_to_event')) {
            return redirect()->route('staff.nataru-events.show', $eventId)
                ->with('success', 'Data penerbangan berhasil dihapus dari event.');
        }

        // Default: Kembali ke halaman detail event (karena index harian sudah tidak ada)
        return redirect()->route('staff.nataru-events.show', $eventId)
            ->with('success', 'Data penerbangan dihapus.');
    }
}