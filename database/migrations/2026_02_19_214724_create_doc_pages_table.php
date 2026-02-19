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
        Schema::create('doc_pages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('doc_section_id');
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
    }
};
