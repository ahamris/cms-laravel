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
        Schema::create('pricing_features', function (Blueprint $table) {
            $table->id();
            $table->string('category'); // e.g., 'Invoices', 'Expenses', 'Quotations', 'Reporting'
            $table->string('name'); // e.g., 'Invoices', 'Credit notes'
            $table->text('description')->nullable();
            $table->json('available_in_plans')->nullable(); // ['smart', 'grow', 'flow'] or specific plans
            $table->string('badge')->nullable(); // e.g., 'Coming soon'
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pricing_features');
    }
};
