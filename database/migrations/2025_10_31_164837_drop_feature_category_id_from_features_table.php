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
        Schema::table('features', function (Blueprint $table) {
            // Drop foreign key constraint first
            $table->dropForeign(['feature_category_id']);

            // Then drop the column
            $table->dropColumn('feature_category_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('features', function (Blueprint $table) {
            // Re-add the column
            $table->unsignedBigInteger('feature_category_id')->nullable()->after('icon');

            // Re-add the foreign key
            $table->foreign('feature_category_id')
                ->references('id')
                ->on('feature_categories')
                ->onDelete('set null');
        });
    }
};
