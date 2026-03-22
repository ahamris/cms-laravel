<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // CRM Deals
        Schema::create('crm_deals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contact_id')->constrained('contacts')->cascadeOnDelete();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('stage', ['lead', 'qualified', 'proposal', 'negotiation', 'won', 'lost'])->default('lead');
            $table->unsignedBigInteger('value')->default(0);
            $table->string('currency', 3)->default('EUR');
            $table->unsignedSmallInteger('probability')->default(0);
            $table->date('expected_close_date')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->string('lost_reason', 500)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['stage', 'is_active']);
            $table->index('contact_id');
        });

        // CRM Tickets
        Schema::create('crm_tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contact_id')->nullable()->constrained('contacts')->nullOnDelete();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->string('subject', 300);
            $table->text('description');
            $table->enum('status', ['open', 'in_progress', 'waiting', 'resolved', 'closed'])->default('open');
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->string('source', 30)->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'priority']);
        });

        // CRM Ticket Replies
        Schema::create('crm_ticket_replies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained('crm_tickets')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('direction', ['inbound', 'outbound'])->default('outbound');
            $table->text('body');
            $table->boolean('is_ai_generated')->default(false);
            $table->timestamps();
        });

        // CRM Appointments
        Schema::create('crm_appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contact_id')->nullable()->constrained('contacts')->nullOnDelete();
            $table->foreignId('deal_id')->nullable()->constrained('crm_deals')->nullOnDelete();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title', 200);
            $table->text('notes')->nullable();
            $table->enum('type', ['demo', 'call', 'follow_up', 'onboarding', 'meeting', 'other'])->default('meeting');
            $table->timestamp('starts_at');
            $table->timestamp('ends_at')->nullable();
            $table->enum('status', ['scheduled', 'completed', 'cancelled', 'no_show'])->default('scheduled');
            $table->string('location', 500)->nullable();
            $table->boolean('is_online')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['starts_at', 'status']);
        });

        // CRM Notes
        Schema::create('crm_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('contact_id')->nullable()->constrained('contacts')->nullOnDelete();
            $table->foreignId('deal_id')->nullable()->constrained('crm_deals')->nullOnDelete();
            $table->foreignId('ticket_id')->nullable()->constrained('crm_tickets')->nullOnDelete();
            $table->text('body');
            $table->string('type', 20)->default('note');
            $table->boolean('is_pinned')->default(false);
            $table->timestamps();

            $table->index('contact_id');
        });

        // Add CRM fields to contacts
        if (!Schema::hasColumn('contacts', 'funnel_fase')) {
            Schema::table('contacts', function (Blueprint $table) {
                $table->string('funnel_fase', 30)->nullable()->after('notes');
                $table->string('lead_source', 100)->nullable()->after('funnel_fase');
                $table->integer('lead_score')->default(0)->after('lead_source');
                $table->string('lifecycle_stage', 30)->default('subscriber')->after('lead_score');
                $table->string('company_name', 200)->nullable()->after('lifecycle_stage');
                $table->string('job_title', 200)->nullable()->after('company_name');
                $table->string('avatar_url', 500)->nullable()->after('job_title');
                $table->timestamp('last_activity_at')->nullable()->after('avatar_url');
                $table->json('tags')->nullable()->after('last_activity_at');
            });
        }

        // Add CRM fields to contact_forms
        if (!Schema::hasColumn('contact_forms', 'lead_score')) {
            Schema::table('contact_forms', function (Blueprint $table) {
                $table->integer('lead_score')->default(0)->after('admin_notes');
                $table->string('funnel_fase')->nullable()->after('lead_score');
                $table->foreignId('converted_contact_id')->nullable()->constrained('contacts')->nullOnDelete()->after('funnel_fase');
                $table->foreignId('converted_deal_id')->nullable()->constrained('crm_deals')->nullOnDelete()->after('converted_contact_id');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('crm_notes');
        Schema::dropIfExists('crm_appointments');
        Schema::dropIfExists('crm_ticket_replies');
        Schema::dropIfExists('crm_tickets');
        Schema::dropIfExists('crm_deals');
    }
};
