<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('complaints', function (Blueprint $table) {
            $table->string('phone_number', 15)->nullable()->after('email');
        });
    }
    public function down()
    {
        Schema::table('complaints', function (Blueprint $table) {
            $table->dropColumn('phone_number');
        });
    }
};
