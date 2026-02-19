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
        Schema::create('case_studies', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('excerpt')->nullable();
            $table->string('client_name');
            $table->string('client_company');
            $table->string('client_industry')->nullable();
            $table->string('client_logo')->nullable();
            $table->text('challenge'); // Uitdaging
            $table->text('solution'); // Oplossing
            $table->text('results'); // Resultaten
            $table->json('metrics')->nullable(); // Quantifiable results
            $table->text('key_quote');
            $table->string('featured_image')->nullable();
            $table->json('product_feature_ids')->nullable(); // Related features used
            $table->json('tags')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_published')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('case_studies');
    }
};
