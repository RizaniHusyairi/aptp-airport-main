<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AirTrafficLog;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AirTrafficLogController extends Controller
{
    /**
     * Menampilkan daftar data lalu lintas udara harian.
     */
    public function index()
    {
        $traffics = AirTrafficLog::latest('date')->get();
        return view('user_staff2.lalu-lintas-harian.index', compact('traffics'));
    }

    /**
     * Menampilkan formulir untuk menambah data baru.
     */
    public function create()
    {
        return view('user_staff2.lalu-lintas-harian.create');
    }

    /**
     * Menyimpan data baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date|unique:air_traffic_logs,date',
            'aircraft_arrival' => 'required|integer|min:0',
            'aircraft_departure' => 'required|integer|min:0',
            'passenger_arrival' => 'required|integer|min:0',
            'passenger_departure' => 'required|integer|min:0',
            'baggage_arrival' => 'required|integer|min:0',
            'baggage_departure' => 'required|integer|min:0',
            'cargo_arrival' => 'required|integer|min:0',
            'cargo_departure' => 'required|integer|min:0',
        ], [
            'date.unique' => 'Data untuk tanggal ini sudah ada. Silakan edit data yang ada.',
        ]);

        AirTrafficLog::create($validated);

        return redirect()->route('staff.air-traffic.index')->with('success', 'Data lalu lintas udara berhasil ditambahkan.');
    }

    /**
     * Menampilkan formulir untuk mengedit data.
     */
    public function edit(AirTrafficLog $airTrafficLog)
    {
        return view('user_staff2.lalu-lintas-harian.edit', ['traffic' => $airTrafficLog]);
    }

    /**
     * Memperbarui data di database.
     */
    public function update(Request $request, AirTrafficLog $airTrafficLog)
    {
        $validated = $request->validate([
            'date' => [
                'required',
                'date',
                // Pastikan tanggal unik, KECUALI untuk data yang sedang diedit
                Rule::unique('air_traffic_logs')->ignore($airTrafficLog->id),
            ],
            'aircraft_arrival' => 'required|integer|min:0',
            'aircraft_departure' => 'required|integer|min:0',
            'passenger_arrival' => 'required|integer|min:0',
            'passenger_departure' => 'required|integer|min:0',
            'baggage_arrival' => 'required|integer|min:0',
            'baggage_departure' => 'required|integer|min:0',
            'cargo_arrival' => 'required|integer|min:0',
            'cargo_departure' => 'required|integer|min:0',
        ], [
            'date.unique' => 'Data untuk tanggal ini sudah ada.',
        ]);

        $airTrafficLog->update($validated);

        return redirect()->route('staff.air-traffic.index')->with('success', 'Data lalu lintas udara berhasil diperbarui.');
    }

    /**
     * Menghapus data dari database.
     */
    public function destroy(AirTrafficLog $airTrafficLog)
    {
        $airTrafficLog->delete();
        return redirect()->route('staff.air-traffic.index')->with('success', 'Data lalu lintas udara berhasil dihapus.');
    }
}