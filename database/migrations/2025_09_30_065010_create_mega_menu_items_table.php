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
            $table->id();
            $table->timestamps();

            // Menu structure
            $table->string('parent_menu'); // e.g., 'solutions', 'pricing'
            $table->enum('column', ['left', 'right'])->default('right'); // Column placement
            $table->unsignedBigInteger('section_id')->nullable(); // Links to section header
            $table->integer('order')->default(0);

            // Section info (for section headers)
            $table->string('section_title')->nullable();
            $table->string('section_subtitle')->nullable();
            $table->boolean('is_section_header')->default(false);

            // Item details
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->text('description')->nullable();
            $table->string('icon')->nullable(); // Font Awesome icon class
            $table->string('icon_bg_color')->nullable(); // Background color for icon

            // Link type
            $table->enum('link_type', ['custom', 'module'])->default('custom');
            $table->string('url')->nullable(); // For custom links
            $table->string('module_name')->nullable(); // For module items (blog, service, etc.)
            $table->unsignedBigInteger('module_id')->nullable(); // ID of the module item

            // Display options
            $table->boolean('is_active')->default(true);
            $table->boolean('open_in_new_tab')->default(false);
            $table->string('badge_text')->nullable(); // e.g., 'New', 'Popular'
            $table->string('badge_color')->nullable();

            // Indexes
            $table->index(['parent_menu', 'section_id', 'order']);
            $table->index(['is_active']);
            $table->foreign('section_id')->references('id')->on('mega_menu_items')->onDelete('cascade');
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
