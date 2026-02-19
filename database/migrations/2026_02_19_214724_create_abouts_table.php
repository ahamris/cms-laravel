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
        Schema::create('abouts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->string('anchor')->unique();
            $table->string('nav_title');
            $table->string('title');
            $table->text('subtitle')->nullable();
            $table->text('short_body')->nullable();
            $table->text('long_body')->nullable();
            $table->json('list_items')->nullable();
            $table->string('link_text')->nullable();
            $table->text('testimonial_quote')->nullable();
            $table->string('testimonial_author')->nullable();
            $table->string('testimonial_company')->nullable();
            $table->string('image')->nullable();
            $table->enum('image_position', ['left', 'right'])->default('right');
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->string('slug')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('abouts');
    }
};
