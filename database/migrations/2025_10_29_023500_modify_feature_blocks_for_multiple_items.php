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
        Schema::table('feature_blocks', function (Blueprint $table) {
            // Drop old single-item columns
            $table->dropColumn([
                'title',
                'subtitle',
                'content',
                'image',
                'button_text',
                'button_url',
            ]);

            // Add new JSON column for multiple items
            $table->json('items')->after('identifier');

            // Add optional section title and subtitle
            $table->string('section_title')->nullable()->after('identifier');
            $table->string('section_subtitle')->nullable()->after('section_title');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('feature_blocks', function (Blueprint $table) {
            // Remove JSON column
            $table->dropColumn(['items', 'section_title', 'section_subtitle']);

            // Restore old columns
            $table->string('title')->after('identifier');
            $table->string('subtitle')->nullable()->after('title');
            $table->text('content')->nullable()->after('subtitle');
            $table->string('image')->nullable()->after('content');
            $table->string('button_text')->nullable()->after('image');
            $table->string('button_url')->nullable()->after('button_text');
        });
    }
};
