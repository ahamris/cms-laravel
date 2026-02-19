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
        Schema::table('blogs', function (Blueprint $table) {
            $columns = Schema::getColumnListing('blogs');
            if (! in_array('meta_title', $columns)) {
                $table->string('meta_title', 255)->nullable()->after('meta_keywords');
            }
            if (! in_array('meta_description', $columns)) {
                $table->text('meta_description')->nullable()->after('meta_title');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('blogs', function (Blueprint $table) {
            $table->dropColumn(['meta_title', 'meta_description']);
        });
    }
};
