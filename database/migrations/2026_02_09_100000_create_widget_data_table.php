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
        if (Schema::hasTable('widget_data')) {
            return;
        }
        Schema::create('widget_data', function (Blueprint $table) {
            $table->id();
            $table->string('widget_type')->index();
            $table->string('name');
            $table->string('identifier')->index();
            $table->json('data')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // form_submissions stays referencing form_builders (admin + display use FormBuilder)
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('widget_data');
    }
};
