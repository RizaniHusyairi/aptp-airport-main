<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('surat_revisions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('letter_id')->constrained()->onDelete('cascade');
                $table->foreignId('user_id')->constrained()->comment('User yang meminta revisi');
                $table->text('comments');
                $table->string('previous_status');
                $table->timestamps();

        });
    }
};
