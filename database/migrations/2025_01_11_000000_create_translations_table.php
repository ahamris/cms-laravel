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
            $table->id();
            $table->string('translation_key')->index(); // Translation key (e.g., 'welcome.message')
            $table->string('locale', 10)->index(); // Language code (e.g., 'en', 'nl', 'de')
            $table->text('translation_value'); // Translation value
            $table->string('group_name')->nullable()->index(); // Group/namespace (e.g., 'auth', 'validation')
            $table->text('description')->nullable(); // Description for translators
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();

            // Ensure unique combination of translation_key, locale, and group_name
            $table->unique(['translation_key', 'locale', 'group_name']);

            // Composite indexes for better performance
            $table->index(['locale', 'is_active']);
            $table->index(['group_name', 'locale']);
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
