<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Models\AirTrafficLog;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;

class AirTrafficLogController extends Controller
{
    /**
     * Helper: Atribut nama field dalam Bahasa Indonesia.
     * Digunakan agar pesan error lebih manusiawi.
     */
    private function getAttributes()
    {
        return [
            'date' => 'Tanggal Laporan',
            'aircraft_arrival' => 'Jumlah Pesawat Datang',
            'aircraft_departure' => 'Jumlah Pesawat Berangkat',
            'passenger_arrival' => 'Jumlah Penumpang Datang',
            'passenger_departure' => 'Jumlah Penumpang Berangkat',
            'baggage_arrival' => 'Jumlah Bagasi Datang',
            'baggage_departure' => 'Jumlah Bagasi Berangkat',
            'cargo_arrival' => 'Jumlah Kargo Datang',
            'cargo_departure' => 'Jumlah Kargo Berangkat',
        ];
    }

    /**
     * Helper: Pesan Error Bahasa Indonesia General.
     */
    private function getMessages()
    {
        return [
            'required' => ':attribute wajib diisi.',
            'integer' => ':attribute harus berupa angka bulat.',
            'min' => ':attribute tidak boleh kurang dari :min.',
            'date' => 'Format :attribute tidak valid.',
            'unique' => ':attribute sudah terdaftar di sistem. Silakan edit data yang ada.',
        ];
    }

    /**
     * Menampilkan daftar data lalu lintas udara harian.
     */
    /**
     * Menampilkan daftar data lalu lintas udara harian dengan Filter & Sort.
     */
    public function index(Request $request)
    {
        // Mulai Query Builder
        $query = AirTrafficLog::query();

        // 1. Logika Filter Bulan (jika ada input 'filter_month')
        if ($request->filled('filter_month')) {
            try {
                $date = Carbon::createFromFormat('Y-m', $request->filter_month);
                $query->whereYear('date', $date->year)
                      ->whereMonth('date', $date->month);
            } catch (\Exception $e) {
                // Abaikan jika format tanggal salah
            }
        }

        // 2. Logika Sorting (jika ada input 'sort_order', default 'desc')
        $sortOrder = $request->input('sort_order', 'desc'); // Default: Terbaru
        // Validasi agar hanya bisa 'asc' atau 'desc'
        if (!in_array($sortOrder, ['asc', 'desc'])) {
            $sortOrder = 'desc';
        }
        
        $query->orderBy('date', $sortOrder);

        // Eksekusi Query
        $traffics = $query->get();

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
        // 1. Definisi Rules
        $rules = [
            'date' => 'required|date|unique:air_traffic_logs,date',
            'aircraft_arrival' => 'required|integer|min:0',
            'aircraft_departure' => 'required|integer|min:0',
            'passenger_arrival' => 'required|integer|min:0',
            'passenger_departure' => 'required|integer|min:0',
            'baggage_arrival' => 'required|integer|min:0',
            'baggage_departure' => 'required|integer|min:0',
            'cargo_arrival' => 'required|integer|min:0',
            'cargo_departure' => 'required|integer|min:0',
        ];

        // 2. Validasi dengan Pesan & Atribut Custom
        $validated = $request->validate($rules, $this->getMessages(), $this->getAttributes());

        // 3. Simpan
        AirTrafficLog::create($validated);

        return redirect()->route('staff.air-traffic.index')
            ->with('success', 'Data lalu lintas udara berhasil ditambahkan.');
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
        // 1. Definisi Rules (Perhatikan bagian Unique Ignore)
        $rules = [
            'date' => [
                'required',
                'date',
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
        ];

        // 2. Validasi
        $validated = $request->validate($rules, $this->getMessages(), $this->getAttributes());

        // 3. Update
        $airTrafficLog->update($validated);

        return redirect()->route('staff.air-traffic.index')
            ->with('success', 'Data lalu lintas udara berhasil diperbarui.');
    }

    /**
     * Menghapus data dari database.
     */
    public function destroy(AirTrafficLog $airTrafficLog)
    {
        $airTrafficLog->delete();
        return redirect()->route('staff.air-traffic.index')
            ->with('success', 'Data lalu lintas udara berhasil dihapus.');
    }

    /**
     * Mengekspor data LLAU bulanan ke PDF.
     */
    public function exportPdf(Request $request)
    {
        // Validasi Khusus untuk Input Bulan
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

        // Ambil data berdasarkan bulan & tahun
        $traffics = AirTrafficLog::whereYear('date', $period->year)
            ->whereMonth('date', $period->month)
            ->orderBy('date', 'asc')
            ->get();

        if ($traffics->isEmpty()) {
            return back()->with('error', 'Tidak ada data lalu lintas udara untuk periode ' . $period->translatedFormat('F Y') . '.');
        }

        $periodeString = $period->translatedFormat('F Y');

        // Hitung Total (Calculation Logic)
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

        // Load View PDF
        $pdf = PDF::loadView('user_staff2.lalu-lintas-harian.llau_pdf', [
            'traffics' => $traffics,
            'periode' => $periodeString,
            'totals' => $totals
        ]);

        // Set Kertas
        $pdf->setPaper('a4', 'portrait');

        $fileName = 'Rekapitulasi-LLAU-' . $period->format('Y-m') . '.pdf';
        
        return $pdf->download($fileName);
    }
}