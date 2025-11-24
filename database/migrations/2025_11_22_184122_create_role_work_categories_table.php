<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('role_work_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_id')->constrained('roles')->onDelete('cascade');
            $table->string('category_name'); // Contoh: 'Alat Besar', 'Listrik'
            $table->boolean('can_verify')->default(false); // Checkbox verifikasi
            $table->timestamps();
        });

    }

    public function down()
    {
        Schema::dropIfExists('role_work_categories');
    }
};
