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
        Schema::table('solutions', function (Blueprint $table) {
            // Header configuration fields
            $table->boolean('show_buttons')->default(true);
            
            $table->string('button1_text')->nullable()->default('Start Gratis Proefperiode');
            $table->string('button1_url')->nullable()->default('#');
            
            $table->string('button2_text')->nullable()->default('Neem Contact Op');
            $table->string('button2_url')->nullable()->default('#');
            
            
            // Module activation fields
            $table->boolean('show_knowledge_grid')->default(false);
            $table->boolean('show_news_articles')->default(false);
            $table->boolean('show_modules_header')->default(false);
            $table->boolean('show_cta')->default(false);
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('solutions', function (Blueprint $table) {
            // Drop header configuration fields
            $table->dropColumn([
                'button1_text',
                'button1_url',
                'button2_text',
                'button2_url',
                'show_buttons',
                'show_cta',
                'show_knowledge_grid',
                'show_news_articles',
                'show_modules_header',
            ]);
        });
    }
};
