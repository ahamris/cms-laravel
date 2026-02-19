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
        Schema::create('marketing_testimonials', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('customer_name');
            $table->string('company')->nullable();
            $table->string('position')->nullable();
            $table->text('quote');
            $table->string('photo')->nullable();
            $table->string('company_logo')->nullable();
            $table->integer('rating')->nullable();
            $table->json('tags')->nullable();
            $table->boolean('featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marketing_testimonials');
    }
};
