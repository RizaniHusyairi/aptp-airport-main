<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EvergreenInformation;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EvergreenInformationController extends Controller
{
    // Definisikan kategori yang tersedia di sini
    protected $categories = [
        'Daftar Informasi Publik',
        'Persuratan',
        'Inventaris BMN',
        'SOP Pelayanan Publik'
    ];

    public function index()
    {
        $informations = EvergreenInformation::orderBy('category')->latest()->get();
        return view('user_staff2.informasi-publik.informasi-setiap-saat.index', compact('informations'));
    }

    public function create()
    {
        return view('user_staff2.informasi-publik.informasi-setiap-saat.create', ['categories' => $this->categories]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category' => ['required', Rule::in($this->categories)],
            'title' => 'required|string|max:255',
            'published_date' => 'nullable|date',
            'document_link' => 'required|url',
        ]);

        EvergreenInformation::create($validated);
        return redirect()->route('staff.evergreen-informations.index')->with('success', 'Dokumen berhasil ditambahkan.');
    }

    public function edit(EvergreenInformation $evergreenInformation)
    {
        return view('user_staff2.informasi-publik.informasi-setiap-saat.edit', [
            'information' => $evergreenInformation,
            'categories' => $this->categories
        ]);
    }

    public function update(Request $request, EvergreenInformation $evergreenInformation)
    {
        $validated = $request->validate([
            'category' => ['required', Rule::in($this->categories)],
            'title' => 'required|string|max:255',
            'published_date' => 'nullable|date',
            'document_link' => 'required|url',
        ]);

        $evergreenInformation->update($validated);
        return redirect()->route('staff.evergreen-informations.index')->with('success', 'Dokumen berhasil diperbarui.');
    }

    public function destroy(EvergreenInformation $evergreenInformation)
    {
        $evergreenInformation->delete();
        return redirect()->route('staff.evergreen-informations.index')->with('success', 'Dokumen berhasil dihapus.');
    }
}
