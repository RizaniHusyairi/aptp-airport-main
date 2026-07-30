<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('external_links', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('url', 500);
            $table->string('description', 255)->nullable();
            $table->string('icon', 50)->nullable();      // kelas Bootstrap Icons, cth: bi-megaphone-fill
            $table->string('logo_path')->nullable();     // unggahan opsional, diprioritaskan di atas icon
            $table->string('group', 100);                // kunci pengelompokan, string agar bebas kode
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('external_links');
    }
};
