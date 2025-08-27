<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\persuratan;
use App\Models\SuratVerification;
use App\Models\SuratRevision;
use App\Models\Surat_event; // <-- model sederhana untuk tabel surat_events
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PersuratanController extends Controller
{
    public function index(Request $request)
    {
         $user = Auth::user();
        $view = $request->query('view', 'inbox'); // default inbox

        $base = persuratan::query()->with(['user','assignee'])->latest();

        switch ($view) {
            case 'mine': // Dibuat oleh saya
                $letters = (clone $base)->where('user_id', $user->id)->get();
                break;

            case 'verifier': // Saya sebagai verifikator (historis atau pending)
                $letters = (clone $base)
                    ->whereHas('verifications', fn($q) => $q->where('user_id', $user->id))
                    ->get();
                break;

            case 'final': // Untuk persetujuan final saya
                $letters = (clone $base)->where('final_approver_id', $user->id)->get();
                break;

            case 'all': // opsional
                $letters = (clone $base)->get();
                break;

            case 'inbox':
            default: // Perlu tindakan saya (yang sedang di-assign ke saya)
                $letters = (clone $base)->where('assigned_to_user_id', $user->id)->get();
                break;
        }
        return view('user_staff2.persuratan.index', compact('letters'));
    }

    public function create()
    {
        $staffs = User::where('is_staff', true)->orderBy('name')->get();
        return view('user_staff2.persuratan.create', compact('staffs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            // kolom title sudah dihapus => tidak divalidasi lagi
            'letter_type'        => 'required|string',
            'letter_date'        => 'required|date',
            'recipient_address'  => 'required|string',
            'subject'            => 'required|string',
            'final_approver_id'  => 'required|exists:users,id',

            'verifiers'          => 'nullable|array',
            'verifiers.*'        => 'exists:users,id',

            'collaborators'      => 'nullable|array',
            'collaborators.*'    => 'exists:users,id',

            'attachments'        => 'required|array|min:1',
            'attachments.*'      => 'file|mimes:pdf|max:2048',
        ]);

        DB::beginTransaction();
        try {
            // simpan lampiran
            $paths = [];
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $paths[] = $file->store('letters', 'public');
                }
            }

            // buat surat (tanpa verifiers, tanpa title)
            $letter = persuratan::create([
                'user_id'            => Auth::id(),
                'letter_type'        => $validated['letter_type'],
                'letter_date'        => $validated['letter_date'],
                'recipient_address'  => $validated['recipient_address'],
                'subject'            => $validated['subject'],
                'final_approver_id'  => $validated['final_approver_id'],
                'collaborators'      => $request->input('collaborators', []),
                'attachments'        => $paths,
                'status'             => 'Verifikasi Tambahan', // default
            ]);

            // susun antrian verifikasi
            $verifiers = $request->input('verifiers', []);
            if (!empty($verifiers)) {
                foreach ($verifiers as $i => $uid) {
                    $letter->verifications()->create([
                        'user_id' => $uid,
                        'order'   => $i + 1,
                        // status default "Menunggu"
                    ]);
                }
                // assign ke verifikator pertama
                $letter->assigned_to_user_id = $verifiers[0];
                $letter->status = 'Verifikasi Tambahan';
                $letter->save();

                $this->logEvent($letter, 'created', ['subject' => $letter->subject]);
                $this->logEvent($letter, 'assigned', ['to_user_id' => $verifiers[0], 'reason' => 'first_verifier']);
                $this->logEvent($letter, 'verification_requested', ['queue_size' => count($verifiers)]);
            } else {
                // tanpa verifikator → langsung ke atasan
                $letter->status = 'Menunggu Persetujuan Atasan';
                $letter->assigned_to_user_id = Auth::user()->supervisor_id ?: $validated['final_approver_id'];
                $letter->save();

                $this->logEvent($letter, 'created', ['subject' => $letter->subject]);
                $this->logEvent($letter, 'assigned', ['to_user_id' => $letter->assigned_to_user_id, 'reason' => 'no_verifier_supervisor_or_final']);
            }

            DB::commit();
            return redirect()->route('persuratan.staffIndex')
                ->with('success', 'Surat berhasil dibuat.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: '.$e->getMessage())->withInput();
        }
    }

    public function show(persuratan $surat)
    {
    $surat->load([
        'user',
        'assignee',
        'finalApprover',
        'verifications.user',
        'revisions.user',
        'events', // jika pakai model SuratEvent
    ]);

    return view('user_staff2.persuratan.show', ['letter' => $surat]);
    }

    /**
     * Verifikator menyetujui tahapnya.
     */
    public function approveVerification(Request $request, persuratan $surat)
    {
        DB::beginTransaction();
        try {
            $actorId = Auth::id();

            // tandai tahap verifikasi aktor ini sebagai Disetujui
            $current = $surat->verifications()
                ->where('user_id', $actorId)
                ->where('status', 'Menunggu')
                ->orderBy('order')
                ->first();

            if (!$current) {
                return back()->with('error', 'Tahap verifikasi tidak ditemukan atau sudah diproses.');
            }

            $current->update(['status' => 'Disetujui']);
            $this->logEvent($surat, 'verified', ['by' => $actorId, 'order' => $current->order]);

            // cari verifikator berikutnya
            $next = $surat->verifications()
                ->where('status', 'Menunggu')
                ->orderBy('order')
                ->first();

            if ($next) {
                // lanjut ke verifikator berikutnya
                $surat->assigned_to_user_id = $next->user_id;
                $surat->status = 'Verifikasi Tambahan';
                $surat->save();

                $this->logEvent($surat, 'assigned', ['to_user_id' => $next->user_id, 'reason' => 'next_verifier']);
            } else {
                // semua verifier selesai → minta persetujuan atasan/final approver
                $surat->status = 'Menunggu Persetujuan Atasan';
                $surat->assigned_to_user_id = $surat->final_approver_id;
                $surat->save();

                $this->logEvent($surat, 'assigned', ['to_user_id' => $surat->final_approver_id, 'reason' => 'final_approval']);
            }

            DB::commit();
            return back()->with('success', 'Verifikasi disetujui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyetujui: '.$e->getMessage());
        }
    }

    /**
     * Verifikator menolak surat (bisa langsung final Ditolak).
     */
    public function rejectVerification(Request $request, persuratan $surat)
    {
        $request->validate(['comments' => 'required|string']);

        DB::beginTransaction();
        try {
            $actorId = Auth::id();

            $current = $surat->verifications()
                ->where('user_id', $actorId)
                ->where('status', 'Menunggu')
                ->orderBy('order')
                ->first();

            if ($current) {
                $current->update(['status' => 'Ditolak', 'comments' => $request->comments]);
            }

            // tandai surat ditolak & tidak ada assignee aktif
            $surat->update([
                'status' => 'Ditolak',
                'assigned_to_user_id' => null,
            ]);

            $this->logEvent($surat, 'rejected', ['by' => $actorId, 'comments' => $request->comments]);

            DB::commit();
            return back()->with('success', 'Surat ditolak.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menolak: '.$e->getMessage());
        }
    }

    /**
     * Meminta revisi (dari verifikator/atasan) → balik ke pembuat.
     */
    public function requestRevision(Request $request, persuratan $surat)
    {
        $request->validate(['comments' => 'required|string']);

        DB::beginTransaction();
        try {
            $actorId = Auth::id();

            // simpan catatan revisi
            $surat->revisions()->create([
                'user_id'         => $actorId,
                'comments'        => $request->comments,
                'previous_status' => $surat->status,
            ]);

            // update status & assignment ke pembuat
            $surat->update([
                'status' => 'Revisi Diperlukan',
                'assigned_to_user_id' => $surat->user_id,
            ]);

            $this->logEvent($surat, 'revision_requested', ['by' => $actorId, 'comments' => $request->comments]);

            DB::commit();
            return back()->with('success', 'Revisi diminta ke pembuat surat.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal meminta revisi: '.$e->getMessage());
        }
    }

    /**
     * Setelah pembuat mengunggah revisi → kembalikan ke antrian berikutnya.
     * (opsional – panggil ketika submit revisi)
     */
    public function submitRevision(Request $request, persuratan $surat)
    {
        DB::beginTransaction();
        try {
            // log event
            $this->logEvent($surat, 'revision_submitted', ['by' => Auth::id()]);

            // tentukan assignment berikutnya:
            // jika masih ada verifier berstatus Menunggu → ke verifier pertama yang Menunggu
            $next = $surat->verifications()->where('status', 'Menunggu')->orderBy('order')->first();
            if ($next) {
                $surat->update([
                    'status' => 'Verifikasi Tambahan',
                    'assigned_to_user_id' => $next->user_id,
                ]);
                $this->logEvent($surat, 'assigned', ['to_user_id' => $next->user_id, 'reason' => 'resume_verification']);
            } else {
                // jika tidak ada verifier → ke final approver
                $surat->update([
                    'status' => 'Menunggu Persetujuan Atasan',
                    'assigned_to_user_id' => $surat->final_approver_id,
                ]);
                $this->logEvent($surat, 'assigned', ['to_user_id' => $surat->final_approver_id, 'reason' => 'resume_final_approval']);
            }

            DB::commit();
            return back()->with('success', 'Revisi dikirim.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal mengirim revisi: '.$e->getMessage());
        }
    }

    /**
     * Final approver menyetujui (tanda tangan akhir).
     */
    public function finalApprove(Request $request, persuratan $surat)
    {
        DB::beginTransaction();
        try {
            // set Disetujui dan kosongkan assignee
            $surat->update([
                'status' => 'Disetujui',
                'assigned_to_user_id' => null,
            ]);

            $this->logEvent($surat, 'final_approved', ['by' => Auth::id()]);

            DB::commit();
            return back()->with('success', 'Surat disetujui final.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyetujui final: '.$e->getMessage());
        }
    }

    /* ===================== helpers ===================== */

    private function logEvent(persuratan $letter, string $type, array $meta = []): void
    {
        Surat_event::create([
            'persuratan_id' => $letter->id,
            'actor_user_id' => Auth::id(),
            'event_type'    => $type,
            'meta'          => $meta,
        ]);
    }
}
