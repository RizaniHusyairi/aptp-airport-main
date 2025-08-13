<?php

namespace App\Http\Controllers\Staff_User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\Rental;
use Illuminate\Support\Facades\Storage;

class SewaController extends Controller
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
        return view('user_staff2.sewa.index', compact('rentals'));
    }
    
    public function create()
    {
        $rentalTypes = $this->rentalTypes;
        return view('user_staff2.sewa.create', compact('rentalTypes'));
    }

    public function store(Request $request)
    {
        $rules = [
            'rental_type' => 'required|string|max:50',
            'rental_name' => 'required|string|max:255',
            'description' => 'required|string',
            'documents' => 'required|file|mimes:pdf|max:2048',
        ];

        

        $request->validate($rules, [
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
        if($request->rental_type === 'Lainnya') {
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
        
        // Siapkan data untuk pembuatan Rental
        $rentalData = [
            
            'rental_name' => $request->rental_name,
            'description' => $request->description,
            'rental_type' => $request->rental_type,
            'rental_more' => $request->input('rental_more'),
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
        return view('user_staff2.sewa.index', compact('rentals'));
    }

    public function show($id)
    {
        $rental = Rental::with('users')->findOrFail($id);
        return view('user_staff2.sewa.show', compact('rental'));
    }

    public function updateStatus(Request $request, Rental $sewa)
    {
        $validated = $request->validate([
            'submission_status' => 'required|in:Disetujui,Ditolak,Revisi Diperlukan',
            'staff_notes' => 'required_if:status,Ditolak,Revisi Diperlukan|nullable|string',
            'reply_document_path' => 'required_if:status,Disetujui|nullable|url',
        ], [
            'staff_notes.required_if' => 'Catatan wajib diisi jika status Ditolak atau Minta Revisi.',
            'reply_document_path.required_if' => 'Tautan surat balasan wajib diisi jika status Disetujui.',
            'reply_document_path.url' => 'Input harus berupa tautan (URL) yang valid.',
        ]);

        $sewa->submission_status = $validated['submission_status'];
        $sewa->staff_notes = $validated['staff_notes'];

        if ($validated['submission_status'] === 'Disetujui') {
            $sewa->reply_document_path = $validated['reply_document_path'];
        } else {
            $sewa->reply_document_path = null;
        }

        $sewa->save();

        return redirect()->route('staffSewa.index')->with('success', 'Status pengajuan sewa berhasil diperbarui.');
    }
    

    
}
