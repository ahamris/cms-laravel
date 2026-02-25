<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('doc_sections', function (Blueprint $table) {
            $table->dropForeign('doc_sections_doc_version_id_foreign');
        });
        Schema::table('doc_sections', function (Blueprint $table) {
            $table->dropColumn('doc_version_id');
        });
        Schema::dropIfExists('doc_versions');
    }

    public function down(): void
    {
        Schema::create('doc_versions', function (Blueprint $table) {
            $table->id();
            $table->string('version', 50);
            $table->string('name')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_default')->default(false);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::table('doc_sections', function (Blueprint $table) {
            $table->unsignedBigInteger('doc_version_id')->nullable()->after('id');
            $table->foreign(['doc_version_id'])->references(['id'])->on('doc_versions')->onUpdate('restrict')->onDelete('cascade');
        });
    }
};
