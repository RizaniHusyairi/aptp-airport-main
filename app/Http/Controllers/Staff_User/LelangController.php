<?php

namespace App\Http\Controllers\Staff_User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Lelang;
use Illuminate\Support\Facades\Storage;

class LelangController extends Controller
{
    

    public function indexuser()
    {
        $user = Auth::user();
        $lelangs = $user->lelangs()->latest()->get();
        return view('user_staff2.lelang.index', compact('lelangs'));
    }


    public function create()
    {
    
        return view('user_staff2.lelang.create');
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'lelang_type' => 'required|string',
            'description' => 'required|string',
            'documents' => 'required|file|mimes:pdf|max:2048',
        ];


        $messages = [
            'name.required' => 'Nama pengajuan wajib diisi.',
            'lelang_type.required' => 'Jenis Lelang wajib dipilih.',
            'description.required' => 'Deskripsi wajib diisi.',
            'documents.required' => 'Dokumen wajib diunggah.',
            'documents.mimes' => 'Dokumen harus berupa PDF.',
            'documents.max' => 'Ukuran dokumen maksimal 2MB.',
            
        ];

        $validated = $request->validate($rules, $messages);

        $file = $request->file('documents');
        $filename = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('documents/lelang', $filename, 'public');

        $data = [
            'name' => $validated['name'],
            'lelang_type' => $validated['lelang_type'],
            'description' => $validated['description'],
            'documents' => $filePath,
        ];


        $lelang = Lelang::create($data);

        $lelang->users()->attach(auth()->id(), [
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('lelang.index')
            ->with('success', 'Pengajuan lelang/beauty contest berhasil dikirim!');
    }

    

    public function destroy($id)
    {
        $lelang = Lelang::findOrFail($id);

        if ($lelang->documents && Storage::disk('public')->exists($lelang->documents)) {
            Storage::disk('public')->delete($lelang->documents);
        }

        if ($lelang->additional_documents && Storage::disk('public')->exists($lelang->additional_documents)) {
            Storage::disk('public')->delete($lelang->additional_documents);
        }

        $lelang->users()->detach();
        $lelang->delete();

        return redirect()->route('lelang.index')
            ->with('success', 'Pengajuan berhasil dihapus.');
    }


    //Staff
    
    public function index()
    {
        $lelangs = Lelang::with('users')->latest()->get();
        return view('user_staff2.lelang.index', compact('lelangs'));     
    }

    public function show($id)
    {
        $lelang = Lelang::with('users')->findOrFail($id);
        return view('user_staff2.lelang.show', compact('lelang'));
    }
    public function updateStatus(Request $request, Lelang $lelang)
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

        $lelang->submission_status = $validated['submission_status'];
        $lelang->staff_notes = $validated['staff_notes'];

        if ($validated['submission_status'] === 'Disetujui') {
            $lelang->reply_document_path = $validated['reply_document_path'];
        } else {
            $lelang->reply_document_path = null;
        }

        $lelang->save();

        return redirect()->route('lelang.staffIndex')->with('success', 'Status pengajuan lelang berhasil diperbarui.');
    }


}