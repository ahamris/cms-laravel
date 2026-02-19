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
        // Drop the table if it exists
        Schema::dropIfExists('solutions');

        // Create fresh table with new structure
        Schema::create('solutions', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('anchor')->unique(); // For #anchor navigation (e.g., 'crm', 'invoices')
            $table->string('nav_title'); // Title shown in navigation (e.g., 'CRM', 'Invoices')
            $table->string('title'); // Main heading
            $table->text('subtitle')->nullable(); // Description
            $table->json('list_items')->nullable(); // Bullet points
            $table->string('link_text')->nullable(); // e.g., "More about CRM →"
            $table->string('link_url')->nullable(); // Link URL
            $table->text('testimonial_quote')->nullable(); // Testimonial text
            $table->string('testimonial_author')->nullable(); // Author name
            $table->string('testimonial_company')->nullable(); // Company name
            $table->string('image')->nullable(); // Image path
            $table->enum('image_position', ['left', 'right'])->default('right'); // Image position
            $table->integer('sort_order')->default(0); // Display order
            $table->boolean('is_active')->default(true); // Active status
            $table->string('slug')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solutions');
    }
};
