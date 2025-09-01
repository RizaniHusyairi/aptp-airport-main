<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ImmediateInformation;
use Illuminate\Http\Request;

class ImmediateInformationController extends Controller
{
    /**
     * Menampilkan daftar semua informasi.
     */
    public function index()
    {
        $informations = ImmediateInformation::latest()->get();
        return view('user_staff2.informasi-serta-merta.index', compact('informations'));
    }

    /**
     * Menampilkan form untuk membuat informasi baru.
     */
    public function create()
    {
        return view('user_staff2.informasi-serta-merta.create');
    }

    /**
     * Menyimpan informasi baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'uraian' => 'required|string',
            'keterangan' => 'required|string',
            'link_url' => 'required|url',
            'link_text' => 'required|string|max:100',
        ]);

        ImmediateInformation::create($validated);

        return redirect()->route('staff.immediate-informations.index')->with('success', 'Informasi Serta Merta berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit informasi.
     */
    public function edit(ImmediateInformation $immediateInformation)
    {
        return view('user_staff2.informasi-serta-merta.edit', ['information' => $immediateInformation]);
    }

    /**
     * Memperbarui informasi di database.
     */
    public function update(Request $request, ImmediateInformation $immediateInformation)
    {
        $validated = $request->validate([
            'uraian' => 'required|string',
            'keterangan' => 'required|string',
            'link_url' => 'required|url',
            'link_text' => 'required|string|max:100',
        ]);

        $immediateInformation->update($validated);

        return redirect()->route('staff.immediate-informations.index')->with('success', 'Informasi Serta Merta berhasil diperbarui.');
    }

    /**
     * Menghapus informasi dari database.
     */
    public function destroy(ImmediateInformation $immediateInformation)
    {
        $immediateInformation->delete();
        return redirect()->route('staff.immediate-informations.index')->with('success', 'Informasi Serta Merta berhasil dihapus.');
    }
}