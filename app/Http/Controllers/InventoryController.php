<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InventoryController extends Controller
{
    /**
     * Menampilkan daftar semua item inventaris.
     */
    public function index()
    {
        $inventories = Inventory::latest('input_date')->get();
        return view('user_staff2.inventaris.index', compact('inventories'));
    }

    /**
     * Menampilkan formulir untuk membuat item baru.
     */
    public function create()
    {
        return view('user_staff2.inventaris.create');
    }

    /**
     * Menyimpan item inventaris baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'input_date' => 'required|date',
            'photo' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $photoPath = $request->file('photo')->store('inventories', 'public');

        Inventory::create([
            'name' => $validated['name'],
            'input_date' => $validated['input_date'],
            'photo_path' => $photoPath,
        ]);

        return redirect()->route('staff.inventories.index')->with('success', 'Item inventaris berhasil ditambahkan.');
    }

    /**
     * Menampilkan formulir untuk mengedit item.
     */
    public function edit(Inventory $inventory)
    {
        return view('user_staff2.inventaris.edit', compact('inventory'));
    }

    /**
     * Memperbarui item inventaris di database.
     */
    public function update(Request $request, Inventory $inventory)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'input_date' => 'required|date',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $photoPath = $inventory->photo_path;
        if ($request->hasFile('photo')) {
            // Hapus foto lama jika ada
            if ($inventory->photo_path) {
                Storage::disk('public')->delete($inventory->photo_path);
            }
            // Simpan foto baru
            $photoPath = $request->file('photo')->store('inventories', 'public');
        }

        $inventory->update([
            'name' => $validated['name'],
            'input_date' => $validated['input_date'],
            'photo_path' => $photoPath,
        ]);

        return redirect()->route('staff.inventories.index')->with('success', 'Item inventaris berhasil diperbarui.');
    }

    /**
     * Menghapus item inventaris dari database.
     */
    public function destroy(Inventory $inventory)
    {
        // Hapus foto dari storage
        if ($inventory->photo_path) {
            Storage::disk('public')->delete($inventory->photo_path);
        }

        $inventory->delete();
        return redirect()->route('staff.inventories.index')->with('success', 'Item inventaris berhasil dihapus.');
    }
}
