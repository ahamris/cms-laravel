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
        Schema::table('module_solution', function (Blueprint $table) {
            $table->foreign(['module_id'])->references(['id'])->on('modules')->onUpdate('restrict')->onDelete('cascade');
            $table->foreign(['solution_id'])->references(['id'])->on('solutions')->onUpdate('restrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('module_solution', function (Blueprint $table) {
            $table->dropForeign('module_solution_module_id_foreign');
            $table->dropForeign('module_solution_solution_id_foreign');
        });
    }
};
