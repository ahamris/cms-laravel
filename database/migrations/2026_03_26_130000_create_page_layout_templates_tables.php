<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('page_layout_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('page_layout_template_rows', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_layout_template_id')
                ->constrained('page_layout_templates')
                ->cascadeOnDelete();
            $table->unsignedInteger('sort_order')->default(0);
            $table->string('label');
            $table->timestamps();

            $table->index(['page_layout_template_id', 'sort_order'], 'plt_row_tpl_sort_idx');
        });

        Schema::table('pages', function (Blueprint $table) {
            $table->foreignId('page_layout_template_id')
                ->nullable()
                ->after('template')
                ->constrained('page_layout_templates')
                ->nullOnDelete();
        });

        Schema::create('page_layout_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_id')->constrained('pages')->cascadeOnDelete();
            $table->foreignId('page_layout_template_row_id')
                ->constrained('page_layout_template_rows')
                ->cascadeOnDelete();
            $table->foreignId('element_id')
                ->nullable()
                ->constrained('elements')
                ->nullOnDelete();
            $table->timestamps();

            $table->unique(['page_id', 'page_layout_template_row_id'], 'plt_assign_page_row_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('page_layout_assignments');

        Schema::table('pages', function (Blueprint $table) {
            $table->dropConstrainedForeignId('page_layout_template_id');
        });

        Schema::dropIfExists('page_layout_template_rows');
        Schema::dropIfExists('page_layout_templates');
    }
};
