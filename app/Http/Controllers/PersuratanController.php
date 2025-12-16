<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Models\persuratan;
use Illuminate\Http\Request;
use App\Models\SuratRevision;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\SuratVerification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;     // Import ini
use Illuminate\Support\Str;                 // Import ini
use SimpleSoftwareIO\QrCode\Facades\QrCode; // Import ini di atas
use App\Models\Surat_event; // <-- model sederhana untuk tabel surat_events

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
        // Tidak perlu validasi link input lagi, karena sistem yang buat.

        DB::beginTransaction();
        try {
            // 1. Ambil Data Penandatangan (User Login)
            $signer = Auth::user();

            // 2. Data Sertifikat (Dari yang kamu berikan)
            // Dalam implementasi nyata, ini bisa disimpan di database user (kolom: certificate_public_key)
            $certContent = "-----BEGIN CERTIFICATE-----
MIIGcTCCBFmgAwIBAgIUEsJcb4tcd5+ToUn+HArOMfwTG/0wDQYJKoZIhvcNAQELBQAwTDELMAkG
A1UEBhMCSUQxJTAjBgNVBAoMHEJhZGFuIFNpYmVyIGRhbiBTYW5kaSBOZWdhcmExFjAUBgNVBAMM
DUJTUkUgQ0EgRFMgRzEwHhcNMjUwNTE1MDY0MDEzWhcNMjcwNTE1MDY0MDEyWjB9MTEwLwYDVQQN
DCg1MTcwMjJRQkZWTDBVTEFRX1RhbmRhIFRhbmdhbiBFbGVrdHJvbmlrMRkwFwYDVQQDDBBQUkFZ
VURBIEVMRkFORFJPMSAwHgYDVQQKDBdLZW1lbnRlcmlhbiBQZXJodWJ1bmdhbjELMAkGA1UEBhMC
SUQwggEiMA0GCSqGSIb3DQEBAQUAA4IBDwAwggEKAoIBAQDdE6L/HIyeASkCxQJMpoMqt4bTLkv9
NE9ZP3fKGPa/NgG5ciDEM4lBAXJkgvNpSyFJt7+cWnhznCjvV3kjITHChubKQXIj+pR7B3Lp3gU1
ux/DZw96G0j16pQWyoRGLMeZ+JUidZR7SLe0s6YZJnE1JzS+D7+9PufJxB7RHL5vAQGmEkzzDkKF
lhG9y447+Dr1c+CUtfU+FIHKR8hHj20Jk4tYk7jOfx/QIsoBYE2cHbEc0ZhMTmugjQ0ZNS5RkrCm
X+YrsZsbiLcdDGE/TsTKFI+jeztu0v6Ov8wLyY1sf48h3+0bM/ixAE7R8ycusWJDtDhCucOw8r37
ZLIjzWthAgMBAAGjggIYMIICFDAMBgNVHRMBAf8EAjAAMB8GA1UdIwQYMBaAFMT4UP7N5+fSXA2M
ZQP+mzRmTTH5MHMGCCsGAQUFBwEBBGcwZTAwBggrBgEFBQcwAoYkaHR0cHM6Ly92YS5ic3JlLmdv
LmlkL2JzcmVjYWRzZzEuY3J0MDEGCCsGAQUFBzABhiVodHRwczovL3ZhLmJzcmVjYWRzZzEuY3J0
/vY3NwMIHUBgNVHSAEgcwwgckwPgYKYIJoAQEBBQEEAzAwMC4GCCsGAQUFBwICMCIM
IEluZGl2aWR1IEluc3RhbnNpIE9ubGluZSBMZXZlbCAzMEkGC2CCaAEBAQMLAgABMDowOAYIKwYB
BQUHAgEWLGh0dHBzOi8vYnNyZS5ic3NuLmdvLmlkL3JlcG9zaXRvcnkva2ViaWpha2FuMDwGCGCC
aAEBAQkBMDAwLgYIKwYBBQUHAgIwIgwgQWRvYmUgQXBwcm92ZWQgVHJ1c3QgTGlzdCAoQUFUTCkw
FwYDVR0lAQH/BA0wCwYJKoZIhvcvAQEFMBgGCCsGAQUFBwEDBAwwCjAIBgYEAI5GAQQwNQYDVR0f
BC4wLDAqoCigJoYkaHR0cHM6Ly92YS5ic3JlLmdvLmlkL2JzcmVjYWRzZzEuY3JsMB0GA1UdDgQW
BBRts2sZ+oNwpA4tzoAEswBWLU3oCDAOBgNVHQ8BAf8EBAMCBsAwDQYJKoZIhvcNAQELBQADggIB
ALJ6Az6MS+IeYQ9FVfpS5nINvWgBeNuTwlUm6madN+o4eIrRhCs8gGLGFT6+i8/MyqwP02MnAQ+Q
wiqZ25g/sy4z+HBkO1qGvW1btxvB0wDIf2Q6Kh4jfodQj+qO0YIS2V77AxtnwNBNLn2KnSfzs8ex
kMRZOBoWNNTeN+dUFXnnFYsZ7CDmF0ou3XAlt4JtB00je+a/7Wit8KBpb04Ueq2keVnZWffX5yPU
LqUI35AoVbhcCqw3Kh+myAr2OUhjfbjqn9S+xHiDMS/Kji9RNFCCwgub58TnRPQNAkLKbfTAva4b
nGdSrq9Ec7tYbtl88G8oFFEfzfm76pMz0Mlkikg9FJ7FGazrq26KusKV9AF2clR8lYMiOk20P2y/
jDeDsQfBUywz/E41vMMSEviNvapg9MxjP7pWJTt3Uh1+RE78jm1z9gWEysCyEaz3pchqSTzGNjie
IKAleuOeHwsXSWpAQ/PZcXkkyb0mwsKvBT8+zRdn7jYVCRqifKV8FXKpAl0dYFeFVfTisHgyQkTC
U1H61DMfx9pf3R2i4WIuFLCz/1yIOgwMpzyEOOr5g90b5X7cm3hPvewca2i8EfEu8AR7wsFvlll5
/E7RtPuAK380zPfvKykVQF7ET53TaF8NwBtnyLXnxjUzK7qR/vnUputgJ7X/MCKOr3qjGTDISljZ
-----END CERTIFICATE-----";

            // 3. Parse Sertifikat untuk ambil Nama (Opsional, agar terlihat keren di PDF)
            $certInfo = openssl_x509_parse($certContent);
            $commonName = $certInfo['subject']['CN'] ?? $signer->name; // Ambil CN (PRAYUDA ELFANDRO) atau nama user
            $issuer = $certInfo['issuer']['O'] ?? 'BSrE';

            // 4. Generate Konten QR Code
            // Ini link verifikasi. Nanti kita buat route publiknya agar saat discan valid.
            // Format: https://website-anda.com/verify-surat/{id_surat}
            $verificationUrl = route('public.surat.verify', ['uuid' => $surat->id . '-' . Str::random(5)]);

            // Generate Image QR Code (Format SVG/PNG base64 agar bisa masuk PDF)
            $qrCodeImage = base64_encode(QrCode::format('svg')->size(200)->generate($verificationUrl));

            // 5. Generate PDF "Lembar Pengesahan Elektronik"
            $pdf = Pdf::loadView('user_staff2.persuratan.tte_sheet', [
                'surat' => $surat,
                'signerName' => $commonName,
                'signerNip' => 'NIP. 19xxxxxxxxxxx', // Sebaiknya ambil dari column nip di table user
                'qrCode' => $qrCodeImage,
                'date' => now()->translatedFormat('d F Y H:i:s'),
                'issuer' => $issuer,
                'verificationUrl' => $verificationUrl
            ]);

            // 6. Simpan PDF ke Storage
            $fileName = 'TTE-' . Str::slug($surat->subject) . '-' . time() . '.pdf';
            $path = 'documents/signed_letters/' . $fileName;
            Storage::disk('public')->put($path, $pdf->output());

            // 7. Update Database
            // Simpan URL file lokal ke kolom signed_document_link
            $publicPath = Storage::url($path);

            $surat->update([
                'status' => 'Disetujui',
                'assigned_to_user_id' => null,
                'signed_document_link' => $publicPath,
            ]);

            $this->logEvent($surat, 'final_approved', [
                'by' => Auth::id(),
                'method' => 'digital_signature_generated'
            ]);

            DB::commit();
            return back()->with('success', 'Surat berhasil disetujui. Lembar Tanda Tangan Elektronik (TTE) telah dibuat otomatis.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal generate TTE: '.$e->getMessage());
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
            if (class_exists(Surat_event::class)) {
                Surat_event::create([
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
