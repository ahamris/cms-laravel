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
        Schema::create('email_logs', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            // Email Details
            $table->string('subject');
            $table->string('to_email');
            $table->string('to_name')->nullable();
            $table->string('from_email')->nullable();
            $table->string('from_name')->nullable();
            $table->string('cc')->nullable();
            $table->string('bcc')->nullable();

            // Email Content
            $table->text('body_html')->nullable();
            $table->text('body_text')->nullable();

            // Metadata
            $table->string('mail_class')->nullable(); // The Mailable class name
            $table->string('status')->default('pending'); // pending, sent, failed
            $table->text('error_message')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('failed_at')->nullable();

            // Related Model (polymorphic)
            $table->nullableMorphs('related');

            // Additional Data
            $table->json('metadata')->nullable(); // Store additional context

            // Indexes
            $table->index(['to_email', 'created_at']);
            $table->index(['status', 'created_at']);
            $table->index(['mail_class', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_logs');
    }
};
