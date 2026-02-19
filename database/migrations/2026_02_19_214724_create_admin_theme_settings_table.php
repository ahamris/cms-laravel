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
        Schema::create('admin_theme_settings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('base_color')->default('zinc');
            $table->string('accent_color')->default('indigo');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_theme_settings');
    }
};
