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
        Schema::create('carousel_widgets', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('identifier')->unique();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->string('data_source')->default('blog'); // blog, custom, etc.
            $table->unsignedBigInteger('blog_category_id')->nullable();
            $table->integer('items_per_row')->default(3); // 1, 2, 3, 4, 6
            $table->integer('number_of_rows')->default(2); // 1, 2, 3, etc.
            $table->integer('total_items')->default(6); // Total items to show
            $table->boolean('show_arrows')->default(true);
            $table->boolean('show_dots')->default(true);
            $table->boolean('autoplay')->default(false);
            $table->integer('autoplay_speed')->default(3000); // milliseconds
            $table->boolean('infinite_loop')->default(true);
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
