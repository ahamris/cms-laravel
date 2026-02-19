<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Consolidated: doc_versions, doc_sections, doc_pages in dependency order.
     */
    public function up(): void
    {
        Schema::create('doc_versions', function (Blueprint $table) {
            $table->id();
            $table->string('version')->unique();
            $table->string('name');
            $table->boolean('is_active')->default(true);
            $table->boolean('is_default')->default(false);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('doc_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doc_version_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['doc_version_id', 'slug']);
        });

        Schema::create('doc_pages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doc_section_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->string('slug');
            $table->longText('content');
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['doc_section_id', 'slug']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doc_pages');
        Schema::dropIfExists('doc_sections');
        Schema::dropIfExists('doc_versions');
    }
};
