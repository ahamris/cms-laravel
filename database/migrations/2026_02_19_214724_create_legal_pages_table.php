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
        Schema::create('legal_pages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->string('title')->nullable();
            $table->string('slug')->nullable()->unique('legacy_pages_slug_unique');
            $table->longText('body')->nullable();
            $table->boolean('is_active')->default(true);
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('keywords')->nullable();
            $table->string('image')->nullable();
            $table->integer('current_version')->default(1);
            $table->boolean('versioning_enabled')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('legal_pages');
    }
};
