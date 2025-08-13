<?php

namespace App\Http\Controllers\Staff_User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\License;

class PerijinanUsahaController extends Controller
{
    protected $licenseTypes = [
        'ATM',
        'Kargo',
    ];
    /* ================== USER ROUTES ================== */
    public function store(Request $request)
    {
        $request->validate([
            'license_name' => 'required|string|max:255',
            'description'   => 'required|string',
            'license_type'   => 'required|string',
            'documents'     => 'required|file|mimes:pdf|max:2048',
        ], [
            'license_name.required' => 'Nama perizinan usaha wajib diisi.',
            'license_name.string'   => 'Nama perizinan usaha harus berupa teks.',
            'license_name.max'      => 'Nama perizinan usaha maksimal 255 karakter.',

            'description.required'   => 'Deskripsi perizinan usaha wajib diisi.',
            'description.string'     => 'Deskripsi harus berupa teks.',
            
            'license_type.required'   => 'Jenis perizinan usaha wajib dipilih.',
            'license_type.string'     => 'Jenis perizinan usaha tidak valid.',
            
            'documents.required'     => 'Dokumen pendukung wajib diunggah.',
            'documents.file'         => 'File dokumen tidak valid.',
            'documents.mimes'        => 'Dokumen harus berupa file dengan format: PDF',
            'documents.max'          => 'Ukuran dokumen maksimal 2MB.',

        ]);

        if($request->licence_type === 'Lainnya') {
            // Tambahkan validasi khusus untuk sewa lainnya
            $request->validate([
                'license_more' => 'required|string|max:150',
            ], [
                'license_more.required' => 'Jenis sewa lainnya wajib diunggah.',
                'license_more.max' => 'Jenis sewa maksimal 150 karakter.',
            ]);
        }


        // Simpan file
        $file = $request->file('documents');
        $filename = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('documents/license', $filename, 'public');

        // Simpan data license
        $license = License::create([
            'license_name' => $request->license_name,
            'license_type'   => $request->license_type,
            'license_more'   => $request->license_more ?? null,
            'description'   => $request->description,
            'documents'     => $filePath,
        ]);

        // Simpan ke pivot license_user
        $license->users()->attach(auth()->id(), [
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('perijinan.index')->with('success', 'Pengajuan perijinan usaha berhasil dikirim!');
    }



    public function create()
    {     
        $license_type = $this->licenseTypes;
        return view('user_staff2.perijinan.create', compact('license_type'));
    }

    public function destroy($id)
    {
        $license = License::findOrFail($id);

        // Hapus file dokumen jika ada
        $documentPath = public_path('uploads/' . $license->documents);
        if (file_exists($documentPath)) {
            unlink($documentPath);
        }

        // Hapus relasi user jika menggunakan pivot
        $license->users()->detach();

        // Hapus license
        $license->delete();

        return redirect()->route('perijinan.index')->with('success', 'Pengajuan berhasil dihapus.');    }

    public function indexUser()
    {
        $user = Auth::user();
        $licenses = $user->licenses()->latest()->get();
        return view('user_staff2.perijinan.index', compact('licenses'));    
    }


    /* ================== STAFF ROUTES ================== */
    public function index()
    {
        $licenses = License::with('users')->latest()->get();
        return view('user_staff2.perijinan.index', compact('licenses'));     
    }

    public function show($id)
    {
        $license = License::with('users')->findOrFail($id);
        return view('user_staff2.perijinan.show', compact('license'));
    }

    public function updateStatus(Request $request, License $license)
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

        $license->submission_status = $validated['submission_status'];
        $license->staff_notes = $validated['staff_notes'];

        if ($validated['submission_status'] === 'Disetujui') {
            $license->reply_document_path = $validated['reply_document_path'];
        } else {
            $license->reply_document_path = null;
        }

        $license->save();

        return redirect()->route('perijinan.staffIndex')->with('success', 'Status pengajuan ijin usaha berhasil diperbarui.');
    }
    
    
}
