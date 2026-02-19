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
        Schema::create('changelogs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->text('description');
            $table->longText('content')->nullable();
            $table->string('video_url')->nullable();
            $table->date('date')->nullable()->index();
            $table->enum('status', ['new', 'improved', 'fixed', 'api'])->default('new')->index();
            $table->string('slug')->unique();
            $table->json('features')->nullable();
            $table->json('steps')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->integer('sort_order')->default(0)->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('changelogs');
    }
};
