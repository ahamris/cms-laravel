<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('blogs', function (Blueprint $table) {
            if (!Schema::hasColumn('blogs', 'type')) {
                $table->string('type', 20)->default('article')->after('blog_type_id');
            }
            if (!Schema::hasColumn('blogs', 'category_id')) {
                $table->foreignId('category_id')->nullable()->after('type')->constrained('article_categories')->nullOnDelete();
            }
            if (!Schema::hasColumn('blogs', 'reading_time')) {
                $table->unsignedInteger('reading_time')->nullable()->after('category_id');
            }
            if (!Schema::hasColumn('blogs', 'media_url')) {
                $table->string('media_url', 500)->nullable()->after('reading_time');
            }
            if (!Schema::hasColumn('blogs', 'media_embed_code')) {
                $table->text('media_embed_code')->nullable()->after('media_url');
            }
            if (!Schema::hasColumn('blogs', 'media_duration')) {
                $table->unsignedInteger('media_duration')->nullable()->after('media_embed_code');
            }
            if (!Schema::hasColumn('blogs', 'media_provider')) {
                $table->string('media_provider', 30)->nullable()->after('media_duration');
            }
            if (!Schema::hasColumn('blogs', 'transcript')) {
                $table->longText('transcript')->nullable()->after('media_provider');
            }
            if (!Schema::hasColumn('blogs', 'show_notes')) {
                $table->text('show_notes')->nullable()->after('transcript');
            }
            if (!Schema::hasColumn('blogs', 'featured_image_id')) {
                $table->foreignId('featured_image_id')->nullable()->after('show_notes')->constrained('media')->nullOnDelete();
            }
            if (!Schema::hasColumn('blogs', 'allow_comments')) {
                $table->boolean('allow_comments')->default(true)->after('is_featured');
            }
            if (!Schema::hasColumn('blogs', 'view_count')) {
                $table->unsignedInteger('view_count')->default(0)->after('allow_comments');
            }
            if (!Schema::hasColumn('blogs', 'series_id')) {
                $table->foreignId('series_id')->nullable()->after('view_count')->constrained('article_series')->nullOnDelete();
            }
            if (!Schema::hasColumn('blogs', 'series_order')) {
                $table->unsignedInteger('series_order')->nullable()->after('series_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('blogs', function (Blueprint $table) {
            $columns = [
                'type', 'category_id', 'reading_time', 'media_url', 'media_embed_code',
                'media_duration', 'media_provider', 'transcript', 'show_notes',
                'featured_image_id', 'allow_comments', 'view_count', 'series_id', 'series_order',
            ];
            foreach ($columns as $col) {
                if (Schema::hasColumn('blogs', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
