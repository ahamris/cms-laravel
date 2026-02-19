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
        Schema::create('ai_service_settings', function (Blueprint $table) {
            $table->id();
            $table->string('service')->unique(); // 'groq' or 'gemini'
            $table->string('api_key')->nullable();
            $table->string('model')->default('default');
            $table->boolean('is_active')->default(false);
            $table->integer('priority')->default(0); // Lower number = higher priority
            $table->timestamps();
            
            $table->index('service');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_service_settings');
    }
};
