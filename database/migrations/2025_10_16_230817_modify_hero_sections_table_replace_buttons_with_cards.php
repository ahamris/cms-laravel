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
        Schema::table('hero_sections', function (Blueprint $table) {
            // Remove old button fields
            $table->dropColumn([
                'primary_button_subtitle',
                'secondary_button_subtitle',
            ]);

            // Add new card fields
            // Card 1
            $table->string('card1_icon')->nullable()->after('list_items');
            $table->enum('card1_bgcolor', ['bg-primary', 'bg-secondary'])->default('bg-primary')->after('card1_icon');
            $table->string('card1_title')->nullable()->after('card1_bgcolor');
            $table->string('card1_description')->nullable()->after('card1_title');

            // Card 2
            $table->string('card2_icon')->nullable()->after('card1_description');
            $table->enum('card2_bgcolor', ['bg-primary', 'bg-secondary'])->default('bg-secondary')->after('card2_icon');
            $table->string('card2_title')->nullable()->after('card2_bgcolor');
            $table->string('card2_description')->nullable()->after('card2_title');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hero_sections', function (Blueprint $table) {
            // Remove new card fields
            $table->dropColumn([
                'card1_icon',
                'card1_bgcolor',
                'card1_title',
                'card1_description',
                'card2_icon',
                'card2_bgcolor',
                'card2_title',
                'card2_description'
            ]);

            // Restore old button fields
            $table->string('primary_button_subtitle')->nullable();
            $table->string('secondary_button_subtitle')->nullable();
        });
    }
};
