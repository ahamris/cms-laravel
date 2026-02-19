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
        // Drop the old table completely
        Schema::dropIfExists('mega_menu_items');
        
        // Create the new simplified structure
        Schema::create('mega_menu_items', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            
            // Hierarchical structure - parent_id NULL means root level
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->integer('order')->default(0);
            
            // Menu item details
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->text('description')->nullable();
            $table->string('url')->nullable(); // Custom link URL
            
            // Icon support
            $table->string('icon')->nullable(); // Font Awesome icon class
            $table->string('icon_bg_color')->nullable(); // Background color for icon
            
            // Root level items can be mega menu or simple menu
            $table->boolean('is_mega_menu')->default(false); // Only for root level items
            
            // Display options
            $table->boolean('is_active')->default(true);
            $table->boolean('open_in_new_tab')->default(false);
            $table->string('badge_text')->nullable(); // e.g., 'New', 'Popular'
            $table->string('badge_color')->nullable();
            
            // Indexes for performance
            $table->index(['parent_id', 'order']);
            $table->index(['is_active']);
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
