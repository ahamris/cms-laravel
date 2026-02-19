<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Drops unused service_grids and knowledge_grids tables and show_knowledge_grid from solutions.
     */
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('service_grids');
        Schema::dropIfExists('knowledge_grids');
        Schema::enableForeignKeyConstraints();

        Schema::table('solutions', function (Blueprint $table) {
            $table->dropColumn('show_knowledge_grid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('service_grids', function (Blueprint $table) {
            $table->id();
            $table->string('section_identifier')->unique();
            $table->string('title')->nullable();
            $table->text('content')->nullable();
            $table->json('cards')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->index(['is_active', 'sort_order']);
        });

        Schema::create('knowledge_grids', function (Blueprint $table) {
            $table->id();
            $table->string('section_identifier')->unique();
            $table->string('title')->nullable();
            $table->text('content')->nullable();
            $table->json('cards')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->index(['is_active', 'sort_order']);
        });

        Schema::table('solutions', function (Blueprint $table) {
            $table->boolean('show_knowledge_grid')->default(false)->after('show_cta');
        });
    }
};
