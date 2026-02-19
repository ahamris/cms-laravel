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
        Schema::table('form_builders', function (Blueprint $table) {
            $table->boolean('is_api_form')->default(false)->after('is_active');
            $table->string('api_url', 500)->nullable()->after('is_api_form');
            $table->string('api_token', 255)->nullable()->after('api_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('form_builders', function (Blueprint $table) {
            $table->dropColumn(['is_api_form', 'api_url', 'api_token']);
        });
    }
};
