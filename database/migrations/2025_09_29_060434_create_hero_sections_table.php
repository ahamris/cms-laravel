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
        Schema::create('hero_sections', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('subtitle');
            $table->json('list_items')->nullable(); // For bullet points/features list
            $table->string('button1')->nullable(); // First button link
            $table->string('button1_text')->nullable(); // First button text
            $table->string('button2')->nullable(); // Second button link
            $table->string('button2_text')->nullable(); // Second button text
            $table->string('image')->nullable(); // Background image
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hero_sections');
    }
};
