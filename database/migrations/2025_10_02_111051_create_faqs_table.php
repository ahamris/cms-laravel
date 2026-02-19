<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Consolidated: create faqs with identifier, title, subtitle, items, nullable question/answer.
     */
    public function up(): void
    {
        Schema::create('faqs', function (Blueprint $table) {
            $table->id();
            $table->string('identifier')->nullable()->comment('Identifier for page builder selection (e.g., homepage_faq, pricing_faq)');
            $table->string('title')->nullable()->comment('Group title for FAQ section');
            $table->string('subtitle')->nullable()->comment('Group subtitle for FAQ section');
            $table->string('question')->nullable();
            $table->text('answer')->nullable();
            $table->json('items')->nullable()->comment('Array of FAQ items with question and answer');
            $table->timestamps();

            $table->index('identifier');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faqs');
    }
};
