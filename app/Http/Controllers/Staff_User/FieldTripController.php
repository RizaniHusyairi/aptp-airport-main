<?php

namespace App\Http\Controllers\Staff_User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\Fieldtrip;

class FieldTripController extends Controller
{
    /* ================== USER ROUTES ================== */
    public function store(Request $request)
    {
        $request->validate([
            'fieldtrip_name' => 'required|string|max:255',
            'description'   => 'required|string',
            'fieldtrip_type'   => 'required|string',
            'documents'     => 'required|file|mimes:pdf|max:2048',
        ], [
            'fieldtrip_name.required' => 'Nama Fieldtrip wajib diisi.',
            'fieldtrip_name.string'   => 'Nama Fieldtrip harus berupa teks.',
            'fieldtrip_name.max'      => 'Nama Fieldtrip maksimal 255 karakter.',

            'description.required'   => 'Deskripsi Fieldtrip wajib diisi.',
            'description.string'     => 'Deskripsi harus berupa teks.',
            
            'fieldtrip_type.required'   => 'Jenis Fieldtrip wajib dipilih.',
            'fieldtrip_type.string'     => 'Jenis Fieldtrip tidak valid.',
            
            'documents.required'     => 'Dokumen pendukung wajib diunggah.',
            'documents.file'         => 'File dokumen tidak valid.',
            'documents.mimes'        => 'Dokumen harus berupa file dengan format: PDF',
            'documents.max'          => 'Ukuran dokumen maksimal 2MB.',

        ]);

        // Simpan file
        $file = $request->file('documents');
        $filename = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('documents/fieldtrip', $filename, 'public');

        // Simpan data license
        $fieldtrip = FieldTrip::create([
            'fieldtrip_name' => $request->fieldtrip_name,
            'fieldtrip_type'   => $request->fieldtrip_type,
            'description'   => $request->description,
            'documents'     => $filePath,
        ]);

        // Simpan ke pivot fieldtrip_user
        $fieldtrip->users()->attach(auth()->id(), [
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('fieldtrip.index')->with('success', 'Pengajuan fieldtrip berhasil dikirim!');
    }

    public function create()
    {
        return view('user_staff2.fieldtrip.create');
    }

    public function destroy($id)
    {
        $fieldtrip = FieldTrip::findOrFail($id);

        // Hapus file dokumen jika ada
        $documentPath = public_path('uploads/' . $fieldtrip->documents);
        if (file_exists($documentPath)) {
            unlink($documentPath);
        }

        // Hapus relasi user jika menggunakan pivot
        $fieldtrip->users()->detach();

        // Hapus fieldtrip
        $fieldtrip->delete();

        return redirect()->route('fieldtrip.index')->with('success', 'Pengajuan berhasil dihapus.');    }

    public function indexUser()
    {
        $user = Auth::user();
        $fieldtrips = $user->fieldtrips()->latest()->get();
        return view('user_staff2.fieldtrip.index', compact('fieldtrips'));    
    }

    /* ================== STAFF ROUTES ================== */
    public function index()
    {
        $fieldtrips = FieldTrip::with('users')->latest()->get();
        return view('user_staff2.fieldtrip.index', compact('fieldtrips'));     
    }

    public function show($id)
    {
        $fieldtrip = Fieldtrip::with('users')->findOrFail($id);
        return view('user_staff2.fieldtrip.show', compact('fieldtrip'));
    }

    public function updateStatus(Request $request, Fieldtrip $fieldtrip)
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

        $fieldtrip->submission_status = $validated['submission_status'];
        $fieldtrip->staff_notes = $validated['staff_notes'];

        if ($validated['submission_status'] === 'Disetujui') {
            $fieldtrip->reply_document_path = $validated['reply_document_path'];
        } else {
            $fieldtrip->reply_document_path = null;
        }

        $fieldtrip->save();

        return redirect()->route('fieldtrip.staffIndex')->with('success', 'Status pengajuan fieldtrip berhasil diperbarui.');
    }

}
