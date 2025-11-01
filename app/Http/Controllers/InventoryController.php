<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\InventoryStatusLog;
use App\Http\Controllers\Controller;
use Carbon\Carbon; // <-- Import Carbon
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf; // <-- Import PDF
use App\Models\User; // <-- Pastikan User di-import
use Illuminate\Support\Facades\Auth; // <-- Import Auth
use App\Models\InventoryLogbook; // <-- Import model logbook

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
        // Eager load riwayat status DAN riwayat logbook
        $inventory->load(['statusLogs.user', 'logbooks.user']);
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


    /**
     * Menampilkan formulir untuk menambah entri logbook baru.
     */
    public function createLogbook(Inventory $inventory)
    {
        return view('user_staff2.inventaris.create-logbook', compact('inventory'));
    }

    /**
     * === METHOD BARU: Menyimpan entri logbook baru ===
     */
    public function storeLogbook(Request $request, Inventory $inventory)
    {
        $validated = $request->validate([
            'log_date' => 'required|date',
            'schedule_time' => 'required|date_format:H:i',
            'notes' => 'required|string',
            'documentation' => 'nullable|array',
            'documentation.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048', // Validasi setiap file
        ], [
            'documentation.*.image' => 'File dokumentasi harus berupa gambar.',
            'documentation.*.mimes' => 'Format foto harus: jpeg, png, jpg, webp.',
            'documentation.*.max' => 'Ukuran foto maksimal 2MB per file.',
        ]);

        $documentationPaths = [];
        if ($request->hasFile('documentation')) {
            foreach ($request->file('documentation') as $file) {
                // Simpan setiap file dan kumpulkan path-nya
                $path = $file->store('inventory_logbooks/' . $inventory->id, 'public');
                $documentationPaths[] = $path;
            }
        }

        $inventory->logbooks()->create([
            'user_id' => Auth::id(),
            'log_date' => $validated['log_date'],
            'schedule_time' => $validated['schedule_time'],
            'notes' => $validated['notes'],
            'documentation' => $documentationPaths, // Simpan array path ke kolom JSON
        ]);

        return redirect()->route('staff.inventories.show', $inventory->id)->with('success', 'Catatan logbook baru berhasil ditambahkan.');
    }

        /**
     * Menampilkan formulir untuk mengedit entri logbook.
     */
    public function editLogbook(Inventory $inventory, InventoryLogbook $logbook)
    {
        // Pastikan logbook milik inventaris yang benar
        if ($logbook->inventory_id !== $inventory->id) {
            abort(404);
        }
        return view('user_staff2.inventaris.edit-logbook', compact('inventory', 'logbook'));
    }

    /**
     * Memperbarui entri logbook.
     */
    public function updateLogbook(Request $request, Inventory $inventory, InventoryLogbook $logbook)
    {
        $validated = $request->validate([
            'log_date' => 'required|date',
            'schedule_time' => 'required|date_format:H:i',
            'notes' => 'required|string',
            'documentation' => 'nullable|array',
            'documentation.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
            'deleted_photos' => 'nullable|array', // <-- Validasi array foto yang dihapus
            'deleted_photos.*' => 'string' // <-- Validasi path foto adalah string
        ]);

        $existingPhotos = $logbook->documentation ?? []; // Ambil path foto lama

        // 1. Proses Hapus Foto
        $deletedPhotos = $request->input('deleted_photos', []);
        if (!empty($deletedPhotos)) {
            foreach ($deletedPhotos as $pathToDelete) {
                // Hapus file dari storage
                Storage::disk('public')->delete($pathToDelete);
            }
            // Filter array $existingPhotos, hapus yang ada di $deletedPhotos
            $existingPhotos = array_values(array_diff($existingPhotos, $deletedPhotos));
        }

        // 2. Proses Tambah Foto Baru
        if ($request->hasFile('documentation')) {
            foreach ($request->file('documentation') as $file) {
                $path = $file->store('inventory_logbooks/' . $inventory->id, 'public');
                $existingPhotos[] = $path; // Tambahkan path baru ke array
            }
        }

        // 3. Update database
        $logbook->update([
            'user_id' => Auth::id(), // Perbarui user ID ke pengedit terakhir
            'log_date' => $validated['log_date'],
            'schedule_time' => $validated['schedule_time'],
            'notes' => $validated['notes'],
            'documentation' => $existingPhotos, // Simpan array yang sudah final
        ]);

        return redirect()->route('staff.inventories.show', $inventory->id)->with('success', 'Catatan logbook berhasil diperbarui.');
    }

    /**
     * Menghapus entri logbook.
     */
    public function destroyLogbook(Inventory $inventory, InventoryLogbook $logbook)
    {
        // Hapus semua foto dokumentasi dari storage
        if (!empty($logbook->documentation)) {
            foreach ($logbook->documentation as $photoPath) {
                Storage::disk('public')->delete($photoPath);
            }
        }

        $logbook->delete();
        return redirect()->route('staff.inventories.show', $inventory->id)->with('success', 'Catatan logbook berhasil dihapus.');
    }

    /**
     * Mengekspor logbook inventaris ke PDF berdasarkan periode.
     */
    public function exportLogbookPdf(Request $request, Inventory $inventory)
    {
        $validated = $request->validate([
            'month_year' => 'required|date_format:Y-m',
        ], [
            'month_year.required' => 'Periode bulan dan tahun wajib dipilih.',
            'month_year.date_format' => 'Format periode tidak valid.'
        ]);

        try {
            $period = Carbon::createFromFormat('Y-m', $validated['month_year']);
        } catch (\Exception $e) {
            return back()->with('error', 'Format periode tidak valid.');
        }

        $logbooks = $inventory->logbooks()
            ->whereYear('log_date', $period->year)
            ->whereMonth('log_date', $period->month)
            ->with('user') // Eager load user
            ->latest('log_date') // Urutkan berdasarkan tanggal
            ->get();

        $periodeString = $period->translatedFormat('F Y');

        $pdf = PDF::loadView('user_staff2.inventaris.logbook_pdf', [
            'inventory' => $inventory,
            'logbooks' => $logbooks,
            'periode' => $periodeString
        ]);

        $pdf->setPaper('a4', 'portrait');

        $fileName = 'Logbook-' . Str::slug($inventory->name) . '-' . $period->format('Y-m') . '.pdf';
        
        return $pdf->download($fileName);
    }


}
