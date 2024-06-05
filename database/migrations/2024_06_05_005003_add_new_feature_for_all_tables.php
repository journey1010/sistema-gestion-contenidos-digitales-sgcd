<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function(Blueprint $table){
            $table->string('app_name')->nullable();
        });

        Schema::table('publicaciones', function(Blueprint $table){
            $table->string('app_name')->nullable();
        });

        Schema::table('national_reports', function(Blueprint $table){
            $table->string('app_name')->nullable();
        });

        Schema::table('docs', function(Blueprint $table){
            $table->string('app_name')->nullable();
        });

        Schema::table('posts', function(Blueprint $table){
            $table->string('app_name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
