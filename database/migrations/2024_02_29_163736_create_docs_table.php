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
        Schema::create('docs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('type_doc_id')->constrained('type_docs');
            $table->foreignId('user_id')->constrained('users');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('original_file_name');
            $table->string('path_file');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('docs', function(Blueprint $table){
            $table->dropForeign('docs_type_doc_id_foreign');
            $table->dropForeign('docs_user_id_foreign');
        });
        Schema::dropIfExists('docs');
    }
};
