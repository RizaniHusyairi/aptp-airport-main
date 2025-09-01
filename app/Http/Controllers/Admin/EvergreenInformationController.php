<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EvergreenInformation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EvergreenInformationController extends Controller
{
    public function index()
    {
        $informations = EvergreenInformation::orderBy('published_date', 'desc')->get();
        return view('user_staff2.informasi-setiap-saat.index', compact('informations'));
    }

    public function create()
    {
        return view('user_staff2.informasi-setiap-saat.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'published_date' => 'required|date',
            'document_path' => 'required|url',
        ]);

        EvergreenInformation::create($validated);


        return redirect()->route('staff.evergreen-informations.index')->with('success', 'Dokumen berhasil ditambahkan.');
    }

    public function edit(EvergreenInformation $evergreenInformation)
    {
        return view('user_staff2.informasi-setiap-saat.edit', ['information' => $evergreenInformation]);
    }

    public function update(Request $request, EvergreenInformation $evergreenInformation)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'published_date' => 'required|date',
            'document_path' => 'required|url',
        ]);
        $evergreenInformation->update($validated);

        

        return redirect()->route('staff.evergreen-informations.index')->with('success', 'Dokumen berhasil diperbarui.');
    }

    public function destroy(EvergreenInformation $evergreenInformation)
    {
        
        // Hapus record dari database
        $evergreenInformation->delete();

        return redirect()->route('staff.evergreen-informations.index')->with('success', 'Dokumen berhasil dihapus.');
    }
}