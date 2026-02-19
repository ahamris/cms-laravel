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
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->string('business_goal');
            $table->text('audience');
            $table->text('topic');
            $table->string('tone')->default('expert');
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
