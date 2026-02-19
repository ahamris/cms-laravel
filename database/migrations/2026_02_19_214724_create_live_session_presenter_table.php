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
        Schema::create('live_session_presenter', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('live_session_id');
            $table->unsignedBigInteger('presenter_id')->index('live_session_presenter_presenter_id_foreign');
            $table->boolean('is_primary')->default(false);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index(['live_session_id', 'is_primary']);
            $table->unique(['live_session_id', 'presenter_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('live_session_presenter');
    }
};
