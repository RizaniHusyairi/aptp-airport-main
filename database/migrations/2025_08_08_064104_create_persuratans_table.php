<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('persuratans', function (Blueprint $table) {
            $table->bigIncrements('id');

            // siapa pembuat surat
            $table->unsignedBigInteger('user_id');

            // siapa yang saat ini “memegang bola” (boleh null)
            $table->unsignedBigInteger('assigned_to_user_id')->nullable();

            // metadata surat
            $table->string('letter_type', 125);         // ex: "Nota Dinas", "Surat Dinas"
            $table->date('letter_date');                // tanggal surat
            $table->text('recipient_address');          // alamat tujuan
            $table->string('subject', 125);             // perihal ringkas

            // pejabat penandatangan final (wajib)
            $table->unsignedBigInteger('final_approver_id');

            // daftar kolaborator (array user_id)
            // gunakan JSON agar mudah difilter & di-cast di Eloquent
            $table->json('collaborators')->default(json_encode([]));

            // daftar lampiran (array path di storage)
            $table->json('attachments')->default(json_encode([]));

            // status global surat untuk kebutuhan listing/dashboard cepat
            // (gunakan enum atau string; di sini pakai enum agar konsisten)
            $table->enum('status', [
                'Verifikasi Tambahan',
                'Menunggu Persetujuan Atasan',
                'Disetujui',
                'Ditolak',
                'Revisi Diperlukan',
            ])->default('Verifikasi Tambahan');
            $table->text('signed_document_link')->nullable();

            $table->timestamps();

            // Indexes
            $table->index('status');
            $table->index('letter_date');
            $table->index(['assigned_to_user_id', 'status']);
            $table->index('final_approver_id');

            // Foreign keys (InnoDB)
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            // assigned_to_user_id bisa berubah/terhapus → SET NULL agar riwayat surat tetap ada
            $table->foreign('assigned_to_user_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('final_approver_id')->references('id')->on('users')->restrictOnDelete();
        });


    }
};
