<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // Tambahkan ini
use Illuminate\Support\Str; // Tambahkan ini

class PublicMeetingController extends Controller
{
    /**
     * Menampilkan formulir absensi berdasarkan slug rapat.
     */
    public function show($slug)
    {
        $meeting = Meeting::where('slug', $slug)->firstOrFail();

        // Cek apakah rapat masih aktif
        if (!$meeting->is_active) {
            return view('landing-menu.absensi.closed', compact('meeting'));
        }

        // Cek jika tanggal rapat sudah lewat (opsional, tergantung kebijakan)
        // if ($meeting->date < now()->toDateString()) { ... }

        return view('landing-menu.absensi.form', compact('meeting'));
    }

    /**
     * Menyimpan data absensi dari peserta.
     */
    public function store(Request $request, $slug)
    {
        $meeting = Meeting::where('slug', $slug)->firstOrFail();

        if (!$meeting->is_active) {
            return back()->with('error', 'Maaf, absensi untuk rapat ini sudah ditutup.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'phone' =>'required|string|max:20',
            'signature' => 'required', // Validasi tanda tangan harus ada
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'department.required' => 'Instansi/Unit Kerja wajib diisi.',
            'phone.required' => 'Nomor HP wajib diisi.', // Tambahkan pesan error
            'signature.required' => 'Tanda tangan wajib diisi.',
        ]);

        // Cek duplikasi
        $exists = Attendance::where('meeting_id', $meeting->id)
                            ->where('name', $validated['name'])
                            ->where('department', $validated['department'])
                            ->exists();

        if ($exists) {
            return back()->with('error', 'Anda sudah terdaftar dalam absensi rapat ini.');
        }

        // === PROSES PENYIMPANAN TANDA TANGAN ===
        $signaturePath = null;
        if ($request->filled('signature')) {
            // 1. Ambil data base64
            $image_64 = $request->input('signature'); 
            
            // 2. Bersihkan prefix data:image/png;base64,
            $extension = explode('/', explode(':', substr($image_64, 0, strpos($image_64, ';')))[1])[1];   
            $replace = substr($image_64, 0, strpos($image_64, ',')+1); 
            $image = str_replace($replace, '', $image_64); 
            $image = str_replace(' ', '+', $image); 

            // 3. Buat nama file unik
            $imageName = 'ttd_' . time() . '_' . Str::random(10) . '.' . $extension;

            // 4. Simpan ke storage (folder: public/signatures)
            Storage::disk('public')->put('signatures/' . $imageName, base64_decode($image));

            $signaturePath = 'signatures/' . $imageName;
        }

        Attendance::create([
            'meeting_id' => $meeting->id,
            'name' => $validated['name'],
            'department' => $validated['department'],
            'phone' => $validated['phone'],
            'signature' => $signaturePath, // Simpan path
        ]);

        return back()->with('success', 'Terima kasih! Kehadiran Anda telah tercatat.');
    }
}
