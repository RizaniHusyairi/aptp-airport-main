<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\InventoryStatusLog; // <<< TAMBAHKAN MODEL LOG
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <<< TAMBAHKAN AUTH
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

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
     * === METHOD BARU: Menampilkan Halaman Detail ===
     */
    public function show(Inventory $inventory)
    {
        // Eager load riwayat status beserta user yang mengubah
        $inventory->load(['statusLogs.user']);
        return view('user_staff2.inventaris.show', compact('inventory'));
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

    /**
     * === METHOD BARU: Memperbarui Status Kondisi ===
     */
    /**
     * Memperbarui Status Kondisi dan Mencatat Riwayat
     */
    public function updateStatus(Request $request, Inventory $inventory)
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(['Baik', 'Pemeliharaan'])],
            'maintenance_report_link' => 'required_if:status,Pemeliharaan|nullable|url',
        ], [
            'maintenance_report_link.required_if' => 'Link laporan pemeliharaan wajib diisi jika status adalah "Pemeliharaan".',
            'maintenance_report_link.url' => 'Input harus berupa URL yang valid.',
        ]);

        $previousStatus = $inventory->status; // Simpan status lama
        $newStatus = $validated['status'];
        $reportLink = $validated['maintenance_report_link'] ?? null;

        // Hanya proses jika status berubah
        if ($previousStatus !== $newStatus) {
            $inventory->status = $newStatus;
            $inventory->maintenance_report_link = ($newStatus === 'Pemeliharaan') ? $reportLink : null;
            $inventory->save();

            // === CATAT RIWAYAT PERUBAHAN ===
            InventoryStatusLog::create([
                'inventory_id' => $inventory->id,
                'user_id' => Auth::id(),
                'previous_status' => $previousStatus,
                'new_status' => $newStatus,
                'notes' => ($newStatus === 'Pemeliharaan') ? $reportLink : null, // Simpan link di notes
            ]);
            
             return redirect()->route('staff.inventories.show', $inventory->id)->with('success', 'Status kondisi berhasil diperbarui.');
        }

        // Jika status tidak berubah, update link saja (jika ada)
        if ($newStatus === 'Pemeliharaan' && $reportLink !== $inventory->maintenance_report_link) {
             $inventory->maintenance_report_link = $reportLink;
             $inventory->save();
             // (Opsional) Anda bisa mencatat pembaruan link laporan di sini jika perlu
             return redirect()->route('staff.inventories.show', $inventory->id)->with('success', 'Link laporan pemeliharaan berhasil diperbarui.');
        }

        return redirect()->route('staff.inventories.show', $inventory->id)->with('info', 'Tidak ada perubahan status.');
    }
}
