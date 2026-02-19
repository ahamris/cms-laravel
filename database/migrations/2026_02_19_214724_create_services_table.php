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
        Schema::create('services', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->unsignedBigInteger('service_category_id')->nullable()->index('services_service_category_id_foreign');
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('short_body');
            $table->longText('long_body');
            $table->enum('funnel_fase', ['interesseer', 'overtuig', 'activeer', 'inspireer'])->nullable();
            $table->string('image')->nullable();
            $table->json('gallery')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->string('meta_canonical_url')->nullable();
            $table->string('meta_robots')->default('index,follow');
            $table->string('og_title')->nullable();
            $table->text('og_description')->nullable();
            $table->string('og_image')->nullable();
            $table->string('og_type')->default('article');
            $table->string('twitter_card')->default('summary_large_image');
            $table->string('twitter_title')->nullable();
            $table->text('twitter_description')->nullable();
            $table->string('twitter_image')->nullable();
            $table->decimal('price', 10)->nullable();
            $table->string('price_type')->nullable();
            $table->text('price_description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->integer('sort_order')->default(0);
            $table->timestamp('published_at')->nullable();
            $table->unsignedBigInteger('marketing_persona_id')->nullable()->index('services_marketing_persona_id_foreign');
            $table->unsignedBigInteger('content_type_id')->nullable()->index('services_content_type_id_foreign');
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
        Schema::dropIfExists('services');
    }
};
