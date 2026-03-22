<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('forms', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200);
            $table->string('slug', 200)->unique();
            $table->text('description')->nullable();
            $table->string('type', 30)->default('contact');
            $table->text('success_message')->nullable();
            $table->string('redirect_url', 500)->nullable();
            $table->string('notification_emails', 500)->nullable();
            $table->string('notification_slack', 500)->nullable();
            $table->string('honeypot_field', 50)->nullable();
            $table->boolean('recaptcha_enabled')->default(false);
            $table->string('crm_pipeline', 30)->nullable();
            $table->boolean('crm_auto_contact')->default(true);
            $table->boolean('crm_auto_deal')->default(false);
            $table->unsignedInteger('crm_deal_value')->nullable();
            $table->unsignedInteger('max_submissions')->nullable();
            $table->timestamp('opens_at')->nullable();
            $table->timestamp('closes_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->json('styling')->nullable();
            $table->timestamps();
        });

        Schema::create('form_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('form_id')->constrained('forms')->cascadeOnDelete();
            $table->string('name', 100);
            $table->string('label', 200);
            $table->string('type', 30);
            $table->string('placeholder', 200)->nullable();
            $table->string('help_text', 500)->nullable();
            $table->boolean('is_required')->default(false);
            $table->json('validation_rules')->nullable();
            $table->json('options')->nullable();
            $table->string('default_value', 500)->nullable();
            $table->integer('sort_order')->default(0);
            $table->string('width', 10)->default('full');
            $table->json('conditional_on')->nullable();
            $table->string('crm_map_to', 50)->nullable();
            $table->timestamps();

            $table->index(['form_id', 'sort_order']);
        });

        Schema::create('form_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('form_id')->constrained('forms')->cascadeOnDelete();
            $table->json('data');
            $table->json('files')->nullable();
            $table->string('ip_address', 45);
            $table->text('user_agent')->nullable();
            $table->string('referrer_url', 500)->nullable();
            $table->string('utm_source', 100)->nullable();
            $table->string('utm_medium', 100)->nullable();
            $table->string('utm_campaign', 100)->nullable();
            $table->string('status', 20)->default('new');
            $table->integer('lead_score')->default(0);
            $table->foreignId('converted_contact_id')->nullable()->constrained('contacts')->nullOnDelete();
            $table->foreignId('converted_deal_id')->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();

            $table->index(['form_id', 'status']);
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('form_submissions');
        Schema::dropIfExists('form_fields');
        Schema::dropIfExists('forms');
    }
};
