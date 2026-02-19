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
        // Add marketing fields to blogs table
        Schema::table('blogs', function (Blueprint $table) {
            $table->enum('funnel_fase', ['interesseer', 'overtuig', 'activeer', 'inspireer'])->nullable()->after('long_body');
            $table->foreignId('marketing_persona_id')->nullable()->constrained()->after('funnel_fase');
            $table->foreignId('content_type_id')->nullable()->constrained()->after('marketing_persona_id');
            $table->string('primary_keyword')->nullable()->after('content_type_id');
            $table->json('secondary_keywords')->nullable()->after('primary_keyword');
            $table->text('ai_briefing')->nullable()->after('secondary_keywords');
            $table->json('seo_analysis')->nullable()->after('ai_briefing');
        });

        // Add marketing fields to pages table
        Schema::table('pages', function (Blueprint $table) {
            $table->enum('funnel_fase', ['interesseer', 'overtuig', 'activeer', 'inspireer'])->nullable()->after('long_body');
            $table->foreignId('marketing_persona_id')->nullable()->constrained()->after('funnel_fase');
            $table->foreignId('content_type_id')->nullable()->constrained()->after('marketing_persona_id');
            $table->string('primary_keyword')->nullable()->after('content_type_id');
            $table->json('secondary_keywords')->nullable()->after('primary_keyword');
            $table->text('ai_briefing')->nullable()->after('secondary_keywords');
            $table->json('seo_analysis')->nullable()->after('ai_briefing');
        });

        // Add marketing fields to services table (assuming this is like landing pages)
        Schema::table('services', function (Blueprint $table) {
            $table->enum('funnel_fase', ['interesseer', 'overtuig', 'activeer', 'inspireer'])->nullable()->after('long_body');
            $table->foreignId('marketing_persona_id')->nullable()->constrained()->after('funnel_fase');
            $table->foreignId('content_type_id')->nullable()->constrained()->after('marketing_persona_id');
            $table->string('primary_keyword')->nullable()->after('content_type_id');
            $table->json('secondary_keywords')->nullable()->after('primary_keyword');
            $table->text('ai_briefing')->nullable()->after('secondary_keywords');
            $table->json('seo_analysis')->nullable()->after('ai_briefing');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('blogs', function (Blueprint $table) {
            $table->dropForeign(['marketing_persona_id']);
            $table->dropForeign(['content_type_id']);
            $table->dropColumn([
                'funnel_fase', 'marketing_persona_id', 'content_type_id',
                'primary_keyword', 'secondary_keywords', 'ai_briefing', 'seo_analysis'
            ]);
        });

        Schema::table('pages', function (Blueprint $table) {
            $table->dropForeign(['marketing_persona_id']);
            $table->dropForeign(['content_type_id']);
            $table->dropColumn([
                'funnel_fase', 'marketing_persona_id', 'content_type_id',
                'primary_keyword', 'secondary_keywords', 'ai_briefing', 'seo_analysis'
            ]);
        });

        Schema::table('services', function (Blueprint $table) {
            $table->dropForeign(['marketing_persona_id']);
            $table->dropForeign(['content_type_id']);
            $table->dropColumn([
                'funnel_fase', 'marketing_persona_id', 'content_type_id',
                'primary_keyword', 'secondary_keywords', 'ai_briefing', 'seo_analysis'
            ]);
        });
    }
};
