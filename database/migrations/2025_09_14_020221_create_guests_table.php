<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $path = config('database.connections.sqlite.database');

        if (! File::exists($path)) {
            File::put($path, '');
        }

        if (Schema::connection('sqlite')->hasTable('guests')) {
            return;
        }

        // Now that the file exists, we can create the table.
        Schema::connection('sqlite')->create('guests', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->dateTime('last_activity')->nullable();
            $table->string('ip_address')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('sqlite')->dropIfExists('guests');
    }
};
