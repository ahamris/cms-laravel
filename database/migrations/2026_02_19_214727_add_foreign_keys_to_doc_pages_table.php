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
        Schema::table('doc_pages', function (Blueprint $table) {
            $table->foreign(['doc_section_id'])->references(['id'])->on('doc_sections')->onUpdate('restrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('doc_pages', function (Blueprint $table) {
            $table->dropForeign('doc_pages_doc_section_id_foreign');
        });
    }
};
