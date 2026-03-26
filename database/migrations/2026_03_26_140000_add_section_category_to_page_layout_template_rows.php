<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('page_layout_template_rows', function (Blueprint $table) {
            $table->string('section_category', 50)->default('content');
        });
    }

    public function down(): void
    {
        Schema::table('page_layout_template_rows', function (Blueprint $table) {
            $table->dropColumn('section_category');
        });
    }
};
