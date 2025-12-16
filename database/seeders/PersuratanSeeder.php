<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Role; // Pastikan model Role ada
use App\Models\persuratan;
use App\Models\Surat_event;
use App\Models\SuratVerification;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PersuratanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1. Ambil User Dummy yang sudah ada (sesuai UserSeeder kamu)

        // Ambil Pejabat (Final Approver)
        $kabandara = User::where('email', 'kabandara@aptpairport.id')->first();
        $kasubbag  = User::where('email', 'kasubbag@aptpairport.id')->first();
        $kasiTeknik = User::where('email', 'kasi3@aptpairport.id')->first(); // Kasi Teknik

        // Ambil Pembuat Surat (Staff Biasa)
        $staffTeknik = User::where('email', 'staff1@aptpairport.id')->first();
        $staffPelayanan = User::where('email', 'staff2@aptpairport.id')->first();

        // Pastikan user-user ini ada (jika UserSeeder belum dijalankan, ini mencegah error)
        if (!$kabandara || !$staffTeknik) {
            $this->command->info('User dummy tidak lengkap. Pastikan UserSeeder sudah dijalankan.');
            return;
        }

        DB::beginTransaction();
        try {
            // =================================================================
            // SKENARIO 1: Surat dari Staff Teknik -> Verifikasi Kasi -> Final Kabandara
            // Status: SIAP TTE (Posisi di Kabandara)
            // =================================================================

            $surat1 = persuratan::create([
                'user_id'             => $staffTeknik->id, // Pembuat
                'letter_type'         => 'Nota Dinas',
                'letter_date'         => Carbon::now()->subDays(2), // Dibuat 2 hari lalu
                'recipient_address'   => 'Direktorat Jenderal Perhubungan Udara',
                'subject'             => 'Laporan Perawatan Fasilitas Sisi Udara Bulan Oktober',
                'final_approver_id'   => $kabandara->id, // Final: Kabandara
                'assigned_to_user_id' => $kabandara->id, // SEKARANG ada di Kabandara
                'status'              => 'Menunggu Persetujuan Atasan', // Status Siap TTE
                'attachments'         => ['https://docs.google.com/document/d/dummy-doc-1'],
                'collaborators'       => [],
                'signed_document_link'=> null, // Belum di TTE
            ]);

            // Buat Histori Verifikasi (Sudah Disetujui oleh Kasi)
            SuratVerification::create([
                'persuratan_id' => $surat1->id,
                'user_id'       => $kasiTeknik->id, // Verifikator: Kasi Teknik
                'order'         => 1,
                'status'        => 'Disetujui', // Sudah approve
                'comments'      => 'Data teknis sudah sesuai. Lanjutkan.',
                'updated_at'    => Carbon::now()->subDay(),
            ]);

            // Buat Log Event (Agar timeline terlihat realistis)
            $this->log($surat1, $staffTeknik->id, 'created', ['subject' => $surat1->subject], Carbon::now()->subDays(2));
            $this->log($surat1, $staffTeknik->id, 'assigned', ['to_user_id' => $kasiTeknik->id, 'reason' => 'first_verifier'], Carbon::now()->subDays(2));
            $this->log($surat1, $kasiTeknik->id, 'verified', ['by' => $kasiTeknik->id, 'order' => 1], Carbon::now()->subDay());
            $this->log($surat1, $kasiTeknik->id, 'assigned', ['to_user_id' => $kabandara->id, 'reason' => 'final_approval'], Carbon::now()->subDay());


            // =================================================================
            // SKENARIO 2: Surat dari Staff Pelayanan -> Final Kasubbag (Tanpa Verifikator)
            // Status: SIAP TTE (Posisi di Kasubbag)
            // =================================================================

            $surat2 = persuratan::create([
                'user_id'             => $staffPelayanan->id,
                'letter_type'         => 'Surat Dinas',
                'letter_date'         => Carbon::now()->subDay(),
                'recipient_address'   => 'Dinas Pariwisata Kota Samarinda',
                'subject'             => 'Koordinasi Event Promosi Wisata di Bandara',
                'final_approver_id'   => $kasubbag->id,
                'assigned_to_user_id' => $kasubbag->id, // Langsung ke Final
                'status'              => 'Menunggu Persetujuan Atasan',
                'attachments'         => ['https://docs.google.com/document/d/dummy-doc-2'],
                'signed_document_link'=> null,
            ]);

            // Tidak ada verifikator, langsung log
            $this->log($surat2, $staffPelayanan->id, 'created', ['subject' => $surat2->subject], Carbon::now()->subDay());
            $this->log($surat2, $staffPelayanan->id, 'assigned', ['to_user_id' => $kasubbag->id, 'reason' => 'no_verifier_supervisor_or_final'], Carbon::now()->subDay());


            // =================================================================
            // SKENARIO 3: Surat Masih dalam Proses Verifikasi (Belum sampai Final)
            // Status: Verifikasi Tambahan (Posisi di Kasi Teknik)
            // =================================================================

            $surat3 = persuratan::create([
                'user_id'             => $staffTeknik->id,
                'letter_type'         => 'Nota Dinas',
                'letter_date'         => Carbon::now(),
                'recipient_address'   => 'Internal Unit Teknik',
                'subject'             => 'Pengajuan Pengadaan Suku Cadang Genset',
                'final_approver_id'   => $kabandara->id,
                'assigned_to_user_id' => $kasiTeknik->id, // Masih di Kasi
                'status'              => 'Verifikasi Tambahan',
                'attachments'         => ['https://docs.google.com/document/d/dummy-doc-3'],
            ]);

            // Verifikasi belum disetujui
            SuratVerification::create([
                'persuratan_id' => $surat3->id,
                'user_id'       => $kasiTeknik->id,
                'order'         => 1,
                'status'        => 'Menunggu', // Masih pending
            ]);

            $this->log($surat3, $staffTeknik->id, 'created', ['subject' => $surat3->subject]);
            $this->log($surat3, $staffTeknik->id, 'assigned', ['to_user_id' => $kasiTeknik->id, 'reason' => 'first_verifier']);

            DB::commit();
            $this->command->info('Data dummy persuratan berhasil dibuat!');

        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('Gagal membuat seeder: ' . $e->getMessage());
        }
    }

    /**
     * Helper untuk membuat log event
     */
    private function log($surat, $actorId, $type, $meta = [], $time = null)
    {
        Surat_event::create([
            'persuratan_id' => $surat->id,
            'actor_user_id' => $actorId,
            'event_type'    => $type,
            'meta'          => $meta,
            'created_at'    => $time ?? Carbon::now(),
            'updated_at'    => $time ?? Carbon::now(),
        ]);
    }
}
