<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('periodic_documents', function (Blueprint $table) {
            $table->id();
            $table->string('category'); // Kunci untuk pengelompokan, cth: "Laporan Keuangan"
            $table->string('title');
            $table->text('document_path');
            $table->date('published_date');
            $table->string('pejabat_name')->nullable();
            $table->timestamps();
        });
    }
};
