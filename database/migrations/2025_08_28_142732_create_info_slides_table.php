<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('info_slides', function (Blueprint $table) {
            $table->id();
            $table->string('image_path');
            $table->string('link_url')->nullable(); // URL tujuan jika slide diklik
            $table->boolean('is_visible')->default(true);
            $table->timestamps();
});
    }
};
