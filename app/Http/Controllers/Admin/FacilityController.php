<?php

namespace App\Http\Controllers\Admin;

use App\Models\Facility;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class FacilityController extends Controller
{
    public function index()
    {
        $facilities = Facility::all();
        return view('admin2.fasilitas.index', compact('facilities'));
    }

    public function create()
    {
        return view('admin2.fasilitas.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|in:udara,darat,umum',
            'details' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $imagePath = $request->file('image')->store('fasilitas', 'public_uploads');

        Facility::create([
            'name' => $validated['name'],
            'category' => $validated['category'],
            'details' => explode("\n", $validated['details']), // Simpan sebagai array
            'image_path' => $imagePath,
        ]);

        return redirect()->route('admin.facilities.index')->with('success', 'Fasilitas baru berhasil ditambahkan.');
    }

    public function edit(Facility $facility)
    {
        return view('admin2.fasilitas.edit', compact('facility'));
    }

    public function update(Request $request, Facility $facility)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|in:udara,darat,umum',
            'details' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $imagePath = $facility->image_path;
        if ($request->hasFile('image')) {
            // Hapus gambar lama
            Storage::disk('public')->delete($facility->image_path);
            // Simpan gambar baru
            $imagePath = $request->file('image')->store('fasilitas', 'public_uploads');
        }

        $facility->update([
            'name' => $validated['name'],
            'category' => $validated['category'],
            'details' => explode("\n", $validated['details']),
            'image_path' => $imagePath,
        ]);

        return redirect()->route('admin.facilities.index')->with('success', 'Fasilitas berhasil diperbarui.');
    }

    public function destroy(Facility $facility)
    {
        Storage::disk('public')->delete($facility->image_path);
        $facility->delete();
        return redirect()->route('admin.facilities.index')->with('success', 'Fasilitas berhasil dihapus.');
    }
}
