<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Restructure to: Solution hasMany Features, Feature hasMany Modules.
     * Drop N:N pivots (module_solution, module_feature) and add direct FKs.
     */
    public function up(): void
    {
        Schema::table('features', function (Blueprint $table) {
            $table->unsignedBigInteger('solution_id')->nullable()->after('id');
        });

        Schema::table('modules', function (Blueprint $table) {
            $table->unsignedBigInteger('feature_id')->nullable()->after('id');
        });

        // Migrate: assign one solution per feature (from module_solution via module_feature)
        DB::statement('
            UPDATE features f
            SET solution_id = (
                SELECT ms.solution_id
                FROM module_feature mf
                JOIN module_solution ms ON ms.module_id = mf.module_id
                WHERE mf.feature_id = f.id
                LIMIT 1
            )
        ');

        // Migrate: assign one feature per module (from module_feature)
        DB::statement('
            UPDATE modules m
            SET feature_id = (
                SELECT mf.feature_id
                FROM module_feature mf
                WHERE mf.module_id = m.id
                LIMIT 1
            )
        ');

        Schema::table('features', function (Blueprint $table) {
            $table->foreign('solution_id')->references('id')->on('solutions')->onUpdate('cascade')->onDelete('set null');
        });

        Schema::table('modules', function (Blueprint $table) {
            $table->foreign('feature_id')->references('id')->on('features')->onUpdate('cascade')->onDelete('set null');
        });

        Schema::dropIfExists('module_solution');
        Schema::dropIfExists('module_feature');
    }

    public function down(): void
    {
        Schema::create('module_solution', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->unsignedBigInteger('module_id');
            $table->unsignedBigInteger('solution_id');
            $table->unique(['module_id', 'solution_id']);
        });

        Schema::create('module_feature', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->unsignedBigInteger('module_id');
            $table->unsignedBigInteger('feature_id');
            $table->unique(['module_id', 'feature_id']);
        });

        // Restore pivot rows from current FKs (best-effort)
        $modules = DB::table('modules')->whereNotNull('feature_id')->get();
        foreach ($modules as $module) {
            $feature = DB::table('features')->where('id', $module->feature_id)->first();
            if ($feature && $feature->solution_id) {
                DB::table('module_solution')->insertOrIgnore([
                    'module_id' => $module->id,
                    'solution_id' => $feature->solution_id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            DB::table('module_feature')->insertOrIgnore([
                'module_id' => $module->id,
                'feature_id' => $module->feature_id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        Schema::table('features', function (Blueprint $table) {
            $table->dropForeign(['solution_id']);
        });
        Schema::table('modules', function (Blueprint $table) {
            $table->dropForeign(['feature_id']);
        });
    }
};
