<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('elements', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('type')->index();
            $table->string('title')->nullable();
            $table->string('sub_title')->nullable();
            $table->text('description')->nullable();
            $table->json('options')->nullable();
            $table->morphs('entity');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('elements');
    }
};
