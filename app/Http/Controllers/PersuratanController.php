<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\persuratan;
use App\Models\SuratVerification;
use App\Models\SuratRevision;
use App\Models\Role;
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
        
        // Ambil HANYA role pejabat yang bisa menjadi penandatangan final
        $approverRoles = Role::whereIn('name', [
            'Kepala Bandara',
            'Kepala Subbagian Keuangan dan Tata Usaha',
            'Kepala Seksi Keamanan Penerbangan dan Pelayanan Darurat',
            'Kepala Seksi Pelayanan dan Kerjasama',
            'Kepala Seksi Teknik dan Operasi'
        ])
        ->with('users') // Eager load user yang memiliki role tersebut
        ->get();

        return view('user_staff2.persuratan.create', compact('staffs','approverRoles'));
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
            'attachments.*'      => ['required','url', function($attr, $value, $fail) {
                $host = parse_url($value, PHP_URL_HOST);
                $allowed = ['drive.google.com','docs.google.com'];
                if (!in_array($host, $allowed, true)) {
                    $fail('Hanya tautan drive.google.com atau docs.google.com yang diizinkan.');
                }
            }],
            
        ],[
            'letter_type.required'       => 'Jenis surat wajib dipilih.',
            'letter_date.required'       => 'Tanggal surat wajib diisi.',
            'letter_date.date'           => 'Format tanggal surat tidak valid.',
            'recipient_address.required' => 'Alamat tujuan surat wajib diisi.',
            'subject.required'           => 'Perihal surat wajib diisi.',
            'final_approver_id.required' => 'Pejabat penandatangan akhir wajib dipilih.',
            'final_approver_id.exists'   => 'Pejabat yang dipilih tidak valid.',
            'attachments.required'       => 'Wajib menyertakan minimal satu tautan dokumen.',
            'attachments.min'            => 'Wajib menyertakan minimal satu tautan dokumen.',
            'attachments.*.url'          => 'Format tautan tidak valid (harus diawali http:// atau https://).',
        ]);

        // Pecah textarea menjadi array URL (satu per baris), trim & buang baris kosong/duplikat
    $links = collect($validated['attachments'])
        ->map(fn($s) => trim($s))
        ->filter()
        ->unique()
        ->values();
        

        DB::beginTransaction();
        try {
            
            

            // buat surat (tanpa verifiers, tanpa title)
            $letter = persuratan::create([
                'user_id'            => Auth::id(),
                'letter_type'        => $validated['letter_type'],
                'letter_date'        => $validated['letter_date'],
                'recipient_address'  => $validated['recipient_address'],
                'subject'            => $validated['subject'],
                'final_approver_id'  => $validated['final_approver_id'],
                'collaborators'      => $request->input('collaborators', []),
                'attachments'        => $links->all(), // <-- array URL
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

        // Eager load semua relasi yang dibutuhkan
        $surat->load([
            'user',
            'assignee',
            'finalApprover',
            'verifications.user',
            'revisions.user',
            'events.actor', // <-- Load aktor untuk setiap event
        ]);

        // Kumpulkan semua ID pengguna dari meta data event untuk di-query sekaligus
        $metaUserIds = $surat->events->pluck('meta')->flatMap(function ($meta) {
            return [$meta['to_user_id'] ?? null, $meta['by'] ?? null];
        })->filter()->unique()->values();
        
        // Ambil data user dari ID yang terkumpul dan format sebagai [id => name]
        $metaUsers = User::whereIn('id', $metaUserIds)->pluck('name', 'id');

        return view('user_staff2.persuratan.show', compact('surat', 'metaUsers'));
    // $surat->load([
    //     'user',
    //     'assignee',
    //     'finalApprover',
    //     'verifications.user',
    //     'revisions.user',
    //     'events', // jika pakai model SuratEvent
    // ]);

    // return view('user_staff2.persuratan.show', ['letter' => $surat]);
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
        $request->validate([
            'attachments'   => 'required|array|min:1',
            'attachments.*' => ['required','url', function($attr,$value,$fail){
                $host = parse_url($value, PHP_URL_HOST);
                $allowed = ['drive.google.com','docs.google.com'];
                if (!in_array($host, $allowed, true)) {
                    $fail('Hanya tautan Google Drive/Docs yang diizinkan.');
                }
            }],
        ]);

        DB::beginTransaction();
        try {
            // simpan lampiran baru
            $surat->attachments = collect($request->attachments)
                ->map(fn($s)=>trim($s))
                ->filter()
                ->unique()
                ->values()
                ->all();

            $surat->status = 'Verifikasi Tambahan';
            
            // kembalikan assignment
            $next = $surat->verifications()->where('status','Menunggu')->orderBy('order')->first();
            if ($next) {
                $surat->assigned_to_user_id = $next->user_id;
                $reason = 'resume_verification';
            } else {
                $surat->assigned_to_user_id = $surat->final_approver_id;
                $surat->status = 'Menunggu Persetujuan Atasan';
                $reason = 'resume_final_approval';
            }

            $surat->save();

            $this->logEvent($surat, 'revision_submitted', ['by'=>Auth::id()]);
            $this->logEvent($surat, 'assigned', ['to_user_id'=>$surat->assigned_to_user_id, 'reason'=>$reason]);

            DB::commit();
            return back()->with('success','Revisi berhasil dikirim.');
        } catch(\Exception $e) {
            DB::rollBack();
            return back()->with('error','Gagal mengirim revisi: '.$e->getMessage());
        }
    }
    /**
     * Final approver menyetujui (tanda tangan akhir).
     */
    public function finalApprove(Request $request, persuratan $surat)
    {
        // === PERUBAHAN DI SINI: TAMBAHKAN VALIDASI UNTUK LINK BARU ===
        $validated = $request->validate([
            'signed_document_link' => 'required|url',
        ]);

        DB::beginTransaction();
        try {
            // set Disetujui, kosongkan assignee, dan simpan link dokumen
            $surat->update([
                'status' => 'Disetujui',
                'assigned_to_user_id' => null,
                'signed_document_link' => $validated['signed_document_link'], // <-- SIMPAN LINK DI SINI
            ]);

            // Tambahkan link ke log agar tercatat
            $this->logEvent($surat, 'final_approved', [
                'by' => Auth::id(),
                'signed_link' => $validated['signed_document_link']
            ]);

            DB::commit();
            return back()->with('success', 'Surat disetujui final dan dokumen bertanda tangan telah disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyetujui final: '.$e->getMessage());
        }
    }

    public function destroy(persuratan $surat)
    {
        // hanya pembuat
        if (Auth::id() !== $surat->user_id) {
            return back()->with('error', 'Anda tidak berhak menghapus surat ini.');
        }

        // harus ada verifikator, dan tidak ada yang sudah respon
        $hasAnyVerifier = $surat->verifications()->exists();
        $hasAnyResponse = $surat->verifications()
            ->whereIn('status', ['Disetujui', 'Ditolak'])
            ->exists();

        // status global juga harus masih di tahap verifikasi
        $isInVerificationStage = $surat->status === 'Verifikasi Tambahan';

        if (! $hasAnyVerifier || $hasAnyResponse || ! $isInVerificationStage) {
            return back()->with('error', 'Surat tidak dapat dihapus (sudah diproses atau tidak melalui verifikator).');
        }

        DB::beginTransaction();
        try {
            // (opsional) catat event
            if (class_exists(\App\Models\SuratEvent::class)) {
                \App\Models\SuratEvent::create([
                    'persuratan_id' => $surat->id,
                    'actor_user_id' => Auth::id(),
                    'event_type'    => 'deleted',
                    'meta'          => ['reason' => 'creator_cancel_before_verifier_response'],
                ]);
            }

            // Hapus surat. Dengan FK ON DELETE CASCADE di verifications/revisions/events, child akan ikut terhapus.
            $surat->delete();

            DB::commit();
            return redirect()->route('persuratan.staffIndex')->with('success', 'Surat berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus: '.$e->getMessage());
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
