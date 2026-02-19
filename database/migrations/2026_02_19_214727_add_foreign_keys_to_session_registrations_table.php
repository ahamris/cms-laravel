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
        Schema::table('session_registrations', function (Blueprint $table) {
            $table->foreign(['live_session_id'])->references(['id'])->on('live_sessions')->onUpdate('restrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('session_registrations', function (Blueprint $table) {
            $table->dropForeign('session_registrations_live_session_id_foreign');
        });
    }
};
