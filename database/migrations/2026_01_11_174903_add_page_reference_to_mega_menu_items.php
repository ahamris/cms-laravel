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
        Schema::table('mega_menu_items', function (Blueprint $table) {
            // Add page_id foreign key to reference pages
            $table->foreignId('page_id')
                ->nullable()
                ->after('url')
                ->constrained('pages')
                ->onDelete('cascade');

            // Add index for performance
            $table->index('page_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mega_menu_items', function (Blueprint $table) {
            $table->dropForeign(['page_id']);
            $table->dropIndex(['page_id']);
            $table->dropColumn('page_id');
        });
    }
};
