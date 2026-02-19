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
            $table->id();
            $table->string('name'); // e.g., 'SMART', 'GROW', 'FLOW'
            $table->string('slug')->unique(); // e.g., 'smart', 'grow', 'flow'
            $table->decimal('price', 10, 2); // Monthly price
            $table->decimal('discounted_price', 10, 2)->nullable(); // Discounted price
            $table->integer('discount_percentage')->nullable(); // e.g., 25
            $table->text('description')->nullable(); // Short description
            $table->json('features')->nullable(); // Array of included features
            $table->string('button_text')->default('Start today →');
            $table->string('button_url')->default('/trial');
            $table->text('footnote')->nullable(); // e.g., "€ 11/month for 10,000 extra contacts"
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_popular')->default(false); // Highlight as popular
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
