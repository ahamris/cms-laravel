<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('page_layout_templates', function (Blueprint $table) {
            $table->text('description')->nullable()->after('name');
            $table->boolean('use_header_section')->default(false)->after('description');
            $table->boolean('use_hero_section')->default(false)->after('use_header_section');
        });

        Schema::table('page_layout_template_rows', function (Blueprint $table) {
            $table->string('row_kind', 32)->default('element')->after('page_layout_template_id');
        });

        Schema::table('page_layout_template_rows', function (Blueprint $table) {
            $table->string('section_category', 50)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('page_layout_template_rows', function (Blueprint $table) {
            $table->dropColumn('row_kind');
        });

        Schema::table('page_layout_templates', function (Blueprint $table) {
            $table->dropColumn(['description', 'use_header_section', 'use_hero_section']);
        });

    }
};
