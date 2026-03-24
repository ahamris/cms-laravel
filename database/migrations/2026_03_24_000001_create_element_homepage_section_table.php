<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('element_homepage_section', function (Blueprint $table) {
            $table->id();
            $table->foreignId('element_id')->constrained('elements')->cascadeOnDelete();
            $table->foreignId('homepage_section_id')->constrained('homepage_sections')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['element_id', 'homepage_section_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('element_homepage_section');
    }
};
