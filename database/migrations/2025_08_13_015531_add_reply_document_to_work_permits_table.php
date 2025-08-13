<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('work_permits', function (Blueprint $table) {
        // Kolom untuk menyimpan path file surat balasan
        $table->string('reply_document_path')->nullable()->after('staff_notes');
    });
    }
};
