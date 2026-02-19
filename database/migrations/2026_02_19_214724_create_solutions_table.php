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
        Schema::create('solutions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->string('anchor')->unique();
            $table->string('nav_title');
            $table->string('title');
            $table->text('subtitle')->nullable();
            $table->json('list_items')->nullable();
            $table->string('link_text')->nullable();
            $table->string('link_url')->nullable();
            $table->text('testimonial_quote')->nullable();
            $table->string('testimonial_author')->nullable();
            $table->string('testimonial_company')->nullable();
            $table->string('image')->nullable();
            $table->enum('image_position', ['left', 'right'])->default('right');
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->string('slug')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('short_body')->nullable();
            $table->text('long_body')->nullable();
            $table->boolean('show_buttons')->default(true);
            $table->string('button1_text')->nullable()->default('Start Gratis Proefperiode');
            $table->string('button1_url')->nullable()->default('#');
            $table->string('button2_text')->nullable()->default('Neem Contact Op');
            $table->string('button2_url')->nullable()->default('#');
            $table->boolean('show_knowledge_grid')->default(false);
            $table->boolean('show_news_articles')->default(false);
            $table->boolean('show_modules_header')->default(false);
            $table->boolean('show_cta')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solutions');
    }
};
