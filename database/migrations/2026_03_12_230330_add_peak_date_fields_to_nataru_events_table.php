<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('nataru_events', function (Blueprint $table) {
            $table->date('peak_date')->nullable()->after('end_date'); // Hari H
        });
    }

    public function down()
    {
        Schema::table('nataru_events', function (Blueprint $table) {
            $table->dropColumn(['peak_date']);
        });
    }
};
