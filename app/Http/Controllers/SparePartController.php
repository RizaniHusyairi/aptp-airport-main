<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\SparePart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SparePartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $spareParts = SparePart::latest()->get();
        return view('user_staff2.suku-cadang.index', compact('spareParts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('user_staff2.suku-cadang.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'stock' => 'required|integer|min:0',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('spare_parts', 'public');
        }

        SparePart::create([
            'name' => $validated['name'],
            'stock' => $validated['stock'],
            'photo_path' => $photoPath,
        ]);

        return redirect()->route('staff.spare-parts.index')->with('success', 'Suku cadang berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SparePart $sparePart)
    {
        return view('user_staff2.suku-cadang.edit', compact('sparePart'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SparePart $sparePart)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'stock' => 'required|integer|min:0', 
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $photoPath = $sparePart->photo_path;
        if ($request->hasFile('photo')) {
            // Hapus foto lama jika ada
            if ($sparePart->photo_path) {
                Storage::disk('public')->delete($sparePart->photo_path);
            }
            // Simpan foto baru
            $photoPath = $request->file('photo')->store('spare_parts', 'public');
        }

        $sparePart->update([
            'name' => $validated['name'],
            'stock' => $validated['stock'],
            'photo_path' => $photoPath,
        ]);

        return redirect()->route('staff.spare-parts.index')->with('success', 'Suku cadang berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SparePart $sparePart)
    {
        // Hapus foto dari storage jika ada
        if ($sparePart->photo_path) {
            Storage::disk('public')->delete($sparePart->photo_path);
        }

        $sparePart->delete();
        return redirect()->route('staff.spare-parts.index')->with('success', 'Suku cadang berhasil dihapus.');
    }
}
