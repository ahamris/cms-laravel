<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Consolidated: legacy_pages creation, columns, rename to legal_pages, versioning.
     */
    public function up(): void
    {
        Schema::create('legacy_pages', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('title')->nullable();
            $table->string('slug')->nullable();
            $table->longText('body')->nullable();
            $table->boolean('is_active')->default(true);
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('keywords')->nullable();
            $table->string('image')->nullable();
            $table->unique('slug');
        });

        Schema::rename('legacy_pages', 'legal_pages');

        Schema::create('legal_page_versions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('legal_page_id')->constrained('legal_pages')->onDelete('cascade');
            $table->integer('version_number');
            $table->string('title')->nullable();
            $table->string('slug')->nullable();
            $table->longText('body')->nullable();
            $table->boolean('is_active')->default(true);
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('keywords')->nullable();
            $table->string('image')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->text('version_notes')->nullable();
            $table->timestamps();

            $table->index('legal_page_id');
            $table->index('version_number');
            $table->unique(['legal_page_id', 'version_number']);
        });

        Schema::table('legal_pages', function (Blueprint $table) {
            $table->integer('current_version')->default(1)->after('image');
            $table->boolean('versioning_enabled')->default(true)->after('current_version');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('legal_pages', function (Blueprint $table) {
            $table->dropColumn(['current_version', 'versioning_enabled']);
        });

        Schema::dropIfExists('legal_page_versions');
        Schema::rename('legal_pages', 'legacy_pages');
        Schema::dropIfExists('legacy_pages');
    }
};
