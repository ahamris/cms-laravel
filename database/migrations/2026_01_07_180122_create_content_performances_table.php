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

        Schema::dropIfExists('content_performances');

        Schema::create('content_performances', function (Blueprint $table) {
            $table->id();
            $table->morphs('contentable'); // Polymorphic: Blog, SocialMediaPost, etc.
            $table->decimal('ctr', 5, 4)->nullable(); // Click-through rate
            $table->integer('impressions')->nullable();
            $table->decimal('engagement', 5, 2)->nullable(); // Engagement rate percentage
            $table->json('ranking_data')->nullable(); // Keyword rankings, positions, etc.
            $table->timestamp('measured_at');
            $table->timestamps();

            // Index for polymorphic relation and measured_at (shorter name to avoid MySQL 64 char limit)
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
