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

        return redirect()->route('kerja.index')->with('success', 'Pengajuan Izin Kerja berhasil dikirim.');
    }

    /**
     * Menampilkan detail dari satu izin kerja (untuk Staff).
     */
    public function show(WorkPermit $workPermit)
    {
        $this->authorize('view', $workPermit); // Asumsi ada Policy
        return view('user_staff2.perizinan_kerja.show', compact('workPermit'));
    }

    /**
     * Mengubah status izin kerja (untuk Staff).
     */
    public function updateStatus(Request $request, WorkPermit $workPermit)
    {
        $this->authorize('update', $workPermit); // Asumsi ada Policy

        $validated = $request->validate([
            'status' => 'required|in:Disetujui,Ditolak,Revisi Diperlukan',
            'staff_notes' => 'nullable|string',
        ]);

        $workPermit->update($validated);

        return redirect()->route('kerja.index')->with('success', 'Status pengajuan berhasil diperbarui.');
    }
}
