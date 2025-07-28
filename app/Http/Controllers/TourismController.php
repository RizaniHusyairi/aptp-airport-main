<?php

namespace App\Http\Controllers;

use App\Models\Tourism;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TourismController extends Controller
{
    public function index()
    {
        $tourisms = Tourism::latest()->get();
        return view('admin2.pariwisata.index', compact('tourisms'));
    }

    public function create()
    {
        return view('admin2.pariwisata.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:tourisms,name',
            'category' => 'required|string|in:Alam,Budaya,Religi,Kuliner',
            'cover_image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'gallery.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'short_desc' => 'required|string|max:255',
            'description' => 'required|string',
            'address' => 'required|string',
            'gmaps_url' => 'nullable|url',
            'status' => 'required|in:published,draft',
        ]);

        $coverImagePath = $request->file('cover_image')->store('tourism/covers', 'public');
        
        $galleryPaths = [];
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $file) {
                $galleryPaths[] = $file->store('tourism/gallery', 'public');
            }
        }

        Tourism::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'category' => $validated['category'],
            'cover_image' => $coverImagePath,
            'gallery' => $galleryPaths,
            'short_desc' => $validated['short_desc'],
            'description' => $validated['description'],
            'address' => $validated['address'],
            'gmaps_url' => $validated['gmaps_url'],
            'status' => $validated['status'],
        ]);

        return redirect()->route('admin.tourism.index')->with('success', 'Destinasi wisata baru berhasil ditambahkan.');
    }

    public function edit(Tourism $tourism)
    {
        return view('admin2.pariwisata.edit', compact('tourism'));
    }

    public function update(Request $request, Tourism $tourism)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:tourisms,name,' . $tourism->id,
            'category' => 'required|string|in:Alam,Budaya,Religi,Kuliner',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'gallery.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'short_desc' => 'required|string|max:255',
            'description' => 'required|string',
            'address' => 'required|string',
            'gmaps_url' => 'nullable|url',
            'status' => 'required|in:published,draft',
        ]);

        $coverImagePath = $tourism->cover_image;
        if ($request->hasFile('cover_image')) {
            Storage::disk('public')->delete($tourism->cover_image);
            $coverImagePath = $request->file('cover_image')->store('tourism/covers', 'public');
        }

        $galleryPaths = $tourism->gallery ?? [];
        if ($request->hasFile('gallery')) {
            // Hapus galeri lama jika ada gambar baru yang diunggah
            foreach ($galleryPaths as $oldImage) {
                Storage::disk('public')->delete($oldImage);
            }
            $galleryPaths = []; // Kosongkan array untuk diisi ulang
            foreach ($request->file('gallery') as $file) {
                $galleryPaths[] = $file->store('tourism/gallery', 'public');
            }
        }

        $tourism->update([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'category' => $validated['category'],
            'cover_image' => $coverImagePath,
            'gallery' => $galleryPaths,
            'short_desc' => $validated['short_desc'],
            'description' => $validated['description'],
            'address' => $validated['address'],
            'gmaps_url' => $validated['gmaps_url'],
            'status' => $validated['status'],
        ]);

        return redirect()->route('admin.tourism.index')->with('success', 'Destinasi wisata berhasil diperbarui.');
    }

    public function destroy(Tourism $tourism)
    {
        // Hapus gambar sampul
        Storage::disk('public')->delete($tourism->cover_image);
        // Hapus semua gambar galeri
        if ($tourism->gallery) {
            foreach ($tourism->gallery as $image) {
                Storage::disk('public')->delete($image);
            }
        }
        $tourism->delete();
        return redirect()->route('admin.tourism.index')->with('success', 'Destinasi wisata berhasil dihapus.');
    }
}
