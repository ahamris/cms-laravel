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
        Schema::create('blogs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('content_plan_id')->nullable()->index('blogs_content_plan_id_foreign');
            $table->enum('autopilot_mode', ['assisted', 'guided', 'full_autopilot'])->nullable();
            $table->timestamps();
            $table->unsignedBigInteger('blog_category_id')->nullable()->index('blogs_blog_category_id_foreign');
            $table->string('title')->nullable();
            $table->text('short_body')->nullable();
            $table->text('long_body')->nullable();
            $table->string('image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->unsignedBigInteger('author_id')->nullable()->index('blogs_author_id_foreign');
            $table->string('slug')->nullable();
            $table->integer('seo_score')->nullable();
            $table->enum('seo_status', ['google-friendly', 'needs-improvement', 'high-potential'])->nullable();
            $table->text('meta_keywords')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blogs');
    }
};
