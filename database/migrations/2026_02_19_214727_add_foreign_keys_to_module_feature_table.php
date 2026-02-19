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
        Schema::table('module_feature', function (Blueprint $table) {
            $table->foreign(['feature_id'])->references(['id'])->on('features')->onUpdate('restrict')->onDelete('cascade');
            $table->foreign(['module_id'])->references(['id'])->on('modules')->onUpdate('restrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('module_feature', function (Blueprint $table) {
            $table->dropForeign('module_feature_feature_id_foreign');
            $table->dropForeign('module_feature_module_id_foreign');
        });
    }
};
