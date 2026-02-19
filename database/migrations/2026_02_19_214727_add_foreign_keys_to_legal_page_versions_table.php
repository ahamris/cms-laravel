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
        Schema::table('legal_page_versions', function (Blueprint $table) {
            $table->foreign(['created_by'])->references(['id'])->on('users')->onUpdate('restrict')->onDelete('set null');
            $table->foreign(['legal_page_id'])->references(['id'])->on('legal_pages')->onUpdate('restrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('legal_page_versions', function (Blueprint $table) {
            $table->dropForeign('legal_page_versions_created_by_foreign');
            $table->dropForeign('legal_page_versions_legal_page_id_foreign');
        });
    }
};
