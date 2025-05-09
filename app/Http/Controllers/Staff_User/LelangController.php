<?php

namespace App\Http\Controllers\Staff_User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Lelang;
use Illuminate\Support\Facades\Storage;

class LelangController extends Controller
{
    protected $lelangTypes = [
        'Lelang Aset',
        'Beauty Contest',
    ];

    public function indexuser()
    {
        $user = Auth::user();
        $lelangs = $user->lelangs()->latest()->get();
        return view('user_staff.lelang.index', compact('lelangs'));
    }


    public function create()
    {
        $lelang_type = $this->lelangTypes;
        return view('user_staff.lelang.create', compact('lelang_type'));
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'lelang_type' => 'required|in:' . implode(',', $this->lelangTypes),
            'description' => 'required|string',
            'documents' => 'required|file|mimes:pdf|max:2048',
        ];

        if ($request->lelang_type === 'Beauty Contest') {
            $rules['additional_documents'] = 'required|file|mimes:pdf|max:2048';
        } else {
            $rules['additional_documents'] = 'nullable|file|mimes:pdf|max:2048';
        }

        $messages = [
            'name.required' => 'Nama pengajuan wajib diisi.',
            'lelang_type.required' => 'Jenis pengajuan wajib dipilih.',
            'lelang_type.in' => 'Jenis pengajuan tidak valid.',
            'description.required' => 'Deskripsi wajib diisi.',
            'documents.required' => 'Dokumen wajib diunggah.',
            'documents.mimes' => 'Dokumen harus berupa PDF.',
            'documents.max' => 'Ukuran dokumen maksimal 2MB.',
            'additional_documents.required' => 'Dokumen tambahan wajib diunggah untuk Beauty Contest.',
            'additional_documents.mimes' => 'Dokumen tambahan harus berupa PDF.',
            'additional_documents.max' => 'Ukuran dokumen tambahan maksimal 2MB.',
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

        if ($request->hasFile('additional_documents')) {
            $additionalFile = $request->file('additional_documents');
            $additionalFilename = time() . '_additional_' . $additionalFile->getClientOriginalName();
            $data['additional_documents'] = $additionalFile->storeAs('documents/lelang', $additionalFilename, 'public');
        }

        $lelang = Lelang::create($data);

        Auth::user()->lelangs()->attach($lelang->id, [
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('lelang.index')
            ->with('success', 'Pengajuan lelang/beauty contest berhasil dikirim!');
    }

    public function edit($id)
    {
        $lelang = Lelang::findOrFail($id);
        $lelang_type = $this->lelangTypes;
        return view('user_staff.lelang.create', compact('lelang', 'lelang_type'));
    }

    public function update(Request $request, $id)
    {
        $lelang = Lelang::findOrFail($id);

        $rules = [
            'name' => 'required|string|max:255',
            'lelang_type' => 'required|in:' . implode(',', $this->lelangTypes),
            'description' => 'required|string',
            'documents' => 'nullable|file|mimes:pdf|max:2048',
        ];

        if ($request->lelang_type === 'Beauty Contest') {
            $rules['additional_documents'] = 'nullable|file|mimes:pdf|max:2048';
        }

        $messages = [
            'name.required' => 'Nama pengajuan wajib diisi.',
            'lelang_type.required' => 'Jenis pengajuan wajib dipilih.',
            'lelang_type.in' => 'Jenis pengajuan tidak valid.',
            'description.required' => 'Deskripsi wajib diisi.',
            'documents.mimes' => 'Dokumen harus berupa PDF.',
            'documents.max' => 'Ukuran dokumen maksimal 2MB.',
            'additional_documents.mimes' => 'Dokumen tambahan harus berupa PDF.',
            'additional_documents.max' => 'Ukuran dokumen tambahan maksimal 2MB.',
        ];

        $validated = $request->validate($rules, $messages);

        $data = [
            'name' => $validated['name'],
            'lelang_type' => $validated['lelang_type'],
            'description' => $validated['description'],
        ];

        if ($request->hasFile('documents')) {
            if ($lelang->documents && Storage::disk('public')->exists($lelang->documents)) {
                Storage::disk('public')->delete($lelang->documents);
            }
            $file = $request->file('documents');
            $filename = time() . '_' . $file->getClientOriginalName();
            $data['documents'] = $file->storeAs('documents/lelang', $filename, 'public');
        }

        if ($request->hasFile('additional_documents')) {
            if ($lelang->additional_documents && Storage::disk('public')->exists($lelang->additional_documents)) {
                Storage::disk('public')->delete($lelang->additional_documents);
            }
            $additionalFile = $request->file('additional_documents');
            $additionalFilename = time() . '_additional_' . $additionalFile->getClientOriginalName();
            $data['additional_documents'] = $additionalFile->storeAs('documents/lelang', $additionalFilename, 'public');
        }

        $lelang->update($data);

        return redirect()->route('lelang.index')
            ->with('success', 'Pengajuan lelang/beauty contest berhasil diperbarui!');
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
        return view('user_staff.lelang.index', compact('lelangs'));     
    }

    public function show($id)
    {
        $lelang = Lelang::with('users')->findOrFail($id);
        return view('user_staff.lelang.show', compact('lelang'));
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