<?php

namespace App\Http\Controllers\Staff_User;

use App\Http\Controllers\Controller;
use App\Models\NataruEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class NataruEventController extends Controller
{
    /**
     * Menampilkan daftar event.
     */
    public function index()
    {
        $events = NataruEvent::latest()->get();
        return view('user_staff2.nataru.event.index', compact('events'));
    }

    /**
     * Menampilkan form tambah event.
     */
    public function create()
    {
        return view('user_staff2.nataru.event.create');
    }

    /**
     * Menyimpan event baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'description' => 'nullable|string',
        ], [
            'name.required' => 'Nama event wajib diisi.',
            'start_date.required' => 'Tanggal mulai wajib diisi.',
            'end_date.after_or_equal' => 'Tanggal selesai tidak boleh sebelum tanggal mulai.',
        ]);

        // Token dibuat otomatis di Model (boot method)
        NataruEvent::create([
            'name' => $validated['name'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'description' => $validated['description'],
            'is_active' => true, 
        ]);

        return redirect()->route('staff.nataru-events.index')->with('success', 'Event berhasil dibuat.');
    }

    /**
     * Menampilkan form edit.
     */
    public function edit(NataruEvent $nataruEvent)
    {
        return view('user_staff2.nataru.event.edit', compact('nataruEvent'));
    }

    /**
     * Update event.
     */
    public function update(Request $request, NataruEvent $nataruEvent)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'description' => 'nullable|string',
            'is_active' => 'boolean', // Tambahan untuk update status aktif
        ]);

        $nataruEvent->update($validated);

        return redirect()->route('staff.nataru-events.index')->with('success', 'Event berhasil diperbarui.');
    }

    /**
     * Hapus event.
     */
    public function destroy(NataruEvent $nataruEvent)
    {
        try {
            $nataruEvent->delete();
            return redirect()->route('staff.nataru-events.index')->with('success', 'Event berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus event. Pastikan tidak ada data penerbangan terkait.');
        }
    }

    /**
     * Menampilkan detail event dan data penerbangan di dalamnya.
     */
    public function show(NataruEvent $nataruEvent)
    {
        // Eager load flights urut berdasarkan tanggal dan jam terbaru
        $nataruEvent->load(['flights' => function($query) {
            $query->orderBy('flight_date', 'desc')->orderBy('flight_time', 'desc');
        }]);
        
        // Hitung ringkasan sederhana untuk ditampilkan di atas
        $summary = [
            'total_flights' => $nataruEvent->flights->count(),
            'total_pax' => $nataruEvent->flights->sum('pax_total'),
            'total_cargo' => $nataruEvent->flights->sum('cargo'),
        ];

        return view('user_staff2.nataru.event.show', compact('nataruEvent', 'summary'));
    }
}