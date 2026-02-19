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

        if (Schema::connection('sqlite')->hasTable('daily_stats')) {
            Schema::connection('sqlite')->dropIfExists('daily_stats');
        }

        Schema::connection('sqlite')->create('daily_stats', function (Blueprint $table) {
            $table->id();
            $table->date('date')->unique(); // The date for this record

            // Page view statistics
            $table->integer('total_page_views')->default(0); // Total page views for the day
            $table->integer('unique_visitors')->default(0); // Unique visitors for the day
            $table->integer('unique_pages')->default(0); // Number of unique pages visited

            // User activity statistics
            $table->integer('new_users')->default(0); // New user registrations
            $table->integer('active_users')->default(0); // Users who logged in
            $table->integer('guest_visitors')->default(0); // Non-logged-in visitors

            // Content statistics
            $table->integer('new_publications')->default(0); // New publications created
            $table->integer('new_contacts')->default(0); // New contacts added
            $table->integer('new_tickets')->default(0); // New support tickets
            $table->integer('new_woo_requests')->default(0); // New WOO requests

            // Email statistics
            $table->integer('emails_sent')->default(0); // Emails sent successfully
            $table->integer('emails_failed')->default(0); // Failed email attempts

            // Traffic sources
            $table->json('referrer_stats')->nullable(); // Top referrers with counts
            $table->json('popular_pages')->nullable(); // Most visited pages with counts
            $table->json('browser_stats')->nullable(); // Browser usage statistics
            $table->json('device_stats')->nullable(); // Device type statistics

            // Performance metrics
            $table->decimal('avg_session_duration', 8, 2)->nullable(); // Average session duration in minutes
            $table->decimal('bounce_rate', 5, 2)->nullable(); // Bounce rate percentage

            $table->timestamps();

            // Indexes for better performance
            $table->index('date');
            $table->index(['date', 'total_page_views']);
            $table->index(['date', 'unique_visitors']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('sqlite')->dropIfExists('daily_stats');
    }
};
