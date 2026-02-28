<?php

namespace App\Http\Controllers\Staff_User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\Ad;
use Illuminate\Support\Facades\Storage;

class PengiklananController extends Controller
{
    /* ================== USER ROUTES ================== */
    public function store(Request $request)
    {
        $request->validate([
            'ad_name' => 'required|string|max:255',
            'description'   => 'required|string',
            'ad_type'   => 'required|string',
            'documents'     => 'required',
            'documents.*'   => 'file|mimes:pdf,doc,docx|max:2048',
        ], [
            'ad_name.required' => 'Nama Pengiklanan wajib diisi.',
            'ad_name.string'   => 'Nama Pengiklanan harus berupa teks.',
            'ad_name.max'      => 'Nama Pengiklanan maksimal 255 karakter.',

            'description.required'   => 'Deskripsi Pengiklanan wajib diisi.',
            'description.string'     => 'Deskripsi harus berupa teks.',
            
            'ad_type.required'   => 'Jenis Pengiklanan wajib dipilih.',
            'ad_type.string'     => 'Jenis Pengiklanan tidak valid.',
            
            'documents.required'     => 'Dokumen pendukung wajib diunggah.',
            'documents.*.file'         => 'File dokumen tidak valid.',
            'documents.*.mimes'        => 'Dokumen harus berupa file dengan format: PDF, DOC, DOCX',
            'documents.*.max'          => 'Ukuran dokumen maksimal 2MB.',

        ]);

        $documentPaths = [];

        // Simpan file
        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $file) {
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('documents/ads', $filename, 'public');
                $documentPaths[] = $path; 
            }
        }

        // Simpan data license
        $ad = Ad::create([
            'user_id' => Auth::id(), // <--- Tambahkan ini
            'ad_name' => $request->ad_name,
            'ad_type'   => $request->ad_type,
            'description'   => $request->description,
            'documents'     => $documentPaths,
        ]);


        return redirect()->route('pengiklanan.index')->with('success', 'Pengajuan pengiklanan berhasil dikirim!');
    }

    public function create()
    {
        return view('user_staff2.pengiklanan.create');
    }

    public function destroy($id)
    {
        $ad = Ad::findOrFail($id);

        if ($ad->documents) {
            foreach ($ad->documents as $path) {
                if (Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->delete($path);
                }
            }
        }

        $ad->delete();

        return redirect()->route('pengiklanan.index')->with('success', 'Pengajuan berhasil dihapus.');    }

    public function indexUser()
    {
        $user = Auth::user();
        $ads = $user->ads()->latest()->get();
        return view('user_staff2.pengiklanan.index', compact('ads'));    
    }

    /* ================== STAFF ROUTES ================== */
    public function index()
    {
        $ads = Ad::with('user')->latest()->get();
        return view('user_staff2.pengiklanan.index', compact('ads'));     
    }

    public function show($id)
    {
        $ad = Ad::with('user')->findOrFail($id);
        return view('user_staff2.pengiklanan.show', compact('ad'));
    }

    public function updateStatus(Request $request, Ad $ad)
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

        $ad->submission_status = $validated['submission_status'];
        $ad->staff_notes = $validated['staff_notes'];

        if ($validated['submission_status'] === 'Disetujui') {
            $ad->reply_document_path = $validated['reply_document_path'];
        } else {
            $ad->reply_document_path = null;
        }

        $ad->save();

        return redirect()->route('perijinan.staffIndex')->with('success', 'Status pengajuan iklan berhasil diperbarui.');
    }

    public function approve($id)
    {
        $ad = Ad::findOrFail($id);
        $ad->submission_status = 'disetujui';
        $ad->save();

        return redirect()->back()->with('success', 'Pengajuan berhasil disetujui.');
    }

    public function reject($id)
    {
        $ad = Ad::findOrFail($id);
        $ad->submission_status = 'ditolak';
        $ad->save();

        return redirect()->back()->with('success', 'Pengajuan berhasil ditolak.');
    }
}
