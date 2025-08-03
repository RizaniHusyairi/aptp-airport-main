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
            'submission_status' => 'diajukan',
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

    public function approve($id)
    {
        $lelang = Lelang::findOrFail($id);
        $lelang->submission_status = 'disetujui';
        $lelang->save();

        return redirect()->back()->with('success', 'Pengajuan berhasil disetujui.');
    }

    public function reject($id)
    {
        $lelang = Lelang::findOrFail($id);
        $lelang->submission_status = 'ditolak';
        $lelang->save();

        return redirect()->back()->with('success', 'Pengajuan berhasil ditolak.');
    }
}