<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('lelangs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('lelang_type');
            $table->text('description');
            $table->string('documents');
            $table->string('additional_documents')->nullable();
            $table->string('submission_status')->default('diajukan');
            $table->timestamps();
        });

        Schema::create('lelang_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('lelang_id')->constrained()->onDelete('cascade');
        $table->timestamps();
        });
        
    }

    public function down()
    {
        Schema::dropIfExists('lelang_user');
        Schema::dropIfExists('lelangs');
    }
};
