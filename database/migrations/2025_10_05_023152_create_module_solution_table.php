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
        Schema::create('module_solution', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('module_id')->constrained()->onDelete('cascade');
            $table->foreignId('solution_id')->constrained()->onDelete('cascade');

            $table->unique(['module_id', 'solution_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('module_solution');
    }
};
