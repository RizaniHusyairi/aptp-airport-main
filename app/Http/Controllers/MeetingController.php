<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Meeting;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class MeetingController extends Controller
{
    /**
     * Menampilkan daftar rapat.
     */
    public function index()
    {
        $meetings = Meeting::withCount('attendances')->latest('date')->get();
        return view('user_staff2.rapat.index', compact('meetings')); 
    }

    /**
     * Menampilkan formulir tambah rapat.
     */
    public function create()
    {
        return view('user_staff2.rapat.create');
    }

    /**
     * Menyimpan rapat baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'date' => 'required|date',
            'start_time' => 'required',
            'location' => 'required|string|max:255',
            'organizer' => 'required|string|max:255',
        ], [
            'title.required' => 'Judul rapat wajib diisi.',
            'date.required' => 'Tanggal pelaksanaan wajib diisi.',
            'start_time.required' => 'Jam mulai wajib diisi.',
            'location.required' => 'Lokasi rapat wajib diisi.',
            'organizer.required' => 'Pimpinan/Penyelenggara wajib diisi.',
        ]);

        // Buat Slug Unik (Judul + Random String)
        $slug = Str::slug($validated['title']) . '-' . Str::random(6);

        Meeting::create([
            'user_id' => Auth::id(), // Staf pembuat
            'title' => $validated['title'],
            'slug' => $slug,
            'date' => $validated['date'],
            'start_time' => $validated['start_time'],
            'location' => $validated['location'],
            'organizer' => $validated['organizer'],
            'is_active' => true,
        ]);

        return redirect()->route('staff.meetings.index')->with('success', 'Rapat berhasil dibuat. Link absensi siap digunakan.');
    }

    /**
     * Menampilkan detail rapat, QR Code, dan daftar peserta.
     */
    public function show(Meeting $meeting)
    {
        // Ambil daftar hadir
        $meeting->load('attendances');
        
        // URL Publik untuk QR Code
        $publicUrl = route('public.absensi.show', $meeting->slug);

        return view('user_staff2.rapat.show', compact('meeting', 'publicUrl'));
    }

    /**
     * Membuka/Menutup Absensi
     */
    public function toggleStatus(Meeting $meeting)
    {
        $meeting->is_active = !$meeting->is_active;
        $meeting->save();
        
        $status = $meeting->is_active ? 'dibuka' : 'ditutup';
        return back()->with('success', "Absensi berhasil $status.");
    }
}