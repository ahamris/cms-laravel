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
        Schema::create('events', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('cover_image')->nullable();
            $table->string('image')->nullable();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('short_body')->nullable();
            $table->longText('long_body')->nullable();
            $table->text('description')->nullable();
            $table->date('start_date')->index();
            $table->date('end_date')->nullable();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->string('location')->nullable();
            $table->text('address')->nullable();
            $table->decimal('price', 10)->nullable();
            $table->string('registration_url')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('user_id')->index();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['is_active', 'start_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
