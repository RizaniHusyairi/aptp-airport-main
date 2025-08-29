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
        return view('admin2.informasi-berkala.index', compact('documents'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Ambil kategori yang sudah ada untuk dijadikan saran
        $categories = PeriodicDocument::select('category')->distinct()->pluck('category');
        return view('admin2.informasi-berkala.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'published_date' => 'required|date',
            'document' => 'required|file|mimes:pdf|max:2048', // Nama input diubah menjadi 'document'
        ]);

        $path = $request->file('document')->store('periodic_documents', 'public');

        PeriodicDocument::create([
            'category' => $validated['category'],
            'title' => $validated['title'],
            'published_date' => $validated['published_date'],
            'document_path' => $path,
        ]);

        return redirect()->route('admin.periodic-documents.index')->with('success', 'Dokumen berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PeriodicDocument $periodicDocument)
    {
        $categories = PeriodicDocument::select('category')->distinct()->pluck('category');
        return view('admin2.informasi-berkala.edit', [
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
            'category' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'published_date' => 'required|date',
            'document' => 'nullable|file|mimes:pdf|max:2048', // Dokumen opsional saat update
        ]);

        $path = $periodicDocument->document_path;
        if ($request->hasFile('document')) {
            // Hapus file lama jika ada
            if ($path) {
                Storage::disk('public')->delete($path);
            }
            // Simpan file baru
            $path = $request->file('document')->store('periodic_documents', 'public');
        }

        $periodicDocument->update([
            'category' => $validated['category'],
            'title' => $validated['title'],
            'published_date' => $validated['published_date'],
            'document_path' => $path,
        ]);

        return redirect()->route('admin.periodic-documents.index')->with('success', 'Dokumen berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PeriodicDocument $periodicDocument)
    {
        // Hapus file dari storage
        if ($periodicDocument->document_path) {
            Storage::disk('public')->delete($periodicDocument->document_path);
        }

        // Hapus record dari database
        $periodicDocument->delete();

        return redirect()->route('admin.periodic-documents.index')->with('success', 'Dokumen berhasil dihapus.');
    }
}
