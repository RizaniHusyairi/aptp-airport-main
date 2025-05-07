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
            'view' => 'user_staff.sewa.ruang',
            'name' => 'Sewa Ruang',
            'validation' => [
                'area' => 'required|integer',
                'location' => 'required|string',
            ],
            'fields' => ['area', 'location'],
        ],
        'lahan' => [
            'view' => 'user_staff.sewa.lahan',
            'name' => 'Sewa Lahan',
            'validation' => [
                'area' => 'required|integer',
                'location' => 'required|string',
            ],
            'fields' => ['area', 'location'],
        ],
        'xray_kabin' => [
            'view' => 'user_staff.sewa.kabin',
            'name' => 'Xray Kabin',
            'validation' => [
                'quantity' => 'required|integer',
            ],
            'fields' => ['quantity'],
        ],
        'xray_kargo' => [
            'view' => 'user_staff.sewa.kargo',
            'name' => 'Xray Kargo',
            'validation' => [
                'quantity' => 'required|integer',
            ],
            'fields' => ['quantity'],
        ],
        'bus' => [
            'view' => 'user_staff.sewa.bus',
            'name' => 'Kendaraan Roda Empat',
            'validation' => [
                'quantity' => 'required|integer',
            ],
            'fields' => ['quantity'],
        ],
        'workshop' => [
            'view' => 'user_staff.sewa.workshop',
            'name' => 'Peralatan Workshop',
            'validation' => [
                'quantity' => 'required|integer',
            ],
            'fields' => ['quantity'],
        ],
        'reklame' => [
            'view' => 'user_staff.sewa.reklame',
            'name' => 'Penempatan Reklame',
            'validation' => [
                'design_file' => 'required|file|mimes:jpg,png|max:2048',
            ],
            'fields' => ['design_file'],
        ],
    ];

    /* ================== USER ROUTES ================== */


    public function index($type)
    {
        if (!array_key_exists($type, $this->rentalTypes)) {
            abort(404, 'Jenis sewa tidak valid.');
        }

        $user = Auth::user();
        $rentals = $user->rentals()->where('rental_type', $type)->latest()->get();
        $view = $this->rentalTypes[$type]['view'] . '.index';

        return view($view, compact('rentals'));
    }
    
    public function create($type)
    {
        if (!array_key_exists($type, $this->rentalTypes)) {
            abort(404, 'Jenis sewa tidak valid.');
        }

        $view = $this->rentalTypes[$type]['view'] . '.create';
        return view($view, [
            'rentalTypes' => $type,
        ]);
    }

    public function store(Request $request, $type)
    {
        if (!array_key_exists($type, $this->rentalTypes)) {
            abort(404, 'Jenis sewa tidak valid.');
        }

        // Validasi umum
        $rules = [
            'rental_name' => 'required|string|max:255',
            'description' => 'required|string',
            'documents' => 'required|file|mimes:pdf|max:2048',
        ];

        // Tambahkan validasi khusus berdasarkan jenis sewa
        $rules = array_merge($rules, $this->rentalTypes[$type]['validation']);

        $validated = $request->validate($rules, [
            'rental_name.required' => 'Nama sewa wajib diisi.',
            'rental_name.string' => 'Nama sewa harus berupa teks.',
            'rental_name.max' => 'Nama sewa maksimal 255 karakter.',
            'description.required' => 'Deskripsi sewa wajib diisi.',
            'description.string' => 'Deskripsi harus berupa teks.',
            'documents.required' => 'Dokumen pendukung wajib diunggah.',
            'documents.file' => 'File dokumen tidak valid.',
            'documents.mimes' => 'Dokumen harus berupa file dengan format: PDF',
            'documents.max' => 'Ukuran dokumen maksimal 2MB.',
            'area.required' => 'Luas wajib diisi.',
            'area.integer' => 'Luas harus berupa angka.',
            'location.required' => 'Lokasi wajib diisi.',
            'location.string' => 'Lokasi harus berupa teks.',
            'quantity.required' => 'Jumlah wajib diisi.',
            'quantity.integer' => 'Jumlah harus berupa angka.',
            'design_file.required' => 'File desain reklame wajib diunggah.',
            'design_file.mimes' => 'File desain harus berupa JPG atau PNG.',
            'design_file.max' => 'Ukuran file desain maksimal 2MB.',
        ]);

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
            'rental_type' => $type,
            'documents' => $filePath,
        ];

        // Tambahkan field khusus
        foreach ($this->rentalTypes[$type]['fields'] as $field) {
            if (isset($validated[$field])) {
                $rentalData[$field] = $validated[$field];
            }
        }
        if ($designFilePath) {
            $rentalData['design_file'] = $designFilePath;
        }

        // Simpan Rental
        $rental = Rental::create($rentalData);

        // Attach ke pivot rental_user
        Auth::user()->rentals()->attach($rental->id, [
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('sewa.index', ['type' => $type])
            ->with('success', "Pengajuan {$this->rentalTypes[$type]['name']} berhasil dikirim!");
    }

    public function destroy($type, $id)
    {
        if (!array_key_exists($type, $this->rentalTypes)) {
            abort(404, 'Jenis sewa tidak valid.');
        }

        $rental = Rental::findOrFail($id);

        // Hapus file dokumen jika ada
        if ($rental->documents && Storage::disk('public')->exists($rental->documents)) {
            Storage::disk('public')->delete($rental->documents);
        }
        // Hapus file desain jika ada
        if ($rental->design_file && Storage::disk('public')->exists($rental->design_file)) {
            Storage::disk('public')->delete($rental->design_file);
        }

        // Hapus relasi pivot
        $rental->users()->detach();

        // Hapus rental
        $rental->delete();

        return redirect()->route('sewa.index', ['type' => $type])
            ->with('success', 'Pengajuan berhasil dihapus.');
    }




    public function ruang()
    {
        $user = Auth::user();
        $rentals = $user->rentals()->latest()->get();
        return view('user_staff.sewa.ruang.index', compact('rentals'));
        
    }
    public function ruangCreate()
    {
        return view('user_staff.sewa.ruang.create', );
        
    }

    public function ruangStore(Request $request)
    {
        $validated = $request->validate([
            'rental_name' => 'required|string',
            'description' => 'required|string',
            'documents' => 'required|file|mimes:pdf|max:2048',
            'area' => 'required|integer',
            'location' => 'required|string',
        ]);

        $file = $request->file('documents');
        $filename = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('documents/rental', $filename, 'public');

        $rental = Rental::create([
            'rental_name' => $validated['rental_name'],
            'description' => $validated['description'],
            'rental_type' => 'ruang',
            'documents' => $filePath,
            'area' => $validated['area'],
            'location' => $validated['location'],
        ]);

        Auth::user()->rentals()->attach($rental->id);

        $rental->users()->attach(auth()->id(), [
            'created_at' => now(),
            'updated_at' => now(),
        ]);


        return redirect()->route('ruang.index')->with('success', 'Pengajuan sewa ruang berhasil dikirim!');
        // return redirect()->route('services.rental.room')->with('success', 'Pengajuan sewa ruang berhasil.');
    }

    public function ruangDestroy($id)
    {
        $rental = Rental::findOrFail($id);

        // Hapus file dokumen jika ada
        $documentPath = public_path('uploads/' . $rental->documents);
        if (file_exists($documentPath)) {
            unlink($documentPath);
        }

        // Hapus relasi user jika menggunakan pivot
        $rental->users()->detach();

        // Hapus rental
        $rental->delete();

        return redirect()->route('ruang.index')->with('success', 'Pengajuan berhasil dihapus.');    
    }


    public function lahanStore(Request $request)
    {
        $request->validate([
            'rental_name' => 'required|string|max:255',
            'description'   => 'required|string',
            'rental_type'   => 'required|string',
            'documents'     => 'required|file|mimes:pdf|max:2048',
        ], [
            'rental_name.required' => 'Nama sewa wajib diisi.',
            'rental_name.string'   => 'Nama sewa harus berupa teks.',
            'rental_name.max'      => 'Nama sewa maksimal 255 karakter.',

            'description.required'   => 'Deskripsi sewa wajib diisi.',
            'description.string'     => 'Deskripsi harus berupa teks.',
            
            'rental_type.required'   => 'Jenis sewa wajib dipilih.',
            'rental_type.string'     => 'Jenis sewa tidak valid.',
            
            'documents.required'     => 'Dokumen pendukung wajib diunggah.',
            'documents.file'         => 'File dokumen tidak valid.',
            'documents.mimes'        => 'Dokumen harus berupa file dengan format: PDF',
            'documents.max'          => 'Ukuran dokumen maksimal 2MB.',

        ]);

        // Simpan file
        $file = $request->file('documents');
        $filename = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('documents/rental', $filename, 'public');

        // Simpan data rental
        $rental = Rental::create([
            'rental_name' => $request->rental_name,
            'rental_type'   => $request->rental_type,
            'description'   => $request->description,
            'documents'     => $filePath,
        ]);

        // Simpan ke pivot rental_user
        $rental->users()->attach(auth()->id(), [
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('sewaLahan.index')->with('success', 'Pengajuan sewa lahan berhasil dikirim!');
    }

    public function lahan()
    {
        $user = Auth::user();
        $rentals = $user->rentals()->latest()->get();
        return view('user_staff.sewa.lahan.index', compact('rentals'));    
    }

    public function lahanCreate()
    {
        return view('user_staff.sewa.lahan.create');
    }

    public function lahanDestroy($id)
    {
        $rental = Rental::findOrFail($id);

        // Hapus file dokumen jika ada
        $documentPath = public_path('uploads/' . $rental->documents);
        if (file_exists($documentPath)) {
            unlink($documentPath);
        }

        // Hapus relasi user jika menggunakan pivot
        $rental->users()->detach();

        // Hapus rental
        $rental->delete();

        return redirect()->route('lahan.index')->with('success', 'Pengajuan berhasil dihapus.');    
    }

    public function kabin(){
        $user = Auth::user();
        $rentals = $user->rentals()->latest()->get();
        return view('user_staff.sewa.kabin.index', compact('rentals'));    
    }

    public function kabinCreate()
    {
        return view('user_staff.sewa.kabin.create');
    }

    public function kabinStore(Request $request)
    {
        $validated = $request->validate([
            'rental_name' => 'required|string',
            'description' => 'required|string',
            'documents' => 'required|file|mimes:pdf|max:2048',
            'quantity' => 'required|integer',
        ]);

        $file = $request->file('documents');
        $filename = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('documents/rental', $filename, 'public');

        $rental = Rental::create([
            'rental_name' => $validated['rental_name'],
            'description' => $validated['description'],
            'rental_type' => 'xray_kabin',
            'documents' => $filePath,
            'quantity' => $validated['quantity'],
        ]);

        Auth::user()->rentals()->attach($rental->id);

        return redirect()->route('kabin.index')->with('success', 'Pengajuan Sewa kabin berhasil dikirim!');
    }

    public function kabinDestroy($id)
    {
        $rental = Rental::findOrFail($id);

        // Hapus file dokumen jika ada
        $documentPath = public_path('uploads/' . $rental->documents);
        if (file_exists($documentPath)) {
            unlink($documentPath);
        }

        // Hapus relasi user jika menggunakan pivot
        $rental->users()->detach();

        // Hapus rental
        $rental->delete();

        return redirect()->route('kabin.index')->with('success', 'Pengajuan berhasil dihapus.');    
    }

    public function kargo(){
        $user = Auth::user();
        $rentals = $user->rentals()->latest()->get();
        return view('user_staff.sewa.kargo.index', compact('rentals'));    
    }

    public function kargoCreate()
    {
        return view('user_staff.sewa.kargo.create');
    }

    public function kargoStore(Request $request)
    {
        $validated = $request->validate([
            'rental_name' => 'required|string',
            'description' => 'required|string',
            'documents' => 'required|file|mimes:pdf|max:2048',
            'quantity' => 'required|integer',
        ]);

        $file = $request->file('documents');
        $filename = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('documents/rental', $filename, 'public');

        $rental = Rental::create([
            'rental_name' => $validated['rental_name'],
            'description' => $validated['description'],
            'rental_type' => 'kargo',
            'documents' => $filePath,
            'quantity' => $validated['quantity'],
        ]);

        Auth::user()->rentals()->attach($rental->id);

        return redirect()->route('kargo.index')->with('success', 'Pengajuan Sewa kargo berhasil dikirim!');
    }

    public function kargoDestroy($id)
    {
        $rental = Rental::findOrFail($id);

        // Hapus file dokumen jika ada
        $documentPath = public_path('uploads/' . $rental->documents);
        if (file_exists($documentPath)) {
            unlink($documentPath);
        }

        // Hapus relasi user jika menggunakan pivot
        $rental->users()->detach();

        // Hapus rental
        $rental->delete();

        return redirect()->route('kargo.index')->with('success', 'Pengajuan berhasil dihapus.');    
    }

    public function bus(){
        $user = Auth::user();
        $rentals = $user->rentals()->latest()->get();
        return view('user_staff.sewa.bus.index', compact('rentals'));    
    }

    public function busCreate()
    {
        return view('user_staff.sewa.bus.create');
    }   

    public function busStore(Request $request)
    {
        $validated = $request->validate([
            'rental_name' => 'required|string',
            'description' => 'required|string',
            'documents' => 'required|file|mimes:pdf|max:2048',
            'quantity' => 'required|integer',
        ]);

        $file = $request->file('documents');
        $filename = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('documents/rental', $filename, 'public');

        $rental = Rental::create([
            'rental_name' => $validated['rental_name'],
            'description' => $validated['description'],
            'rental_type' => 'bus',
            'documents' => $filePath,
            'quantity' => $validated['quantity'],
        ]);

        Auth::user()->rentals()->attach($rental->id);

        return redirect()->route('bus.index')->with('success', 'Pengajuan Sewa bus berhasil dikirim!');
    }

    public function busDestroy($id)
    {
        $rental = Rental::findOrFail($id);

        // Hapus file dokumen jika ada
        $documentPath = public_path('uploads/' . $rental->documents);
        if (file_exists($documentPath)) {
            unlink($documentPath);
        }

        // Hapus relasi user jika menggunakan pivot
        $rental->users()->detach();

        // Hapus rental
        $rental->delete();

        return redirect()->route('bus.index')->with('success', 'Pengajuan berhasil dihapus.');    
    }

    public function workshop(){
        $user = Auth::user();
        $rentals = $user->rentals()->latest()->get();
        return view('user_staff.sewa.workshop.index', compact('rentals'));    
    }
    public function workshopCreate()
    {
        return view('user_staff.sewa.workshop.create');
    }

    public function workshopStore(Request $request)
    {
        $validated = $request->validate([
            'rental_name' => 'required|string',
            'description' => 'required|string',
            'documents' => 'required|file|mimes:pdf|max:2048',
            'quantity' => 'required|integer',
        ]);

        $file = $request->file('documents');
        $filename = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('documents/rental', $filename, 'public');

        $rental = Rental::create([
            'rental_name' => $validated['rental_name'],
            'description' => $validated['description'],
            'rental_type' => 'workshop',
            'documents' => $filePath,
            'quantity' => $validated['quantity'],
        ]);

        Auth::user()->rentals()->attach($rental->id);

        return redirect()->route('workshop.index')->with('success', 'Pengajuan Sewa workshop berhasil dikirim!');
    }

    public function workshopDestroy($id)
    {
        $rental = Rental::findOrFail($id);

        // Hapus file dokumen jika ada
        $documentPath = public_path('uploads/' . $rental->documents);
        if (file_exists($documentPath)) {
            unlink($documentPath);
        }

        // Hapus relasi user jika menggunakan pivot
        $rental->users()->detach();

        // Hapus rental
        $rental->delete();

        return redirect()->route('workshop.index')->with('success', 'Pengajuan berhasil dihapus.');    
    }

    public function reklame(){
        $user = Auth::user();
        $rentals = $user->rentals()->latest()->get();
        return view('user_staff.sewa.reklame.index', compact('rentals'));    
    }
    public function reklameCreate()
    {
        return view('user_staff.sewa.reklame.create');
    }
    public function reklameStore(Request $request)
    {
        $validated = $request->validate([
            'rental_name' => 'required|string',
            'description' => 'required|string',
            'documents' => 'required|file|mimes:pdf|max:2048',
            'design_file' => 'required|file|mimes:jpg,png|max:2048',
        ]);

        $file = $request->file('documents');
        $filename = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('documents/rental', $filename, 'public');

        $designFile = $request->file('design_file');
        $designFilename = time() . '_' . $designFile->getClientOriginalName();
        $designFilePath = $designFile->storeAs('documents/rental', $designFilename, 'public');

        $rental = Rental::create([
            'rental_name' => $validated['rental_name'],
            'description' => $validated['description'],
            'rental_type' => 'reklame',
            'documents' => $filePath,
            'design_file' => $designFilePath,
        ]);

        Auth::user()->rentals()->attach($rental->id);

        return redirect()->route('reklame.index')->with('success', 'Pengajuan Sewa reklame berhasil dikirim!');
    }

    


    /* ================== STAFF ROUTES ================== */

    public function indexStaff($type)
    {
        if (!array_key_exists($type, $this->rentalTypes)) {
            abort(404, 'Jenis sewa tidak valid.');
        }

        $rentals = Rental::with('users')->where('rental_type', $type)->latest()->get();
        $view = $this->rentalTypes[$type]['view'] . '.index';

        return view($view, compact('rentals'));
    }

    public function show($type, $id)
    {
        if (!array_key_exists($type, $this->rentalTypes)) {
            abort(404, 'Jenis sewa tidak valid.');
        }

        $rental = Rental::with('users')->findOrFail($id);
        $view = $this->rentalTypes[$type]['view'] . '.show';

        return view($view, compact('rental'));
    }


    public function ruangStaff()
    {
        $rentals = Rental::with('users')->latest()->get();
        return view('user_staff.sewa.ruang.index', compact('rentals'));     
    }
    
    public function ruangShow($id)
    {
        $rental = Rental::with('users')->findOrFail($id);
        return view('user_staff.sewa.ruang.show', compact('rental'));
    }
    
    public function lahanStaff()
    {
        $rentals = Rental::with('users')->latest()->get();
        return view('user_staff.sewa.lahan.index', compact('rentals'));     
    }
    
    public function lahanShow($id)
    {
        $rental = Rental::with('users')->findOrFail($id);
        return view('user_staff.sewa.lahan.show', compact('rental'));
    }
    
    public function kargoStaff()
    {
        $rentals = Rental::with('users')->latest()->get();
        return view('user_staff.sewa.kargo.index', compact('rentals'));     
    }
    
    public function kargoShow($id)
    {
        $rental = Rental::with('users')->findOrFail($id);
        return view('user_staff.sewa.kargo.show', compact('rental'));
    }

    public function kabinStaff()
    {
        $rentals = Rental::with('users')->latest()->get();
        return view('user_staff.sewa.kabin.index', compact('rentals'));     
    }

    public function kabinShow($id)
    {
        $rental = Rental::with('users')->findOrFail($id);
        return view('user_staff.sewa.kabin.show', compact('rental'));
    }

    public function busStaff()
    {
        $rentals = Rental::with('users')->latest()->get();
        return view('user_staff.sewa.bus.index', compact('rentals'));     
    }

    public function busShow($id)
    {
        $rental = Rental::with('users')->findOrFail($id);
        return view('user_staff.sewa.bus.show', compact('rental'));
    }
    public function workshopStaff()
    {
        $rentals = Rental::with('users')->latest()->get();
        return view('user_staff.sewa.workshop.index', compact('rentals'));     
    }
    public function workshopShow($id)
    {
        $rental = Rental::with('users')->findOrFail($id);
        return view('user_staff.sewa.workshop.show', compact('rental'));
    }
    public function reklameStaff()
    {
        $rentals = Rental::with('users')->latest()->get();
        return view('user_staff.sewa.reklame.index', compact('rentals'));     
    }
    public function reklameShow($id)
    {
        $rental = Rental::with('users')->findOrFail($id);
        return view('user_staff.sewa.reklame.show', compact('rental'));
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
