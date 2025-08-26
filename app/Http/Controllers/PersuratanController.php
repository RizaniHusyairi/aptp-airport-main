<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\persuratan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PersuratanController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        // Tampilkan surat yang dibuat oleh user, atau di mana user adalah verifikator, atau ditugaskan ke user
        $letters = persuratan::where('user_id', $user->id)
            ->orWhere('assigned_to_user_id', $user->id)
            ->orWhereHas('verifications', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->with(['user', 'assignee'])
            ->latest()
            ->get();
            
        return view('user_staff2.persuratan.index', compact('letters'));
    }

    public function create()
    {
        // Ambil semua staff untuk pilihan di form
        $staffs = User::where('is_staff', true)->orderBy('name')->get();
        return view('user_staff2.persuratan.create', compact('staffs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'letter_type' => 'required|string',
            'letter_date' => 'required|date',
            'recipient_address' => 'required|string',
            'subject' => 'required|string',
            'final_approver_id' => 'required|exists:users,id',
            'verifiers' => 'nullable|array',
            'verifiers.*' => 'exists:users,id',
            'collaborators' => 'nullable|array',
            'collaborators.*' => 'exists:users,id',
            'attachments' => 'required|array|min:1',
            'attachments.*' => 'file|mimes:pdf|max:2048',
        ]);

        DB::beginTransaction();
        try {
            $attachmentPaths = [];
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $attachmentPaths[] = $file->store('letters', 'public');
                }
            }

            $letter = persuratan::create([
                'user_id' => Auth::id(),
                'letter_type' => $validated['letter_type'],
                'letter_date' => $validated['letter_date'],
                'recipient_address' => $validated['recipient_address'],
                'subject' => $validated['subject'],
                'final_approver_id' => $validated['final_approver_id'],
                'verifiers'          => $request->input('verifiers', []),     // <-- isi [] kalau kosong
                'collaborators'      => $request->input('collaborators', []), // <-- isi [] kalau kosong
                'attachments' => $attachmentPaths,
            ]);

            // Tambahkan verifikator manual jika ada
            if (!empty($validated['verifiers'])) {
                foreach ($validated['verifiers'] as $order => $userId) {
                    $letter->verifications()->create([
                        'user_id' => $userId,
                        'order' => $order + 1,
                    ]);
                }
                // Tugaskan ke verifikator pertama
                $letter->assigned_to_user_id = $validated['verifiers'][0];
            } else {
                // Jika tidak ada verifikator, langsung ke atasan
                $letter->assigned_to_user_id = Auth::user()->supervisor_id;
            }
            
            $letter->save();
            DB::commit();

            return redirect()->route('persuratan.staffIndex')->with('success', 'Surat berhasil dibuat dan dikirim untuk verifikasi.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function show(persuratan $surat)
    {
        // Tampilkan detail surat, termasuk riwayat revisi
        $surat->load(['user', 'assignee', 'finalApprover', 'verifications.user']);
        return view('user_staff2.persuratan.show', compact('surat'));
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
        return redirect()->route('admin2.persuratan.index')->with('success', 'Surat berhasil disetujui dan diteruskan.');
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
