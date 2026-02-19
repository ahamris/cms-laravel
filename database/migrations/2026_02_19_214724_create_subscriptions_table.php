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
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->index('demo_applications_email_index');
            $table->string('phone')->nullable();
            $table->string('country_code', 10)->nullable();
            $table->string('company_name')->nullable()->index('demo_applications_company_name_index');
            $table->string('job_title')->nullable();
            $table->string('product_interest')->nullable();
            $table->text('message')->nullable();
            $table->string('preferred_contact_method')->default('email');
            $table->date('preferred_demo_date')->nullable();
            $table->string('preferred_demo_time')->nullable();
            $table->string('company_size')->nullable();
            $table->string('industry')->nullable();
            $table->string('website')->nullable();
            $table->text('topic')->nullable();
            $table->enum('status', ['new', 'contacted', 'demo_scheduled', 'demo_completed', 'converted', 'rejected'])->default('new');
            $table->text('admin_notes')->nullable();
            $table->timestamp('contacted_at')->nullable();
            $table->timestamp('demo_scheduled_at')->nullable();
            $table->timestamp('demo_completed_at')->nullable();
            $table->string('source')->default('website');
            $table->string('utm_source')->nullable();
            $table->string('utm_medium')->nullable();
            $table->string('utm_campaign')->nullable();
            $table->boolean('is_active')->default(true)->index('demo_applications_is_active_index');
            $table->boolean('newsletter_consent')->default(false);
            $table->boolean('marketing_consent')->default(false);
            $table->timestamps();

            $table->index(['status', 'created_at'], 'demo_applications_status_created_at_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
