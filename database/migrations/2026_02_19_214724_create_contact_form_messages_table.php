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
            $table->bigIncrements('id');
            $table->unsignedBigInteger('contact_form_id')->index();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->enum('direction', ['inbound', 'outbound'])->default('outbound')->index();
            $table->string('subject');
            $table->text('message');
            $table->json('attachments')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->string('status')->default('sent');
            $table->text('error_message')->nullable();
            $table->timestamps();
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
