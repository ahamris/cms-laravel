<?php

// ══════════════════════════════════════════════════════════════════
// MIGRATION: create_crm_tables.php
// Run: php artisan migrate
// ══════════════════════════════════════════════════════════════════

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── CRM Deals ─────────────────────────────────────────
        Schema::create('crm_deals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contact_id')->constrained('contacts')->cascadeOnDelete();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('stage', ['lead','qualified','proposal','negotiation','won','lost'])
                  ->default('lead');
            $table->unsignedBigInteger('value')->default(0); // in cents
            $table->string('currency', 3)->default('EUR');
            $table->unsignedSmallInteger('probability')->default(0); // 0-100
            $table->date('expected_close_date')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->string('lost_reason')->nullable();
            $table->string('funnel_fase')->nullable(); // mirrors contact funnel_fase
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
            $table->index(['stage', 'is_active']);
            $table->index('contact_id');
        });

        // ── CRM Tickets ───────────────────────────────────────
        Schema::create('crm_tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contact_id')->nullable()->constrained('contacts')->nullOnDelete();
            $table->foreignId('contact_form_id')->nullable()->constrained('contact_forms')->nullOnDelete();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->string('subject');
            $table->text('description')->nullable();
            $table->enum('status', ['open','in_progress','waiting','resolved','closed'])->default('open');
            $table->enum('priority', ['low','medium','high','critical'])->default('medium');
            $table->string('category')->nullable(); // billing, technical, onboarding, etc.
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['status', 'priority']);
        });

        // ── CRM Ticket Replies ────────────────────────────────
        Schema::create('crm_ticket_replies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained('crm_tickets')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('direction', ['inbound', 'outbound'])->default('outbound');
            $table->text('body');
            $table->boolean('is_ai_generated')->default(false);
            $table->timestamps();
        });

        // ── CRM Appointments ─────────────────────────────────
        Schema::create('crm_appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contact_id')->nullable()->constrained('contacts')->nullOnDelete();
            $table->foreignId('deal_id')->nullable()->constrained('crm_deals')->nullOnDelete();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title');
            $table->text('notes')->nullable();
            $table->enum('type', ['demo','call','follow_up','onboarding','meeting','other'])->default('meeting');
            $table->timestamp('starts_at');
            $table->timestamp('ends_at')->nullable();
            $table->enum('status', ['scheduled','completed','cancelled','no_show'])->default('scheduled');
            $table->string('location')->nullable();  // URL or address
            $table->boolean('is_online')->default(true);
            $table->timestamps();
            $table->softDeletes();
            $table->index(['starts_at', 'status']);
        });

        // ── CRM Notes ─────────────────────────────────────────
        Schema::create('crm_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('contact_id')->nullable()->constrained('contacts')->nullOnDelete();
            $table->foreignId('deal_id')->nullable()->constrained('crm_deals')->nullOnDelete();
            $table->foreignId('ticket_id')->nullable()->constrained('crm_tickets')->nullOnDelete();
            $table->text('body');
            $table->string('type')->default('note'); // note, call_log, email_log
            $table->boolean('is_pinned')->default(false);
            $table->timestamps();
            $table->index('contact_id');
        });

        // ── Add funnel_fase to contacts (if not present) ──────
        if (!Schema::hasColumn('contacts', 'funnel_fase')) {
            Schema::table('contacts', function (Blueprint $table) {
                $table->string('funnel_fase')->nullable()
                      ->after('notes')
                      ->comment('interesseer=Attract, overtuig=Convert, activeer=Close, inspireer=Delight');
                $table->string('lead_source')->nullable()->after('funnel_fase');
                $table->integer('lead_score')->default(0)->after('lead_source');
            });
        }

        // ── Add crm fields to contact_forms ───────────────────
        if (!Schema::hasColumn('contact_forms', 'lead_score')) {
            Schema::table('contact_forms', function (Blueprint $table) {
                $table->integer('lead_score')->default(0)->after('admin_notes');
                $table->string('funnel_fase')->nullable()->after('lead_score');
                $table->foreignId('converted_contact_id')->nullable()
                      ->constrained('contacts')->nullOnDelete()
                      ->after('funnel_fase');
                $table->foreignId('converted_deal_id')->nullable()
                      ->constrained('crm_deals')->nullOnDelete()
                      ->after('converted_contact_id');
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
