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
        Schema::create('demo_applications', function (Blueprint $table) {
            $table->id();

            // Contact Information
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('company_name')->nullable();
            $table->string('job_title')->nullable();

            // Demo Request Details
            $table->string('product_interest')->nullable(); // Which product/service they're interested in
            $table->text('message')->nullable(); // Additional message/requirements
            $table->string('preferred_contact_method')->default('email'); // email, phone, both
            $table->date('preferred_demo_date')->nullable();
            $table->string('preferred_demo_time')->nullable(); // morning, afternoon, evening

            // Business Information
            $table->string('company_size')->nullable(); // small, medium, large, enterprise
            $table->string('industry')->nullable();
            $table->string('website')->nullable();

            // Application Status
            $table->enum('status', ['new', 'contacted', 'demo_scheduled', 'demo_completed', 'converted', 'rejected'])
                ->default('new');
            $table->text('admin_notes')->nullable();
            $table->timestamp('contacted_at')->nullable();
            $table->timestamp('demo_scheduled_at')->nullable();
            $table->timestamp('demo_completed_at')->nullable();

            // Source tracking
            $table->string('source')->default('website'); // website, referral, social, etc.
            $table->string('utm_source')->nullable();
            $table->string('utm_medium')->nullable();
            $table->string('utm_campaign')->nullable();

            // Flags
            $table->boolean('is_active')->default(true);
            $table->boolean('newsletter_consent')->default(false);
            $table->boolean('marketing_consent')->default(false);

            $table->timestamps();

            // Indexes
            $table->index(['status', 'created_at']);
            $table->index(['email']);
            $table->index(['company_name']);
            $table->index(['is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('demo_applications');
    }
};
