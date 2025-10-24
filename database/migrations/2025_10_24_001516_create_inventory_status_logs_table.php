<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('inventory_status_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventory_id')->constrained('inventories')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete(); // Staff yang mengubah status
            $table->enum('previous_status', ['Baik', 'Pemeliharaan'])->nullable();
            $table->enum('new_status', ['Baik', 'Pemeliharaan']);
            $table->string('notes')->nullable(); // Bisa untuk menyimpan link laporan atau catatan lain
            $table->timestamp('created_at')->nullable(); // Hanya timestamp saat dibuat
        });
    }
};
