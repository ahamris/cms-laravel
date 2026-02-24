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
        Schema::create('mega_menu_sidebars', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mega_menu_item_id')->unique();
            $table->string('title');
            $table->text('description')->nullable();
            $table->json('tags')->nullable();
            $table->timestamps();

            $table->foreign('mega_menu_item_id')
                ->references('id')
                ->on('mega_menu_items')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mega_menu_sidebars');
    }
};
