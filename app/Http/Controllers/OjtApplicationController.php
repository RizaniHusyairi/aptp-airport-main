<?php

namespace App\Http\Controllers;

use App\Models\OjtStudent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class OjtApplicationController extends Controller
{
    /**
     * Halaman Dashboard Pengajuan Saya (User)
     */
    public function index()
    {
        // User hanya melihat pengajuannya sendiri
        $applications = OjtStudent::where('user_id', Auth::id())->latest()->get();
        return view('user.ojt.index', compact('applications'));
    }

    public function show($id)
    {
        // Pastikan hanya bisa melihat data milik sendiri
        $application = OjtStudent::where('user_id', Auth::id())->findOrFail($id);
        
        return view('user.ojt.show', compact('application'));
    }

    /**
     * Form Pengajuan Baru
     */
    public function create()
    {
        $units = [
            'Kepegawaian', 'Tata Usaha', 'AAB', 'Keuangan', 'Jasa', 'Avsec', 
            'Bendahara', 'Bangland', 'AMC', 'Data & Informasi', 'Elband', 
            'Pengelola Informasi', 'BMN', 'Listrik', 'Humas', 'PKP-PK'
        ];
        return view('user.ojt.create', compact('units'));
    }

    /**
     * Simpan Pengajuan
     */
    public function store(Request $request)
    {
        // Validasi sama seperti sebelumnya
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'id_number' => 'required|string|max:50',
            'birth_place' => 'required|string',
            'birth_date' => 'required|date',
            'address' => 'required|string',
            'institution' => 'required|string',
            'major' => 'required|string',
            'duration' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'phone_number' => 'required|string',
            'supervisors' => 'required|array|min:1',
            'supervisors.*' => 'required|string',
            'work_units' => 'required|array|min:1',
            'identity_card' => 'required|image|max:2048',
            'photo' => 'required|image|max:2048',
        ]);

        $idPath = $request->file('identity_card')->store('ojt_docs/identity', 'public');
        $photoPath = $request->file('photo')->store('ojt_docs/photos', 'public');

        OjtStudent::create([
            'user_id' => Auth::id(), // Link ke akun login
            'status' => 'Menunggu Verifikasi',
            'name' => $validated['name'],
            'id_number' => $validated['id_number'],
            'birth_place' => $validated['birth_place'],
            'birth_date' => $validated['birth_date'],
            'address' => $validated['address'],
            'institution' => $validated['institution'],
            'major' => $validated['major'],
            'duration' => $validated['duration'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'supervisors' => $validated['supervisors'],
            'work_units' => $validated['work_units'],
            'phone_number' => $validated['phone_number'],
            'identity_card_path' => $idPath,
            'photo_path' => $photoPath,
        ]);

        return redirect()->route('user.ojt.index')->with('success', 'Pengajuan sertifikat berhasil dikirim. Mohon tunggu verifikasi staff.');
    }
}