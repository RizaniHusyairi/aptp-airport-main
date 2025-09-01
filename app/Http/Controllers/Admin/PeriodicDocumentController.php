<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PeriodicDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PeriodicDocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $documents = PeriodicDocument::latest()->get();
        return view('user_staff2.informasi-berkala.index', compact('documents'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Ambil kategori yang sudah ada untuk dijadikan saran
        $categories = PeriodicDocument::select('category')->distinct()->pluck('category');
        return view('user_staff2.informasi-berkala.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category' => 'required|string|in:Laporan Kinerja,Survei kepuasan,LHKPN,Rencana kerja tahunan,Data statistik kepegawaian',
            'title' => 'required|string|max:255',
            'published_date' => 'required|date',
            'document_path' => 'required|url',

            // Validasi kondisional: pejabat_name wajib jika kategori adalah LHKPN
            'pejabat_name' => 'nullable|string|max:255|required_if:category,LHKPN',
        ]);

   
        PeriodicDocument::create($validated);

        
        return redirect()->route('staff.periodic-documents.index')->with('success', 'Dokumen berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PeriodicDocument $periodicDocument)
    {
        $categories = PeriodicDocument::select('category')->distinct()->pluck('category');
        return view('user_staff2.informasi-berkala.edit', [
            'document' => $periodicDocument,
            'categories' => $categories
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PeriodicDocument $periodicDocument)
    {
        $validated = $request->validate([
            'category' => 'required|string|in:Laporan Kinerja,Survei kepuasan,LHKPN,Rencana kerja tahunan,Data statistik kepegawaian',
            'title' => 'required|string|max:255',
            'published_date' => 'required|date',
            'document_path' => 'required|url',
            'pejabat_name' => 'nullable|string|max:255|required_if:category,LHKPN',
        ]);

        // Atur ulang nama pejabat jika kategori bukan LHKPN
        if ($validated['category'] !== 'LHKPN') {
            $validated['pejabat_name'] = null;
        }

       // Tidak ada lagi logika hapus/unggah file, langsung update
        $periodicDocument->update($validated);

 
        return redirect()->route('staff.periodic-documents.index')->with('success', 'Dokumen berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PeriodicDocument $periodicDocument)
    {

        // Hapus record dari database
        $periodicDocument->delete();

        return redirect()->route('staff.periodic-documents.index')->with('success', 'Dokumen berhasil dihapus.');
    }
}
