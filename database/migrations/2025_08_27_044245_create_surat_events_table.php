<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surat_events', function (Blueprint $table) {

            // PK
            $table->bigIncrements('id');

            // FKs
            $table->foreignId('persuratan_id')
                ->constrained('persuratans')
                ->cascadeOnDelete();

            $table->foreignId('actor_user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // ENUM event_type
            $table->enum('event_type', [
                'created',
                'assigned',
                'verification_requested',
                'verified',
                'rejected',
                'revision_requested',
                'revision_submitted',
                'final_approved',
            ])->index(); // opsional index tunggal; tetap ada index gabungan di bawah

            // JSON meta dengan default JSON_OBJECT()
            $table->json('meta')->default(DB::raw('(JSON_OBJECT())'));

            // created_at saja (tanpa updated_at)
            $table->timestamps();


    
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surat_events');
    }
};
