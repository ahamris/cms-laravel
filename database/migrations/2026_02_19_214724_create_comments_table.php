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
        Schema::create('comments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->nullable()->index('comments_user_id_foreign');
            $table->string('guest_name')->nullable();
            $table->string('guest_email')->nullable();
            $table->text('body');
            $table->boolean('is_approved')->default(false)->index();
            $table->unsignedTinyInteger('rating')->nullable();
            $table->string('entity_type')->nullable();
            $table->unsignedBigInteger('entity_id')->nullable();
            $table->unsignedBigInteger('parent_id')->nullable()->index('comments_parent_id_foreign');
            $table->integer('likes')->default(0);
            $table->integer('dislikes')->default(0);
            $table->timestamps();

            $table->index(['entity_type', 'entity_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
