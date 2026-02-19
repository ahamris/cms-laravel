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
        Schema::table('solutions', function (Blueprint $table) {
            // SEO Meta fields
            $table->string('meta_title')->nullable()->after('meta_keywords');
            $table->text('meta_description')->nullable()->after('meta_title');
            $table->text('short_body')->nullable()->after('meta_description');
            $table->text('long_body')->nullable()->after('short_body');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('solutions', function (Blueprint $table) {
            $table->dropColumn([
                'meta_title',
                'meta_description',
                'short_body',
                'long_body',
            ]);
        });
    }
};
