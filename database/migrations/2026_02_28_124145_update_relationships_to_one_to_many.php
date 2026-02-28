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
        Schema::table('fieldtrips', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->after('id');
        });
        Schema::table('rentals', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->after('id');
        });
        Schema::table('tenants', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->after('id');
        });

        // 2. Migrate data
        DB::statement('UPDATE fieldtrips f JOIN fieldtrip_user fu ON f.id = fu.fieldtrip_id SET f.user_id = fu.user_id');
        DB::statement('UPDATE rentals r JOIN rental_user ru ON r.id = ru.rental_id SET r.user_id = ru.user_id');
        DB::statement('UPDATE tenants t JOIN tenant_user tu ON t.id = tu.tenant_id SET t.user_id = tu.user_id');

        // 3. Add foreign key constraints
        Schema::table('fieldtrips', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
        Schema::table('rentals', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
        Schema::table('tenants', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        // 4. Drop Pivot Tables
        Schema::dropIfExists('fieldtrip_user');
        Schema::dropIfExists('rental_user');
        Schema::dropIfExists('tenant_user');
        Schema::dropIfExists('slot_user');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('fieldtrip_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('fieldtrip_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
        Schema::create('rental_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('rental_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
        Schema::create('tenant_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
        Schema::create('slot_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('slot_id')->constrained('slots')->onDelete('cascade');
            $table->timestamps();
        });

        DB::statement('INSERT IGNORE INTO fieldtrip_user (user_id, fieldtrip_id, created_at, updated_at) SELECT user_id, id, NOW(), NOW() FROM fieldtrips WHERE user_id IS NOT NULL');
        DB::statement('INSERT IGNORE INTO rental_user (user_id, rental_id, created_at, updated_at) SELECT user_id, id, NOW(), NOW() FROM rentals WHERE user_id IS NOT NULL');
        DB::statement('INSERT IGNORE INTO tenant_user (user_id, tenant_id, created_at, updated_at) SELECT user_id, id, NOW(), NOW() FROM tenants WHERE user_id IS NOT NULL');
        DB::statement('INSERT IGNORE INTO slot_user (user_id, slot_id, created_at, updated_at) SELECT user_id, id, NOW(), NOW() FROM slots WHERE user_id IS NOT NULL');

        Schema::table('fieldtrips', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
        Schema::table('rentals', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
        Schema::table('tenants', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};
