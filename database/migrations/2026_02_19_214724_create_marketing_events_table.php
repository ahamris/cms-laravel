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
            $table->bigIncrements('id');
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->string('type')->default('webinar');
            $table->dateTime('start_date');
            $table->dateTime('end_date')->nullable();
            $table->string('timezone')->default('Europe/Amsterdam');
            $table->string('location')->nullable();
            $table->string('meeting_url')->nullable();
            $table->text('agenda')->nullable();
            $table->json('speakers')->nullable();
            $table->integer('max_attendees')->nullable();
            $table->integer('registered_count')->default(0);
            $table->decimal('price')->default(0);
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
