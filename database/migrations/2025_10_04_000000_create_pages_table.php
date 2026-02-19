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
        if (Schema::hasTable('pages')) {
            return;
        }

        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->string('title')->nullable();
            $table->string('slug')->nullable();
            $table->text('meta_keywords')->nullable();

            $table->text('short_body')->nullable();
            $table->longText('long_body')->nullable();

            $table->string('meta_title')->nullable();
            $table->text('meta_body')->nullable();

            $table->string('image')->nullable();
            $table->string('icon')->nullable();

            $table->boolean('is_active')->default(true);

            // Marketing automation (requires marketing_personas + content_types to exist)
            $table->enum('funnel_fase', ['interesseer', 'overtuig', 'activeer', 'inspireer'])->nullable();
            $table->foreignId('marketing_persona_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('content_type_id')->nullable()->constrained()->nullOnDelete();
            $table->string('primary_keyword')->nullable();
            $table->json('secondary_keywords')->nullable();
            $table->text('ai_briefing')->nullable();
            $table->json('seo_analysis')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
