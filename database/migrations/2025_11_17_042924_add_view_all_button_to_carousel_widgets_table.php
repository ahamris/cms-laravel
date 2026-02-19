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
            $table->boolean('show_view_all_button')->default(false)->after('infinite_loop');
            $table->string('view_all_title')->nullable()->after('show_view_all_button');
            $table->text('view_all_description')->nullable()->after('view_all_title');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('carousel_widgets', function (Blueprint $table) {
            $table->dropColumn(['show_view_all_button', 'view_all_title', 'view_all_description']);
        });
    }
};
