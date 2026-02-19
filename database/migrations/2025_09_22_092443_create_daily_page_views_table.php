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

        if (Schema::connection('sqlite')->hasTable('daily_page_views')) {
            Schema::connection('sqlite')->dropIfExists('daily_page_views');
        }

        Schema::connection('sqlite')->create('daily_page_views', function (Blueprint $table) {
            $table->id();
            $table->date('date'); // The date for this record
            $table->string('url', 500); // The URL that was visited
            $table->string('page_title')->nullable(); // Page title if available
            $table->integer('views')->default(1); // Number of views for this URL on this date
            $table->integer('unique_visitors')->default(1); // Number of unique visitors
            $table->string('referrer', 500)->nullable(); // Where visitors came from
            $table->string('user_agent', 1000)->nullable(); // Browser/device info
            $table->string('ip_address', 45)->nullable(); // Visitor IP address
            $table->json('metadata')->nullable(); // Additional data (device type, browser, etc.)
            $table->timestamps();

            // Indexes for better performance
            $table->unique(['date', 'url'], 'daily_page_views_date_url_unique');
            $table->index('date');
            $table->index('url');
            $table->index(['date', 'views']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('sqlite')->dropIfExists('daily_page_views');
    }
};
