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
        Schema::table('faqs', function (Blueprint $table) {
            $table->json('items')->nullable()->after('answer')->comment('Array of FAQ items with question and answer');
            $table->string('title')->nullable()->after('identifier')->comment('Group title for FAQ section');
            $table->string('subtitle')->nullable()->after('title')->comment('Group subtitle for FAQ section');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('faqs', function (Blueprint $table) {
            $table->dropColumn(['items', 'title', 'subtitle']);
        });
    }
};
