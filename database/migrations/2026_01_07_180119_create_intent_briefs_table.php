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
        Schema::create('intent_briefs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('business_goal'); // More leads / Authority / SEO traffic / Product adoption
            $table->text('audience'); // Decision-makers, developers, citizens, SMBs, etc.
            $table->text('topic'); // "Woo compliance", "AI in government", etc.
            $table->string('tone')->default('expert'); // Expert, neutral, persuasive
            $table->enum('approval_level', ['manual', 'auto_approve'])->default('manual');
            $table->enum('status', ['draft', 'processing', 'completed', 'failed'])->default('draft');
            $table->timestamps();

            $table->index(['user_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('intent_briefs');
    }
};
