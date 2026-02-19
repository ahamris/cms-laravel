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
        Schema::create('search_queries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('query')->index();
            $table->integer('results_count')->default(0);
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamp('created_at')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('search_queries');
    }
};
