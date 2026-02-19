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
        Schema::table('services', function (Blueprint $table) {
            $table->foreign(['content_type_id'])->references(['id'])->on('content_types')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['marketing_persona_id'])->references(['id'])->on('marketing_personas')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['service_category_id'])->references(['id'])->on('service_categories')->onUpdate('restrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropForeign('services_content_type_id_foreign');
            $table->dropForeign('services_marketing_persona_id_foreign');
            $table->dropForeign('services_service_category_id_foreign');
        });
    }
};
