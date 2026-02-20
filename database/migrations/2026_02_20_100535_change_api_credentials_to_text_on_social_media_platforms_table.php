<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * api_credentials uses Laravel's encrypted cast (stores a string), not JSON.
     * JSON column rejects non-JSON values, so we use LONGTEXT.
     */
    public function up(): void
    {
        $driver = DB::connection()->getDriverName();

        if ($driver === 'mysql') {
            DB::statement('ALTER TABLE social_media_platforms MODIFY api_credentials LONGTEXT NULL');
        }

        if ($driver === 'sqlite') {
            DB::statement('ALTER TABLE social_media_platforms RENAME COLUMN api_credentials TO api_credentials_old');
            DB::statement('ALTER TABLE social_media_platforms ADD COLUMN api_credentials TEXT NULL');
            DB::statement('UPDATE social_media_platforms SET api_credentials = api_credentials_old');
            DB::statement('ALTER TABLE social_media_platforms DROP COLUMN api_credentials_old');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::connection()->getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE social_media_platforms MODIFY api_credentials JSON NULL');
        }
    }
};
