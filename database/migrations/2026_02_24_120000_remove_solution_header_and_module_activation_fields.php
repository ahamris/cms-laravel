<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Remove header configuration and module activation fields from solutions.
     */
    public function up(): void
    {
        Schema::table('solutions', function (Blueprint $table) {
            $table->dropColumn([
                'show_buttons',
                'button1_text',
                'button1_url',
                'button2_text',
                'button2_url',
                'show_cta',
                'show_modules_header',
                'show_news_articles',
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('solutions', function (Blueprint $table) {
            $table->boolean('show_buttons')->default(true)->after('long_body');
            $table->string('button1_text')->nullable()->default('Start Gratis Proefperiode')->after('show_buttons');
            $table->string('button1_url')->nullable()->default('#')->after('button1_text');
            $table->string('button2_text')->nullable()->default('Neem Contact Op')->after('button1_url');
            $table->string('button2_url')->nullable()->default('#')->after('button2_text');
            $table->boolean('show_news_articles')->default(false)->after('show_knowledge_grid');
            $table->boolean('show_modules_header')->default(false)->after('show_news_articles');
            $table->boolean('show_cta')->default(false)->after('show_modules_header');
        });
    }
};
