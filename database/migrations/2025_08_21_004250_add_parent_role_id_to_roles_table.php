<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('roles', function (Blueprint $table) {
            // Menambahkan kolom untuk menyimpan ID dari role atasan
            $table->foreignId('parent_role_id')
                  ->nullable() // Bisa kosong untuk jabatan tertinggi
                  ->after('name')
                  ->constrained('roles') // Merujuk ke tabel roles itu sendiri
                  ->onDelete('set null'); // Jika role atasan dihapus, kolom ini menjadi NULL
        });
    }

    public function down()
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->dropForeign(['parent_role_id']);
            $table->dropColumn('parent_role_id');
        });
    }
};
