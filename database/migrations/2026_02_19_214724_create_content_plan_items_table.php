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
        Schema::create('content_plan_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('content_plan_id');
            $table->enum('item_type', ['pillar', 'supporting', 'social', 'evergreen'])->default('supporting');
            $table->enum('status', ['planned', 'generating', 'draft', 'scheduled', 'published', 'failed'])->default('planned');
            $table->integer('priority')->default(0);
            $table->timestamp('scheduled_at')->nullable();
            $table->json('content_data')->nullable();
            $table->unsignedBigInteger('related_content_id')->nullable();
            $table->string('related_content_type')->nullable();
            $table->timestamps();

            $table->index(['content_plan_id', 'status']);
            $table->index(['item_type', 'status']);
            $table->index(['scheduled_at', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('content_plan_items');
    }
};
