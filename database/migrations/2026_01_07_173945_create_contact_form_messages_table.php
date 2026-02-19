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
        Schema::create('contact_form_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contact_form_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('direction', ['inbound', 'outbound'])->default('outbound');
            $table->string('subject');
            $table->text('message');
            $table->json('attachments')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->string('status')->default('sent'); // sent, failed
            $table->text('error_message')->nullable();
            $table->timestamps();
            
            $table->index('contact_form_id');
            $table->index('user_id');
            $table->index('direction');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_form_messages');
    }
};
