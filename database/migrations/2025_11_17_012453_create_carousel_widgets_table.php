<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Consolidated: create carousel_widgets with show_author, view_all fields; no number_of_rows.
     */
    public function up(): void
    {
        Schema::create('carousel_widgets', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('identifier')->unique();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->string('data_source')->default('blog');
            $table->unsignedBigInteger('blog_category_id')->nullable();
            $table->integer('items_per_row')->default(3);
            $table->integer('total_items')->default(6);
            $table->boolean('show_arrows')->default(true);
            $table->boolean('show_dots')->default(true);
            $table->boolean('show_author')->default(true);
            $table->boolean('autoplay')->default(false);
            $table->integer('autoplay_speed')->default(3000);
            $table->boolean('infinite_loop')->default(true);
            $table->boolean('show_view_all_button')->default(false);
            $table->string('view_all_title')->nullable();
            $table->text('view_all_description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->json('settings')->nullable();
            $table->timestamps();

            $table->foreign('blog_category_id')->references('id')->on('blog_categories')->onDelete('set null');
            $table->index('identifier');
            $table->index('is_active');
            $table->index('sort_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carousel_widgets');
    }
};
