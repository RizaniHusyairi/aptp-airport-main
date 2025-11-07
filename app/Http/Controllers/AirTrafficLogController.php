<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf; // Import PDF
use Illuminate\Http\Request;
use App\Models\AirTrafficLog;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;

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

    /**
     * Mengekspor data LLAU bulanan ke PDF.
     */
    public function exportPdf(Request $request)
    {
        $validated = $request->validate([
            'month_year' => 'required|date_format:Y-m',
        ], [
            'month_year.required' => 'Periode bulan dan tahun wajib dipilih.',
            'month_year.date_format' => 'Format periode tidak valid.'
        ]);

        try {
            $period = Carbon::createFromFormat('Y-m', $validated['month_year']);
        } catch (\Exception $e) {
            return back()->with('error', 'Format periode tidak valid.');
        }

        $traffics = AirTrafficLog::whereYear('date', $period->year)
            ->whereMonth('date', $period->month)
            ->orderBy('date', 'asc') // Urutkan berdasarkan tanggal
            ->get();

        if ($traffics->isEmpty()) {
            return back()->with('error', 'Tidak ada data lalu lintas udara untuk periode yang dipilih.');
        }

        $periodeString = $period->translatedFormat('F Y');

        // Hitung Total
        $totals = [
            'aircraft_arrival' => $traffics->sum('aircraft_arrival'),
            'aircraft_departure' => $traffics->sum('aircraft_departure'),
            'aircraft_total' => $traffics->sum('aircraft_arrival') + $traffics->sum('aircraft_departure'),
            'passenger_arrival' => $traffics->sum('passenger_arrival'),
            'passenger_departure' => $traffics->sum('passenger_departure'),
            'passenger_total' => $traffics->sum('passenger_arrival') + $traffics->sum('passenger_departure'),
            'baggage_arrival' => $traffics->sum('baggage_arrival'),
            'baggage_departure' => $traffics->sum('baggage_departure'),
            'baggage_total' => $traffics->sum('baggage_arrival') + $traffics->sum('baggage_departure'),
            'cargo_arrival' => $traffics->sum('cargo_arrival'),
            'cargo_departure' => $traffics->sum('cargo_departure'),
            'cargo_total' => $traffics->sum('cargo_arrival') + $traffics->sum('cargo_departure'),
        ];

        $pdf = PDF::loadView('user_staff2.lalu-lintas-harian.llau_pdf', [
            'traffics' => $traffics,
            'periode' => $periodeString,
            'totals' => $totals
        ]);

        // Atur ke landscape agar tabel muat
        $pdf->setPaper('a4', 'potrait');

        $fileName = 'Rekapitulasi-LLAU-' . $period->format('Y-m') . '.pdf';
        
        return $pdf->download($fileName);
    }
}