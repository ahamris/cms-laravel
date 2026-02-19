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
        Schema::table('pages', function (Blueprint $table) {
            $table->foreign(['content_type_id'])->references(['id'])->on('content_types')->onUpdate('restrict')->onDelete('set null');
            $table->foreign(['marketing_persona_id'])->references(['id'])->on('marketing_personas')->onUpdate('restrict')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropForeign('pages_content_type_id_foreign');
            $table->dropForeign('pages_marketing_persona_id_foreign');
        });
    }
};
