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
        Schema::table('pages', function (Blueprint $table) {
            $table->string('design_type')->nullable()->after('page_type'); // 'general' or 'custom'
            $table->string('header_block')->nullable()->after('design_type');
            $table->string('footer_block')->nullable()->after('header_block');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn(['design_type', 'header_block', 'footer_block']);
        });
    }
};
