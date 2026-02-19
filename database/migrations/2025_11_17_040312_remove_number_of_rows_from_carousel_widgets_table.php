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
        Schema::table('carousel_widgets', function (Blueprint $table) {
            $table->dropColumn('number_of_rows');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('carousel_widgets', function (Blueprint $table) {
            $table->integer('number_of_rows')->default(2)->after('items_per_row');
        });
    }
};
