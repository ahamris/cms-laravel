<?php

use App\Models\Feature;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('features', function (Blueprint $table) {
            $table->string('anchor', 255)->nullable()->after('title');
        });

        // Backfill anchor from title (slug) for existing rows
        $seen = [];
        Feature::query()->orderBy('id')->each(function (Feature $feature) use (&$seen) {
            $base = Str::slug($feature->title);
            $anchor = $base;
            $suffix = 0;
            while (isset($seen[$anchor])) {
                $suffix++;
                $anchor = $base . '-' . $suffix;
            }
            $seen[$anchor] = true;
            $feature->update(['anchor' => $anchor]);
        });

        Schema::table('features', function (Blueprint $table) {
            $table->unique('anchor');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('features', function (Blueprint $table) {
            $table->dropUnique(['anchor']);
            $table->dropColumn('anchor');
        });
    }
};
