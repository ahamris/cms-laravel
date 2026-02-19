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
        Schema::create('pages', function (Blueprint $table) {
            $table->bigIncrements('id');
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
            $table->enum('funnel_fase', ['interesseer', 'overtuig', 'activeer', 'inspireer'])->nullable();
            $table->unsignedBigInteger('marketing_persona_id')->nullable()->index('pages_marketing_persona_id_foreign');
            $table->unsignedBigInteger('content_type_id')->nullable()->index('pages_content_type_id_foreign');
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
