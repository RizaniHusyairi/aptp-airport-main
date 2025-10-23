<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\SparePartRequest;
use App\Models\SparePart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SparePartRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ambil semua permintaan, urutkan terbaru, dan eager load relasi
        $requests = SparePartRequest::with(['user', 'sparePart'])->latest()->get();
        return view('user_staff2.permintaan-suku-cadang.index', compact('requests'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Ambil daftar suku cadang untuk dropdown
        $spareParts = SparePart::orderBy('name')->get();
        return view('user_staff2.permintaan-suku-cadang.create', compact('spareParts'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'spare_part_id' => 'required|exists:spare_parts,id',
            'subject' => 'required|string|max:255',
            'follow_up_notes' => 'nullable|string',
            'memo_link' => 'required|url',
        ],[
            'spare_part_id.required' => 'Peralatan (suku cadang) wajib dipilih.',
            'subject.required' => 'Perihal wajib diisi.',
            'memo_link.required' => 'Link Nota Dinas wajib diisi.',
            'memo_link.url' => 'Link Nota Dinas harus berupa URL yang valid (contoh: https://...).',
        ]);

        SparePartRequest::create([
            'user_id' => Auth::id(), // ID Staff yang login
            'spare_part_id' => $validated['spare_part_id'],
            'subject' => $validated['subject'],
            'follow_up_notes' => $validated['follow_up_notes'],
            'memo_link' => $validated['memo_link'],
        ]);

        return redirect()->route('staff.spare-part-requests.index')->with('success', 'Permintaan suku cadang berhasil dibuat.');
    }

    /**
     * Remove the specified resource from storage.
     * (Optional: Tambahkan logika hapus jika diperlukan)
     */
    public function destroy(SparePartRequest $sparePartRequest)
    {
        $sparePartRequest->delete();
        return redirect()->route('staff.spare-part-requests.index')->with('success', 'Permintaan suku cadang berhasil dihapus.');
    }
}
