<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('rentals', function (Blueprint $table) {
            
            $table->integer('area')->nullable()->after('documents');
            $table->string('location')->nullable()->after('area');
            $table->integer('quantity')->nullable()->after('location');
            $table->string('design_file')->nullable()->after('quantity');
            
            
        });
    }
};
