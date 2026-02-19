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
        Schema::create('form_builders', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('identifier')->unique();
            $table->string('slug')->unique();
            $table->json('fields')->nullable(); // Form fields configuration
            $table->json('settings')->nullable(); // Additional settings
            $table->text('success_message')->nullable();
            $table->string('redirect_url')->nullable();
            $table->boolean('send_email_notification')->default(false);
            $table->text('notification_emails')->nullable(); // Comma-separated emails
            $table->string('submit_button_text')->default('Submit');
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            
            $table->index('identifier');
            $table->index('is_active');
            $table->index('sort_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_builders');
    }
};
