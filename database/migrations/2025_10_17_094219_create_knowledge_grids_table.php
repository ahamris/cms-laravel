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
        Schema::create('knowledge_grids', function (Blueprint $table) {
            $table->id();
            $table->string('section_identifier')->unique();
            $table->string('title')->nullable();
            $table->text('content')->nullable();
            $table->json('cards')->nullable(); // Store the 4 cards as JSON
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index(['is_active', 'sort_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('knowledge_grids');
    }
};
