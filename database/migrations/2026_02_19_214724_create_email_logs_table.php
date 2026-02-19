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
            $table->bigIncrements('id');
            $table->timestamps();
            $table->string('subject');
            $table->string('to_email');
            $table->string('to_name')->nullable();
            $table->string('from_email')->nullable();
            $table->string('from_name')->nullable();
            $table->string('cc')->nullable();
            $table->string('bcc')->nullable();
            $table->text('body_html')->nullable();
            $table->text('body_text')->nullable();
            $table->string('mail_class')->nullable();
            $table->string('status')->default('pending');
            $table->text('error_message')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('failed_at')->nullable();
            $table->string('related_type')->nullable();
            $table->unsignedBigInteger('related_id')->nullable();
            $table->json('metadata')->nullable();

            $table->index(['mail_class', 'created_at']);
            $table->index(['related_type', 'related_id']);
            $table->index(['status', 'created_at']);
            $table->index(['to_email', 'created_at']);
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
