<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ExtendAdvance;
use App\Models\ExtendAdvanceSetting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use PDF; // Pastikan package baris ini sudah ada

class ExtendAdvanceController extends Controller
{
    /* ================== USER (PENGAJU) ROUTES ================== */

    public function index()
    {
        $submissions = ExtendAdvance::where('user_id', Auth::id())
            ->latest()
            ->get();
        return view('user_staff2.extend-advance.index', compact('submissions'));
    }

    public function create()
    {
        // <<< AMBIL TEKS PERNYATAAN TERBARU DARI PENGATURAN >>>
        $statementText = ExtendAdvanceSetting::where('key', 'statement_notes')->first()->value 
            ?? 'Teks pernyataan default belum diatur oleh administrator.';

        return view('user_staff2.extend-advance.create', compact('statementText'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'operator' => 'required|string|max:255',
            'aircraft_type' => 'required|string|max:255',
            'registration_and_flight_number' => 'required|string|max:255',
            'flight_date' => 'required|date|after_or_equal:today',
            'eobt' => 'required|date_format:H:i',
            'aobt' => 'required|date_format:H:i',
            'route' => 'required|string|max:255',
            'take_off_alternate' => 'nullable|string|max:255',
            'purpose_of_flight' => 'required|string|max:255',
            'pic_name' => 'required|string|max:255',
        ], [
            'flight_date.after_or_equal' => 'Tanggal penerbangan tidak boleh tanggal yang sudah lewat.',
            'eobt.date_format' => 'Format Jam Keberangkatan tidak valid (contoh: 14:30).',
            'aobt.date_format' => 'Format Jam Kedatangan tidak valid (contoh: 15:00).',
        ]);

        // <<< AMBIL TEKS PERNYATAAN SAAT INI UNTUK DISIMPAN >>>
        $currentStatement = ExtendAdvanceSetting::where('key', 'statement_notes')->first()->value 
            ?? 'Teks pernyataan tidak ditemukan saat pengajuan.';


        $submission = new ExtendAdvance($validated);
        $submission->user_id = Auth::id();
        $submission->statement_notes = $currentStatement;
        $submission->submission_status = 'Menunggu Dokumen Ditandatangani'; 
        $submission->save();

        // Alihkan ke halaman detail agar user bisa langsung ekspor PDF
        return redirect()->route('extend-advance.userShow', $submission->id)
            ->with('success', 'Pengajuan awal berhasil disimpan. Silakan ekspor PDF untuk ditandatangani.');
        
    }

    /**
     * === METHOD BARU: EKSPOR KE PDF ===
     * Membuat dan mengunduh file PDF dari data pengajuan.
     */
    public function exportPdf($id)
    {
        $submission = ExtendAdvance::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Render view 'pdf_template' dengan data, lalu buat PDF
        $pdf = PDF::loadView('user_staff2.extend-advance.pdf_template', compact('submission'));

        // Nama file saat diunduh
        $fileName = 'Extend-Advance-' . $submission->operator . '-' . $submission->flight_date->format('Y-m-d') . '.pdf';

        // Unduh file PDF di browser
        return $pdf->download($fileName);
    }

    /**
     * === METHOD BARU: UNGGAH DOKUMEN TTD ===
     * Menyimpan file yang diunggah oleh pengaju dan mengubah status.
     */
    public function uploadSignedDocument(Request $request, $id)
    {
        $submission = ExtendAdvance::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $validated = $request->validate([
            'signed_document' => 'required|file|mimes:pdf|max:2048', // Maks 2MB
        ], [
            'signed_document.required' => 'Anda harus memilih file untuk diunggah.',
            'signed_document.mimes' => 'File yang diunggah harus dalam format PDF.',
            'signed_document.max' => 'Ukuran file tidak boleh melebihi 2MB.',
        ]);

        // Hapus file lama jika ada (untuk kasus revisi di masa depan)
        if ($submission->signed_document_path) {
            Storage::disk('public')->delete($submission->signed_document_path);
        }

        // Simpan file baru
        $filePath = $request->file('signed_document')->store('signed_documents/extend_advance', 'public');

        // Update database
        $submission->signed_document_path = $filePath;
        $submission->submission_status = 'Diajukan'; // Status berubah menjadi "Diajukan"
        $submission->save();

        return redirect()->route('extend-advance.show', $submission->id)
            ->with('success', 'Dokumen berhasil diunggah dan pengajuan Anda telah diteruskan untuk verifikasi staf.');
    }

    /* ================== STAFF ROUTES (BARU) ================== */

    /**
     * Menampilkan daftar semua pengajuan untuk staf.
     */
    public function indexStaff()
    {
        $submissions = ExtendAdvance::with('user')->latest()->get();
        $statementText = ExtendAdvanceSetting::where('key', 'statement_notes')->first()->value ?? 'Default statement not found.';
        return view('user_staff2.extend-advance.index', compact('submissions', 'statementText'));
    }

       /**
     * Menampilkan detail pengajuan untuk Pengaju.
     */
    public function show($id)
    {
        $submission = ExtendAdvance::where('id', $id)
            ->where('user_id', Auth::id()) // Pastikan hanya pemilik yang bisa lihat
            ->firstOrFail();
            
        return view('user_staff2.extend-advance.show', compact('submission'));
    }


    /**
     * Memperbarui status pengajuan oleh staf.
     */
    public function updateStatus(Request $request, ExtendAdvance $extendAdvance)
    {
        $validated = $request->validate([
            'submission_status' => 'required|in:Disetujui,Ditolak,Revisi Diperlukan',
            'staff_notes' => 'required_if:submission_status,Ditolak,Revisi Diperlukan|nullable|string',
            'reply_document_path' => 'required_if:submission_status,Disetujui|nullable|url',
        ], [
            'staff_notes.required_if' => 'Catatan wajib diisi jika status Ditolak atau Minta Revisi.',
            'reply_document_path.required_if' => 'Tautan surat balasan wajib diisi jika status Disetujui.',
            'reply_document_path.url' => 'Input harus berupa tautan (URL) yang valid.',
        ]);

        $extendAdvance->submission_status = $validated['submission_status'];
        $extendAdvance->staff_notes = $validated['staff_notes'];

        if ($validated['submission_status'] === 'Disetujui') {
            $extendAdvance->reply_document_path = $validated['reply_document_path'];
        } else {
            $extendAdvance->reply_document_path = null;
        }

        $extendAdvance->save();

        return redirect()->route('staff.extend-advance.show', $extendAdvance->id)->with('success', 'Status pengajuan berhasil diperbarui.');
    }

    /**
     * Method baru untuk memperbarui teks pernyataan global.
     */
    public function updateStatement(Request $request)
    {
        $validated = $request->validate([
            'statement_notes' => 'required|string',
        ]);

        ExtendAdvanceSetting::updateOrCreate(
            ['key' => 'statement_notes'],
            ['value' => $validated['statement_notes']]
        );

        return redirect()->route('staff.extend-advance.index')
            ->with('success', 'Teks Pernyataan Tanggung Jawab berhasil diperbarui.');
    }
}

