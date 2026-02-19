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
        Schema::table('legacy_pages', function (Blueprint $table) {
            $table->dropColumn('selected_sections');
            $table->json('selected_call_actions')->nullable()->after('meta_description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('legacy_pages', function (Blueprint $table) {
            $table->dropColumn('selected_call_actions');
            $table->json('selected_sections')->nullable()->after('meta_description');
        });
    }
};
