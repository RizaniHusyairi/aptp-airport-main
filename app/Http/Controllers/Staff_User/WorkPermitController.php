<?php

namespace App\Http\Controllers\Staff_User;

use App\Http\Controllers\Controller;
use App\Models\WorkPermit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class WorkPermitController extends Controller
{
    /**
     * Menampilkan daftar izin kerja sesuai peran pengguna.
     */
    public function index()
    {
        $user = Auth::user();
        if ($user->is_staff) {
            // Staff melihat semua pengajuan
            $workPermits = WorkPermit::with('user')->latest()->get();
        } else {
            // Pengaju hanya melihat pengajuan miliknya
            $workPermits = $user->workPermits()->latest()->get();
        }
        return view('user_staff2.perizinan_kerja.index', compact('workPermits'));
    }

    /**
     * Menampilkan form untuk membuat izin kerja baru.
     */
    public function create()
    {
        return view('user_staff2.perizinan_kerja.create');
    }

    /**
     * Menyimpan izin kerja baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'work_type' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'description' => 'required|string',
            'docs' => 'required|array|min:1',
            'docs.*' => 'required|file|mimes:pdf,jpg,png|max:2048',
        ]);

        $documentPaths = [];
        if ($request->hasFile('docs')) {
            foreach ($request->file('docs') as $file) {
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('documents/work_permits', $filename, 'public');
                $documentPaths[] = $path;
            }
        }

        Auth::user()->workPermits()->create([
            'work_type' => $validated['work_type'],
            'location' => $validated['location'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'description' => $validated['description'],
            'documents' => $documentPaths,
            'status' => 'Diajukan',
        ]);

        return redirect()->route('kerja.userindex')->with('success', 'Pengajuan Izin Kerja berhasil dikirim.');
    }

    /**
     * Menampilkan detail dari satu izin kerja (untuk Staff).
     */
    public function show(WorkPermit $workPermit)
    {
        return view('user_staff2.perizinan_kerja.show', compact('workPermit'));
    }
    public function userShow(WorkPermit $workPermit)
    {
        return view('user_staff2.perizinan_kerja.show', compact('workPermit'));
    }

    /**
     * Mengubah status izin kerja (untuk Staff).
     */
    public function updateStatus(Request $request, WorkPermit $workPermit)
    {

        $validated = $request->validate([
            'status' => 'required|in:Disetujui,Ditolak,Revisi Diperlukan',
            'staff_notes' => 'required_if:status,Ditolak,Revisi Diperlukan|nullable|string',
            'reply_document_path' => 'required_if:status,Disetujui|nullable|url',
        ], [
            'staff_notes.required_if' => 'Catatan wajib diisi jika status Ditolak atau Minta Revisi.',
            'reply_document_path.required_if' => 'Tautan surat balasan wajib diisi jika status Disetujui.',
            'reply_document_path.url' => 'Input harus berupa tautan (URL) yang valid.',
        ]);
        
        $workPermit->status = $validated['status'];
        $workPermit->staff_notes = $validated['staff_notes'];
        
        
        if ($validated['status'] === 'Disetujui') {
            $workPermit->reply_document_path = $validated['reply_document_path'];
        } else {
            // Kosongkan link jika statusnya bukan disetujui
            $workPermit->reply_document_path = null; 
        }
        $workPermit->save();


        return redirect()->route('kerja.index')->with('success', 'Status pengajuan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $work = WorkPermit::findOrFail($id);

        // Hapus file dokumen jika ada
        // Lakukan perulangan pada array dokumen dan hapus satu per satu.
        if ($work->documents) {
            foreach ($work->documents as $docPath) {
                if (Storage::disk('public')->exists($docPath)) {
                    Storage::disk('public')->delete($docPath);
                }
            }
        }

        // Hapus record dari database
        $work->delete();
        

        // Hapus relasi user jika menggunakan pivot
        
        return redirect()->route('kerja.userindex')->with('success', 'Pengajuan izin kerja berhasil dihapus.');
    }
}
