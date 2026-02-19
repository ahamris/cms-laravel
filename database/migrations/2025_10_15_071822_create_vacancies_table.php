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
        Schema::create('vacancies', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('location')->nullable();
            $table->enum('short_code', ['BE', 'FE', 'MM', 'DO', 'QA', 'AI', 'HR', 'IT', 'PM'])->default('BE');
            $table->enum('type', ['full-time', 'part-time', 'contract', 'remote', 'project-based'])->default('full-time');
            $table->string('hours_per_week')->nullable();
            $table->string('experience_level')->nullable();
            $table->string('category')->nullable();
            $table->string('department')->nullable();
            $table->text('description')->nullable();
            $table->text('requirements')->nullable();
            $table->text('responsibilities')->nullable();
            $table->string('salary_range')->nullable();
            $table->boolean('is_active')->default(true);
            $table->date('closing_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vacancies');
    }
};
