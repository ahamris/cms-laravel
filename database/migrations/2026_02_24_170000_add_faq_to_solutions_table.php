<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * FAQ items stored as JSON: [{"question":"...","answer":"..."}, ...]
     */
    public function up(): void
    {
        Schema::table('solutions', function (Blueprint $table) {
            $table->json('faq')->nullable()->after('long_body');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('solutions', function (Blueprint $table) {
            $table->dropColumn('faq');
        });
    }
};
