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
        Schema::create('sent_emails', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            // Email details
            $table->string('to_email');
            $table->json('cc_emails')->nullable(); // Store as JSON array
            $table->json('bcc_emails')->nullable(); // Store as JSON array
            $table->string('subject');
            $table->text('message');

            // Sender information
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');

            // Attachments information
            $table->json('attachments')->nullable(); // Store attachment names and info as JSON
            $table->integer('attachments_count')->default(0);

            // Nullable morphs for relating to any model (contacts, tickets, etc.)
            $table->nullableMorphs('related'); // Creates related_type and related_id columns

            // Email status and metadata
            $table->enum('status', ['sent', 'failed', 'pending'])->default('sent');
            $table->text('error_message')->nullable();
            $table->timestamp('sent_at')->nullable();

            $table->boolean('is_processed')->default(false);

            // Indexes for better performance
            $table->index(['user_id', 'sent_at']);
            $table->index(['to_email', 'sent_at']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sent_emails');
    }
};
