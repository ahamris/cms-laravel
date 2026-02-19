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
        Schema::table('live_session_presenter', function (Blueprint $table) {
            $table->foreign(['live_session_id'])->references(['id'])->on('live_sessions')->onUpdate('restrict')->onDelete('cascade');
            $table->foreign(['presenter_id'])->references(['id'])->on('presenters')->onUpdate('restrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('live_session_presenter', function (Blueprint $table) {
            $table->dropForeign('live_session_presenter_live_session_id_foreign');
            $table->dropForeign('live_session_presenter_presenter_id_foreign');
        });
    }
};
