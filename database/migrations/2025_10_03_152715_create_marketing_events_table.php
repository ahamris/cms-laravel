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
        Schema::create('marketing_events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->string('type')->default('webinar'); // webinar, workshop, conference, etc.
            $table->datetime('start_date');
            $table->datetime('end_date')->nullable();
            $table->string('timezone')->default('Europe/Amsterdam');
            $table->string('location')->nullable(); // For physical events
            $table->string('meeting_url')->nullable(); // For online events
            $table->text('agenda')->nullable();
            $table->json('speakers')->nullable(); // Array of speaker info
            $table->integer('max_attendees')->nullable();
            $table->integer('registered_count')->default(0);
            $table->decimal('price', 8, 2)->default(0);
            $table->string('featured_image')->nullable();
            $table->json('tags')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_published')->default(true);
            $table->boolean('registration_open')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marketing_events');
    }
};
