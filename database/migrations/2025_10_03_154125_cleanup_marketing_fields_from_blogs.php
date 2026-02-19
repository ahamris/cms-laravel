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
        // Clean up any existing marketing fields from failed migrations
        Schema::table('blogs', function (Blueprint $table) {
            $columns = Schema::getColumnListing('blogs');

            // Drop foreign keys first
            if (in_array('marketing_persona_id', $columns)) {
                try {
                    $table->dropForeign(['marketing_persona_id']);
                } catch (Exception $e) {
                    // Foreign key might not exist
                }
            }

            if (in_array('content_type_id', $columns)) {
                try {
                    $table->dropForeign(['content_type_id']);
                } catch (Exception $e) {
                    // Foreign key might not exist
                }
            }

            // Now drop columns
            $fieldsToRemove = ['funnel_fase', 'marketing_persona_id', 'content_type_id', 'primary_keyword', 'secondary_keywords', 'ai_briefing', 'seo_analysis'];

            foreach ($fieldsToRemove as $field) {
                if (in_array($field, $columns)) {
                    $table->dropColumn($field);
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to reverse cleanup
    }
};
