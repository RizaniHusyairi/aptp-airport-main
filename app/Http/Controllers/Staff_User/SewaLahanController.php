<?php

namespace App\Http\Controllers\Staff_User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\Rental;

class SewaLahanController extends Controller
{
    /* ================== USER ROUTES ================== */
    public function store(Request $request)
    {
        $request->validate([
            'rental_name' => 'required|string|max:255',
            'description'   => 'required|string',
            'rental_type'   => 'required|string',
            'documents'     => 'required|array',
            'documents.*'     => 'required|file|mimes:pdf|max:2048',
        ], [
            'rental_name.required' => 'Nama sewa wajib diisi.',
            'rental_name.string'   => 'Nama sewa harus berupa teks.',
            'rental_name.max'      => 'Nama sewa maksimal 255 karakter.',

            'description.required'   => 'Deskripsi sewa wajib diisi.',
            'description.string'     => 'Deskripsi harus berupa teks.',
            
            'rental_type.required'   => 'Jenis sewa wajib dipilih.',
            'rental_type.string'     => 'Jenis sewa tidak valid.',
            
            'documents.required'     => 'Dokumen pendukung wajib diunggah.',
            'documents.array'        => 'Format dokumen tidak valid.',
            'documents.*.file'         => 'File dokumen tidak valid.',
            'documents.*.mimes'        => 'Dokumen harus berupa file dengan format: PDF',
            'documents.*.max'          => 'Ukuran dokumen maksimal 2MB.',

        ]);

        // Simpan file
        $documentPaths = [];
        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $file) {
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('documents/rental', $filename, 'public');
                $documentPaths[] = $path;
            }
        }
        $rental = Rental::create([
            'user_id' => auth()->id(),
            'rental_name' => $request->rental_name,
            'rental_type'   => $request->rental_type,
            'description'   => $request->description,
            'documents'     => $documentPaths,
        ]);

        return redirect()->route('sewa.index')->with('success', 'Pengajuan sewa lahan berhasil dikirim!');
    }

    public function create()
    {
        return view('user_staff.sewa-lahan.create');
    }

    public function destroy($id)
    {
        $rental = Rental::findOrFail($id);

        // Hapus file dokumen jika ada
        if (is_array($rental->documents)) {
            foreach ($rental->documents as $path) {
                if (\Storage::disk('public')->exists($path)) {
                    \Storage::disk('public')->delete($path);
                }
            }
        } elseif (is_string($rental->documents)) {
            // Fallback for older data format
            if (\Storage::disk('public')->exists($rental->documents)) {
                \Storage::disk('public')->delete($rental->documents);
            }
        }

        // Hapus rental
        $rental->delete();

        return redirect()->route('sewa.index')->with('success', 'Pengajuan berhasil dihapus.');    }

    public function indexUser()
    {
        $user = Auth::user();
        $rentals = $user->rentals()->latest()->get();
        return view('user_staff.sewa-lahan.index', compact('rentals'));    
    }


    /* ================== STAFF ROUTES ================== */
    public function index()
    {
        $rentals = Rental::with('user')->latest()->get();
        return view('user_staff.sewa-lahan.index', compact('rentals'));     
    }

    public function show($id)
    {
        $rental = Rental::with('user')->findOrFail($id);
        return view('user_staff.sewa-lahan.show', compact('rental'));
    }

    public function approve($id)
    {
        $rental = Rental::findOrFail($id);
        $rental->submission_status = 'disetujui';
        $rental->save();

        return redirect()->back()->with('success', 'Pengajuan berhasil disetujui.');
    }

    public function reject($id)
    {
        $rental = Rental::findOrFail($id);
        $rental->submission_status = 'ditolak';
        $rental->save();

        return redirect()->back()->with('success', 'Pengajuan berhasil ditolak.');
    }
}
