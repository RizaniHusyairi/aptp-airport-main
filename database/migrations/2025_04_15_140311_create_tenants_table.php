<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tenants', function (Blueprint $table) {
            $table->id();
            $table->string('business_name');
            $table->string('business_type');
            $table->text('description');
            $table->string('rental_type');
            $table->string('rental_more')->nullable();
            $table->string('documents'); 
            $table->enum('submission_status', ['Diajukan', 'Disetujui', 'Ditolak', 'Revisi Diperlukan'])->default('Diajukan');
            $table->text('staff_notes')->nullable();
            $table->string('reply_document_path')->nullable(); // Untuk link Google Drive
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tenants');
    }
};
