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
        Schema::table('blogs', function (Blueprint $table) {
            $columns = Schema::getColumnListing('blogs');
            
            // Add content_plan_id if it doesn't exist
            if (!in_array('content_plan_id', $columns)) {
                $table->foreignId('content_plan_id')->nullable()->after('id')->constrained()->nullOnDelete();
            }
            
            // Add autopilot_mode if it doesn't exist
            if (!in_array('autopilot_mode', $columns)) {
                $table->enum('autopilot_mode', ['assisted', 'guided', 'full_autopilot'])->nullable()->after('content_plan_id');
            }
            
            // Add seo_score if it doesn't exist
            if (!in_array('seo_score', $columns)) {
                $table->integer('seo_score')->nullable()->after('slug'); // 0-100 score
            }
            
            // Add seo_status if it doesn't exist
            if (!in_array('seo_status', $columns)) {
                $table->enum('seo_status', ['google-friendly', 'needs-improvement', 'high-potential'])->nullable()->after('seo_score');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('blogs', function (Blueprint $table) {
            $table->dropForeign(['content_plan_id']);
            $table->dropColumn(['content_plan_id', 'autopilot_mode', 'seo_score', 'seo_status']);
        });
    }
};
