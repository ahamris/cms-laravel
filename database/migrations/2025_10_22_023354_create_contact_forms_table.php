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
        Schema::create('contact_forms', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('phone');
            $table->string('company_name')->nullable();
            $table->string('country_code')->nullable();
            $table->string('reden'); // ondersteuning, kennismaking, demo
            $table->text('bericht');
            $table->string('bijlage')->nullable(); // file path
            $table->string('contact_preference'); // call, query
            $table->boolean('avg_optin');
            $table->string('status')->default('new'); // new, contacted, resolved, closed
            $table->text('admin_notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_forms');
    }
};
