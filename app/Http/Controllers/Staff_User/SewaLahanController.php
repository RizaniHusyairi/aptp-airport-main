<?php

namespace App\Http\Controllers\Staff_User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\Rental;
use Illuminate\Support\Facades\Storage;

class SewaLahanController extends Controller
{

    // Konfigurasi jenis sewa
    protected $rentalTypes = [
        'ruang' => [
            'name' => 'Ruang',
            'validation' => [
            'area' => 'required|integer',
            'location' => 'required|string',
            ],
            'fields' => ['area', 'location'],
        ],
        'lahan' => [
            'name' => 'Lahan',
            'validation' => [
            'area' => 'required|integer',
            'location' => 'required|string',
            ],
            'fields' => ['area', 'location'],
        ],
        'xray_kabin' => [
            'name' => 'Xray Kabin',
            'validation' => [
            'quantity' => 'required|integer',
            ],
            'fields' => ['quantity'],
        ],
        'xray_kargo' => [
            'name' => 'Xray Kargo',
            'validation' => [
            'quantity' => 'required|integer',
            ],
            'fields' => ['quantity'],
        ],
        'bus' => [
            'name' => 'Kendaraan Roda Empat',
            'validation' => [
            'quantity' => 'required|integer',
            ],
            'fields' => ['quantity'],
        ],
        'workshop' => [
            'name' => 'Peralatan Workshop',
            'validation' => [
            'quantity' => 'required|integer',
            ],
            'fields' => ['quantity'],
        ],
        'reklame' => [
            'name' => 'Penempatan Reklame',
            'validation' => [
            'design_file' => 'required|file|mimes:jpg,png|max:2048',
            ],
            'fields' => ['design_file'],
        ],
    ];

    /* ================== USER ROUTES ================== */


    public function index()
    {
        $user = Auth::user();
        $rentals = $user->rentals()->latest()->get();
        return view('user_staff.sewa.index', compact('rentals'));
    }
    
    public function create()
    {
        $rentalTypes = $this->rentalTypes;
        return view('user_staff.sewa.create', compact('rentalTypes'));
    }

    public function store(Request $request)
    {
        $rules = [
            'rental_type' => 'required|string|max:50',
            'rental_name' => 'required|string|max:255',
            'description' => 'required|string',
            'documents' => 'required|file|mimes:pdf|max:2048',
        ];

        

        $validated = $request->validate($rules, [
            'rental_type.required' => 'Jenis sewa wajib dipilih.',
            'rental_type.in' => 'Jenis sewa tidak valid.',
            'rental_name.required' => 'Nama sewa wajib diisi.',
            'rental_name.string' => 'Nama sewa harus berupa teks.',
            'rental_name.max' => 'Nama sewa maksimal 255 karakter.',
            'description.required' => 'Deskripsi sewa wajib diisi.',
            'description.string' => 'Deskripsi harus berupa teks.',
            'documents.required' => 'Dokumen pendukung wajib diunggah.',
            'documents.file' => 'File dokumen tidak valid.',
            'documents.mimes' => 'Dokumen harus berupa file dengan format: PDF',
            'documents.max' => 'Ukuran dokumen maksimal 2MB.',
        ]);

        if($validated['rental_type'] === 'Lainnya') {
            // Tambahkan validasi khusus untuk sewa lainnya
            $request->validate([
                'rental_more' => 'required|string|max:150',
            ], [
                'rental_more.required' => 'Jenis sewa lainnya wajib diunggah.',
                'rental_more.max' => 'Jenis sewa maksimal 150 karakter.',
            ]);
        }

        // Simpan file dokumen
        $file = $request->file('documents');
        $filename = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('documents/rental', $filename, 'public');

        // Simpan file desain (jika ada)
        $designFilePath = null;
        if ($request->hasFile('design_file')) {
            $designFile = $request->file('design_file');
            $designFilename = time() . '_' . $designFile->getClientOriginalName();
            $designFilePath = $designFile->storeAs('documents/rental', $designFilename, 'public');
        }

        // Siapkan data untuk pembuatan Rental
        $rentalData = [
            'rental_name' => $validated['rental_name'],
            'description' => $validated['description'],
            'rental_type' => $validated['rental_type'],
            'rental_more' => $validated['rental_more'] ?? null,
            'documents' => $filePath,
        ];

        // Simpan Rental
        $rental = Rental::create($rentalData);

        // Attach ke pivot rental_user
        Auth::user()->rentals()->attach($rental->id, [
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('sewa.index')
            ->with('success', 'Pengajuan sewa berhasil dikirim!');
    }

    
    public function destroy($id)
    {
        $rental = Rental::findOrFail($id);

        // Hapus file dokumen jika ada
        if ($rental->documents && Storage::disk('public')->exists($rental->documents)) {
            Storage::disk('public')->delete($rental->documents);
        }
        

        // Hapus relasi pivot
        $rental->users()->detach();

        // Hapus rental
        $rental->delete();

        return redirect()->route('sewa.index')
            ->with('success', 'Pengajuan berhasil dihapus.');
    }

    


    /* ================== STAFF ROUTES ================== */

    public function indexStaff()
    {
        $rentals = Rental::with('users')->latest()->get();
        return view('user_staff.sewa.index', compact('rentals'));
    }

    public function show($id)
    {
        $rental = Rental::with('users')->findOrFail($id);
        return view('user_staff.sewa.show', compact('rental'));
    }

    public function approve($id)
    {
        $rental = Rental::findOrFail($id);
        $rental->submission_status = 'disetujui';
        $rental->save();

        // // Catat pemasukan ke tabel finances
        // \App\Models\Finance::create([
        //     'date' => now(),
        //     'flow_type' => 'in',
        //     'amount' => 10000000, // Sesuaikan nilai
        //     'note' => "Pemasukan dari sewa {$rental->rental_type} #{$rental->id}",
        // ]);

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
