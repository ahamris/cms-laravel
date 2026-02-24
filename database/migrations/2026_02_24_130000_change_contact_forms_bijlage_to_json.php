<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Use only bijlage: convert from string to JSON [{"path": "...", "name": "..."}, ...].
     * Drops attachments column if present.
     */
    public function up(): void
    {
        $table = 'contact_forms';

        // Add new JSON column
        Schema::table($table, function (Blueprint $blueprint) {
            $blueprint->json('bijlage_new')->nullable()->after('bericht');
        });

        $hasAttachments = Schema::hasColumn($table, 'attachments');

        // Migrate data: from attachments column if present, else from bijlage string
        $rows = DB::table($table)->get();
        foreach ($rows as $row) {
            $json = null;
            if ($hasAttachments && isset($row->attachments) && $row->attachments !== null) {
                $json = is_string($row->attachments) ? $row->attachments : json_encode($row->attachments);
            } elseif (! empty($row->bijlage)) {
                $path = $row->bijlage;
                $json = json_encode([['path' => $path, 'name' => basename($path)]]);
            }
            if ($json !== null) {
                DB::table($table)->where('id', $row->id)->update(['bijlage_new' => $json]);
            }
        }

        Schema::table($table, function (Blueprint $blueprint) use ($hasAttachments) {
            $blueprint->dropColumn('bijlage');
            if ($hasAttachments) {
                $blueprint->dropColumn('attachments');
            }
        });

        Schema::table($table, function (Blueprint $blueprint) {
            $blueprint->renameColumn('bijlage_new', 'bijlage');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $table = 'contact_forms';

        Schema::table($table, function (Blueprint $blueprint) {
            $blueprint->json('bijlage_old')->nullable()->after('bericht');
        });

        $rows = DB::table($table)->get();
        foreach ($rows as $row) {
            $path = null;
            if (! empty($row->bijlage)) {
                $decoded = json_decode($row->bijlage, true);
                if (is_array($decoded) && isset($decoded[0]['path'])) {
                    $path = $decoded[0]['path'];
                }
            }
            if ($path !== null) {
                DB::table($table)->where('id', $row->id)->update(['bijlage_old' => $path]);
            }
        }

        Schema::table($table, function (Blueprint $blueprint) {
            $blueprint->dropColumn('bijlage');
            $blueprint->renameColumn('bijlage_old', 'bijlage');
        });

        Schema::table($table, function (Blueprint $blueprint) {
            $blueprint->string('bijlage')->nullable()->change();
        });

        Schema::table($table, function (Blueprint $blueprint) {
            $blueprint->json('attachments')->nullable()->after('bijlage');
        });
    }
};
