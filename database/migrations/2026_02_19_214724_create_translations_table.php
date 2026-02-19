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
        Schema::create('translations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('translation_key')->index();
            $table->string('locale', 10)->index();
            $table->text('translation_value');
            $table->string('group_name')->nullable()->index();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();

            $table->index(['group_name', 'locale']);
            $table->index(['locale', 'is_active']);
            $table->unique(['translation_key', 'locale', 'group_name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('translations');
    }
};
