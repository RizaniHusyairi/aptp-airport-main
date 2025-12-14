<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Meeting;
use App\Models\Attendance; // <<< Import Model Attendance
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; // <<< Import Storage
use Barryvdh\DomPDF\Facade\Pdf; 

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
            'organizer_nip' => 'required|string|max:50',

        ], [
            'title.required' => 'Judul rapat wajib diisi.',
            'date.required' => 'Tanggal pelaksanaan wajib diisi.',
            'start_time.required' => 'Jam mulai wajib diisi.',
            'location.required' => 'Lokasi rapat wajib diisi.',
            'organizer.required' => 'Pimpinan/Penyelenggara wajib diisi.',
            'organizer_nip.required' => 'NIP Penyelenggara wajib diisi.',
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
            'organizer_nip' => $validated['organizer_nip'],
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

    /**
     * === METHOD BARU: Export PDF Daftar Hadir ===
     */
    public function exportPdf(Meeting $meeting)
    {
        // Ambil data peserta
        $meeting->load('attendances');

        // Render PDF
        $pdf = PDF::loadView('user_staff2.rapat.attendance_pdf', [
            'meeting' => $meeting,
            'attendances' => $meeting->attendances
        ]);

        // Set ukuran kertas A4 Portrait
        $pdf->setPaper('a4', 'portrait');

        // Nama file yang rapi
        $fileName = 'Daftar-Hadir-' . Str::slug($meeting->title) . '-' . $meeting->date->format('Y-m-d') . '.pdf';

        return $pdf->download($fileName);
    }

    /**
     * === METHOD BARU: Tampilkan Form Edit ===
     */
    public function edit(Meeting $meeting)
    {
        return view('user_staff2.rapat.edit', compact('meeting'));
    }

    /**
     * === METHOD BARU: Update Data Rapat ===
     */
    public function update(Request $request, Meeting $meeting)
    {
        // Tambahkan pesan custom bahasa Indonesia di parameter ke-3
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'date' => 'required|date',
            'start_time' => 'required',
            'location' => 'required|string|max:255',
            'organizer' => 'required|string|max:255',
            'organizer_nip' => 'required|string|max:50',
        ], [
            'title.required' => 'Judul rapat wajib diisi.',
            'date.required' => 'Tanggal pelaksanaan wajib diisi.',
            'start_time.required' => 'Jam mulai wajib diisi.',
            'location.required' => 'Lokasi rapat wajib diisi.',
            'organizer.required' => 'Pimpinan/Penyelenggara wajib diisi.',
            'organizer_nip.required' => 'NIP Penyelenggara wajib diisi.',
        ]);

        $meeting->update([
            'title' => $validated['title'],
            'date' => $validated['date'],
            'start_time' => $validated['start_time'],
            'location' => $validated['location'],
            'organizer' => $validated['organizer'],
            'organizer_nip' => $validated['organizer_nip'],
        ]);

        return redirect()->route('staff.meetings.index')->with('success', 'Data rapat berhasil diperbarui.');
    }

    /**
     * === METHOD BARU: Hapus Rapat ===
     */
    public function destroy(Meeting $meeting)
    {
        // Data absensi terkait akan otomatis terhapus karena onDelete('cascade') di database
        $meeting->delete();
        return redirect()->route('staff.meetings.index')->with('success', 'Agenda rapat berhasil dihapus.');
    }

    /**
     * === METHOD BARU: Hapus Peserta Absen ===
     */
    public function destroyAttendance(Attendance $attendance)
    {
        // Hapus file tanda tangan jika ada
        // if ($attendance->signature && Storage::disk('public')->exists($attendance->signature)) {
        //     Storage::disk('public')->delete($attendance->signature);
        // }

        if ($attendance->signature) {
            $documentPath = public_path('uploads/signatures/' . basename($attendance->signature));
            if (file_exists($documentPath)) {
                unlink($documentPath);
            }
        }

        $attendance->delete();

        return back()->with('success', 'Data peserta berhasil dihapus.');
    }
}