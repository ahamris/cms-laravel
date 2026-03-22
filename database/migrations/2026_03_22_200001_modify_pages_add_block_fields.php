<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            if (!Schema::hasColumn('pages', 'parent_id')) {
                $table->foreignId('parent_id')->nullable()->after('id')->constrained('pages')->nullOnDelete();
            }
            if (!Schema::hasColumn('pages', 'sort_order')) {
                $table->integer('sort_order')->default(0)->after('is_active');
            }
            if (!Schema::hasColumn('pages', 'is_homepage')) {
                $table->boolean('is_homepage')->default(false)->after('sort_order');
            }
            if (!Schema::hasColumn('pages', 'published_at')) {
                $table->timestamp('published_at')->nullable()->after('is_homepage');
            }
            if (!Schema::hasColumn('pages', 'og_image_id')) {
                $table->foreignId('og_image_id')->nullable()->after('published_at')->constrained('media')->nullOnDelete();
            }
            if (!Schema::hasColumn('pages', 'layout')) {
                $table->string('layout', 30)->default('full')->after('template');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $columns = ['parent_id', 'sort_order', 'is_homepage', 'published_at', 'og_image_id', 'layout'];
            foreach ($columns as $col) {
                if (Schema::hasColumn('pages', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
