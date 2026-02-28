<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Add user_id column
        Schema::table('lelangs', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->after('id');
        });

        // 2. Migrate data from pivot to this table
        DB::statement('UPDATE lelangs l JOIN lelang_user lu ON l.id = lu.lelang_id SET l.user_id = lu.user_id');

        // 3. Add foreign key
        Schema::table('lelangs', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        // 4. Drop Pivot Table
        Schema::dropIfExists('lelang_user');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('lelang_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('lelang_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });

        DB::statement('INSERT IGNORE INTO lelang_user (user_id, lelang_id, created_at, updated_at) SELECT user_id, id, NOW(), NOW() FROM lelangs WHERE user_id IS NOT NULL');

        Schema::table('lelangs', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};
