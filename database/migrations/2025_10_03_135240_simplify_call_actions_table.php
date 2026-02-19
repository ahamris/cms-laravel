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
        Schema::table('call_actions', function (Blueprint $table) {
            // Remove unnecessary fields
            $table->dropColumn([
                'description',
                'price_text',
                'price_button_text',
                'price_button_url',
                'stats_number',
                'stats_text',
                'stats_button_text',
                'stats_button_url',
            ]);

            // Add simple content field
            $table->text('content')->after('title');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('call_actions', function (Blueprint $table) {
            // Add back the removed fields
            $table->text('description')->nullable()->after('title');
            $table->string('price_text')->nullable();
            $table->string('price_button_text')->nullable();
            $table->string('price_button_url')->nullable();
            $table->string('stats_number')->nullable();
            $table->string('stats_text')->nullable();
            $table->string('stats_button_text')->nullable();
            $table->string('stats_button_url')->nullable();

            // Remove the content field
            $table->dropColumn('content');
        });
    }
};
