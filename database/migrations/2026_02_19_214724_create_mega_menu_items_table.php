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
        Schema::create('mega_menu_items', function (Blueprint $table) {
            $table->bigIncrements('id');
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
            $table->boolean('is_active')->default(true)->index();
            $table->boolean('open_in_new_tab')->default(false);
            $table->unsignedBigInteger('page_id')->nullable()->index();

            $table->index(['parent_id', 'order']);
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
