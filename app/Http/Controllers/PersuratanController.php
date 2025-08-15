<?php

namespace App\Http\Controllers;

use App\Models\persuratan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PersuratanController extends Controller
{
    public function index()
    {
        // Tampilkan surat yang relevan: dibuat oleh user atau menunggu persetujuan user
        $user = Auth::user();
        $letters = persuratan::where('user_id', $user->id)
            ->orWhere('assigned_to_user_id', $user->id)
            ->latest()
            ->get();
            
        return view('admin2.persuratan.index', compact('letters'));
    }

    public function create()
    {
        return view('admin2.persuratan.create');
    }

    public function store(Request $request)
    {
        // Logika untuk menyimpan surat baru sebagai 'Draft'
        // ... (validasi, file upload, dll.)
        
        // Setelah disimpan, arahkan ke halaman detail untuk diajukan
        return redirect()->route('admin.persuratan.index')->with('success', 'Draf surat berhasil disimpan.');
    }

    public function show(persuratan $surat)
    {
        // Tampilkan detail surat, termasuk riwayat revisi
        $surat->load('revisions.user');
        return view('admin2.persuratan.show', compact('surat'));
    }

    public function approve(Request $request, persuratan $surat)
    {
        // Logika persetujuan berjenjang
        $user = Auth::user();
        // Anda perlu logika untuk menentukan siapa approver selanjutnya
        // Contoh sederhana:
        switch ($surat->status) {
            case 'Menunggu Persetujuan Kasi':
                $surat->status = 'Menunggu Persetujuan Kasubbag';
                // $surat->assigned_to_user_id = ID_KASUBBAG;
                break;
            case 'Menunggu Persetujuan Kasubbag':
                $surat->status = 'Menunggu Persetujuan Kabandara';
                // $surat->assigned_to_user_id = ID_KABANDARA;
                break;
            case 'Menunggu Persetujuan Kabandara':
                $surat->status = 'Disetujui';
                $surat->assigned_to_user_id = null;
                break;
        }
        $surat->save();
        return redirect()->route('admin.persuratan.index')->with('success', 'Surat berhasil disetujui dan diteruskan.');
    }

    public function reject(Request $request, persuratan $surat)
    {
        // Logika untuk meminta revisi
        $request->validate(['comments' => 'required|string']);

        $surat->revisions()->create([
            'user_id' => Auth::id(),
            'comments' => $request->comments,
            'previous_status' => $surat->status,
        ]);

        $surat->update([
            'status' => 'Revisi Diperlukan',
            'assigned_to_user_id' => $surat->user_id, // Kembalikan ke pembuat
        ]);
        
        return redirect()->route('admin.persuratan.show', $surat)->with('success', 'Catatan revisi telah dikirim.');
    }

}
