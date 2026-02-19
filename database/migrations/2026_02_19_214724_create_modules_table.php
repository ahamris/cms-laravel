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
        Schema::create('modules', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->string('title');
            $table->text('short_body')->nullable();
            $table->longText('long_body')->nullable();
            $table->string('slug')->unique();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->string('meta_title', 60)->nullable();
            $table->string('meta_description', 160)->nullable();
            $table->text('meta_keywords')->nullable();
            $table->string('anchor')->nullable();
            $table->string('nav_title')->nullable();
            $table->string('subtitle')->nullable();
            $table->json('list_items')->nullable();
            $table->string('link_text')->nullable();
            $table->string('link_url')->nullable();
            $table->text('testimonial_quote')->nullable();
            $table->string('testimonial_author')->nullable();
            $table->string('testimonial_company')->nullable();
            $table->string('image')->nullable();
            $table->enum('image_position', ['left', 'right'])->default('right');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modules');
    }
};
