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
        $driver = Schema::getConnection()->getDriverName();
        if ($driver === 'sqlite') {
            Schema::table('faqs', function (Blueprint $table) {
                $table->dropIndex('faqs_entity_type_entity_id_index');
            });
        }
        Schema::table('faqs', function (Blueprint $table) {
            $table->dropColumn(['entity_id', 'entity_type', 'sort_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('faqs', function (Blueprint $table) {
            $table->unsignedBigInteger('entity_id')->nullable();
            $table->string('entity_type')->nullable();
            $table->integer('sort_order')->default(0);
        });
    }
};
