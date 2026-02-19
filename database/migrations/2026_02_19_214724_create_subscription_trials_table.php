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
        Schema::create('subscription_trials', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('subscription_id')->index('subscription_trials_subscription_id_foreign');
            $table->unsignedBigInteger('user_id')->nullable()->index('subscription_trials_user_id_foreign');
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscription_trials');
    }
};
