<?php

namespace App\Http\Controllers\Staff_User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\AirFreightTraffic;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;

class LaluLintasController extends Controller
{
    public function index(Request $request)
    {
        $traffics = AirFreightTraffic::latest()->get();
        return view('user_staff.lalu-lintas.index', compact('traffics'));
    }


    public function create()
    {
        return view('user_staff.lalu-lintas.create');
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'date'      => 'required|date_format:Y-m',
            'type'      => 'required|string|in:Pesawat,Penumpang,Penumpang Transit,Bagasi,Kargo,Pos',
            'arrival'   => 'required|integer|min:0',
            'departure' => 'required|integer|min:0',
        ], [
            'date.required'        => 'Periode wajib diisi.',
            'date.date_format'     => 'Format periode tidak valid. Gunakan format Bulan-Tahun (YYYY-MM).',
            'type.required'        => 'Jenis angkutan wajib dipilih.',
            'type.in'              => 'Jenis angkutan tidak valid.',
            'arrival.required'     => 'Jumlah kedatangan wajib diisi.',
            'arrival.integer'      => 'Jumlah kedatangan harus berupa angka.',
            'arrival.min'          => 'Jumlah kedatangan tidak boleh negatif.',
            'departure.required'   => 'Jumlah keberangkatan wajib diisi.',
            'departure.integer'    => 'Jumlah keberangkatan harus berupa angka.',
            'departure.min'        => 'Jumlah keberangkatan tidak boleh negatif.',
        ]);

        AirFreightTraffic::create([
            'date'   => $validated['date'] . '-01',
            'type'      => $validated['type'],
            'arrival'   => $validated['arrival'],
            'departure' => $validated['departure'],
        ]);
        return redirect()->route('laluLintas.staffIndex')->with('success', 'Data aktivitas berhasil ditambahkan.');
    }



}
