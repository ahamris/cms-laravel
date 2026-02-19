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
        Schema::table('content_plans', function (Blueprint $table) {
            $table->foreign(['intent_brief_id'])->references(['id'])->on('intent_briefs')->onUpdate('restrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('content_plans', function (Blueprint $table) {
            $table->dropForeign('content_plans_intent_brief_id_foreign');
        });
    }
};
