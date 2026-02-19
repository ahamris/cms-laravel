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
        Schema::create('pricing_plans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('organization_category')->nullable();
            $table->string('organization_category_description')->nullable();
            $table->decimal('price', 10);
            $table->decimal('discounted_price', 10)->nullable();
            $table->integer('discount_percentage')->nullable();
            $table->text('description')->nullable();
            $table->json('features')->nullable();
            $table->string('button_text')->default('Start today →');
            $table->string('button_url')->default('/trial');
            $table->text('footnote')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_popular')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pricing_plans');
    }
};
