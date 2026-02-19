<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists(table: 'magic_builders');
        Schema::enableForeignKeyConstraints();
    }

    public function down(): void
    {
        // Table cannot be restored — feature has been fully removed.
    }
};
