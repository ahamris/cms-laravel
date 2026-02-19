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
        Schema::create('pricing_boosters', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., 'Project management Booster'
            $table->string('slug')->unique();
            $table->decimal('price', 10, 2); // Monthly price
            $table->text('description')->nullable();
            $table->string('link_text')->default('Read more →');
            $table->string('link_url')->default('/trial');
            $table->text('footnote')->nullable(); // e.g., "*Price excludes VAT per account"
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pricing_boosters');
    }
};
