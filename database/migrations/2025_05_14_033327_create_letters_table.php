<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('letters', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['edaran', 'utusan']);
            $table->string('number')->unique();
            $table->string('title');
            $table->date('issue_date');
            $table->string('file_path');
            $table->timestamps();
        });    
    }

    public function down()
    {
        Schema::dropIfExists('letters');
    }
};
