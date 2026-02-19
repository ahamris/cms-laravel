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
        Schema::create('homepage_sections', function (Blueprint $table) {
            $table->id();
            $table->string('section_name'); // Section 1, Section 2, etc.
            $table->string('module_type'); // hero, solution, call-action, features, changelog, blog, etc.
            $table->string('identifier')->nullable(); // Specific identifier for the module
            $table->text('title')->nullable(); // Changeable title
            $table->text('description')->nullable(); // Changeable description
            $table->string('button_text')->nullable(); // Changeable image
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
        Schema::dropIfExists('homepage_sections');
    }
};
