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
        Schema::create('content_performances', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('contentable_type');
            $table->unsignedBigInteger('contentable_id');
            $table->decimal('ctr', 5, 4)->nullable();
            $table->integer('impressions')->nullable();
            $table->decimal('engagement', 5)->nullable();
            $table->json('ranking_data')->nullable();
            $table->timestamp('measured_at');
            $table->timestamps();

            $table->index(['contentable_type', 'contentable_id']);
            $table->index(['contentable_type', 'contentable_id', 'measured_at'], 'content_perf_poly_measured_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('content_performances');
    }
};
