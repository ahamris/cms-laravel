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
            $table->bigIncrements('id');
            $table->string('service')->index();
            $table->string('api_key')->nullable();
            $table->string('model')->default('default');
            $table->boolean('is_active')->default(false)->index();
            $table->integer('priority')->default(0);
            $table->timestamps();

            $table->unique(['service']);
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
