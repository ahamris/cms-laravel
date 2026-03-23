<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('element_static_page', function (Blueprint $table) {
            $table->id();
            $table->foreignId('element_id')->constrained('elements')->cascadeOnDelete();
            $table->foreignId('static_page_id')->constrained('static_pages')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['element_id', 'static_page_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('element_static_page');
    }
};
