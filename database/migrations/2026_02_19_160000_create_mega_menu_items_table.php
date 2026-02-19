<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Consolidated: mega_menu_items final schema (parent_id, page_id; no badges, flyout, footer actions).
     */
    public function up(): void
    {
        Schema::create('mega_menu_items', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->unsignedBigInteger('parent_id')->nullable();
            $table->integer('order')->default(0);

            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->text('description')->nullable();
            $table->string('url')->nullable();

            $table->string('icon')->nullable();
            $table->string('icon_bg_color')->nullable();

            $table->boolean('is_mega_menu')->default(false);
            $table->boolean('is_active')->default(true);
            $table->boolean('open_in_new_tab')->default(false);

            $table->foreignId('page_id')
                ->nullable()
                ->constrained('pages')
                ->onDelete('cascade');

            $table->index(['parent_id', 'order']);
            $table->index(['is_active']);
            $table->index('page_id');
            $table->foreign('parent_id')->references('id')->on('mega_menu_items')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mega_menu_items');
    }
};
