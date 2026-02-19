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
        Schema::create('comment_votes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('comment_id');
            $table->unsignedBigInteger('user_id')->nullable()->index('comment_votes_user_id_foreign');
            $table->string('ip_address', 45);
            $table->string('user_agent')->nullable();
            $table->enum('type', ['like', 'dislike']);
            $table->timestamps();

            $table->unique(['comment_id', 'ip_address']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comment_votes');
    }
};
