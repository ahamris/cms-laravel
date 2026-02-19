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
            $table->bigIncrements('id');
            $table->timestamps();
            $table->string('to_email');
            $table->json('cc_emails')->nullable();
            $table->json('bcc_emails')->nullable();
            $table->string('subject');
            $table->text('message');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->json('attachments')->nullable();
            $table->integer('attachments_count')->default(0);
            $table->string('related_type')->nullable();
            $table->unsignedBigInteger('related_id')->nullable();
            $table->enum('status', ['sent', 'failed', 'pending'])->default('sent')->index();
            $table->text('error_message')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->boolean('is_processed')->default(false);

            $table->index(['related_type', 'related_id']);
            $table->index(['to_email', 'sent_at']);
            $table->index(['user_id', 'sent_at']);
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
