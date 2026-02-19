---
name: Inbound Marketing Orchestrator
overview: Implement an AI-powered inbound marketing orchestrator that automates content planning, generation, scheduling, and publishing based on user intent briefs. The system will include autopilot modes, intelligent content strategy, automated scheduling, and continuous learning from performance data.
todos: []
---

# I

nbound Marketing Orchestrator Implementation Plan

## Overview

This plan implements an AI-powered marketing automation system that transforms user intent into automated content marketing campaigns. The system will handle content planning, generation, scheduling, and optimization with minimal user intervention.

## Architecture

The system consists of two core engines:

- **Strategy Engine**: Decides what content to create and why (AI-driven planning)

- **Execution Engine**: Writes, schedules, and publishes content automatically

## Database Schema

### New Models & Migrations

1. **ContentPlan** (`content_plans` table)

- Stores generated content plans (30-day plans)

- Fields: `intent_brief_id`, `status`, `autopilot_mode`, `approved_at`, `start_date`, `end_date`, `strategy_data` (JSON)

2. **ContentPlanItem** (`content_plan_items` table)

- Individual items in a plan (blog posts, social posts, etc.)

- Fields: `content_plan_id`, `item_type` (blog/social/evergreen), `status`, `priority`, `scheduled_at`, `content_data` (JSON), `related_content_id`

3. **IntentBrief** (`intent_briefs` table)

- User input for content strategy

- Fields: `business_goal`, `audience`, `topic`, `tone`, `approval_level`, `user_id`, `status`

4. **ContentPerformance** (`content_performances` table)

- Tracks performance metrics for learning loop

- Fields: `contentable_type`, `contentable_id`, `ctr`, `impressions`, `engagement`, `ranking_data` (JSON), `measured_at`

5. **Blog Model Updates**

- Add fields: `content_plan_id`, `autopilot_mode`, `seo_score`, `seo_status` (google-friendly/needs-improvement/high-potential)

## Core Services

### 1. StrategyEngine Service

**File**: `app/Services/StrategyEngine.php`

Responsibilities:

- Analyze intent brief and generate content strategy

- Perform SEO gap analysis

- Topic clustering and keyword intent classification

- Channel suitability scoring
- Generate 30-day content flight plans

- Determine optimal content mix (pillar → clusters → social)

Uses existing AI integrations (Groq/Gemini) with specialized marketing prompts.

### 2. ExecutionEngine Service

**File**: `app/Services/ExecutionEngine.php`Responsibilities:

- Generate blog post content using AI

- Create social media posts from blog content

- Schedule content based on optimal timing

- Publish content based on autopilot mode

- Handle approval workflows

### 3. MarketingIntelligence Service

**File**: `app/Services/MarketingIntelligence.php`

Responsibilities:

- SEO analysis (E-E-A-T, Helpful Content, Structured Data)
- Internal link suggestions
- Performance data analysis

- Content optimization recommendations

- Timing optimization (uses Google Search Console data if available)

### 4. ContentScheduler Service

**File**: `app/Services/ContentScheduler.php`Responsibilities:

- Calculate optimal posting times based on:
- Historical CMS traffic

- Industry benchmarks

- Channel-specific best practices

- Timezone awareness

- Schedule content plan items

- Process scheduled content via queue jobs

## Autopilot Modes

Three modes stored in `ContentPlan`:

- **Assisted** (default): AI proposes → user approves

- **Guided**: AI publishes unless blocked
- **Full Autopilot**: AI plans, writes, schedules, publishes

## Queue Jobs

1. **GenerateContentPlanJob**

- Processes intent brief and generates plan via StrategyEngine

2. **GenerateBlogContentJob**

- Uses AI to write blog post content

3. **PublishScheduledContentJob**

- Publishes content when scheduled_at is reached

4. **UpdateContentPerformanceJob**

- Periodic job to fetch and update performance metrics

5. **OptimizeContentPlanJob**

- Uses performance data to adjust future content plans

## Admin Controllers

1. **IntentBriefController** (`app/Http/Controllers/Admin/Marketing/IntentBriefController.php`)

- CRUD for intent briefs

- Generate content plan action

2. **ContentPlanController** (`app/Http/Controllers/Admin/Marketing/ContentPlanController.php`)

- View and manage content plans

- Approve/reject plans
- Adjust plan items

- Set autopilot mode

3. **MarketingDashboardController** (`app/Http/Controllers/Admin/Marketing/MarketingDashboardController.php`)

- Main dashboard showing:

    - Growth status

    - Next publications

    - Items awaiting approval

    - Risks/suggestions

## Admin Views

1. **Intent Brief Form** (`resources/views/admin/marketing/intent-briefs/create.blade.php`)

- Simple 5-field form: business goal, audience, topic, tone, approval level

2. **Content Plan Dashboard** (`resources/views/admin/marketing/content-plans/index.blade.php`)

- List of content plans with status

- Quick actions (approve, edit, view)

3. **Content Plan Detail** (`resources/views/admin/marketing/content-plans/show.blade.php`)

- Visual timeline of 30-day plan

- Items grouped by type (pillar, supporting, social, evergreen)
- Approval controls

- Autopilot mode selector

4. **Marketing Dashboard** (`resources/views/admin/marketing/dashboard.blade.php`)

- Growth metrics
- Upcoming publications calendar

- Approval queue

- Performance insights

## Blog Controller Updates

Update `app/Http/Controllers/Admin/Content/BlogController.php`:

- Add SEO guarantee display (instead of raw fields)

- Show SEO status badge (Google-friendly/Needs improvement/High potential)

- Integrate with ContentPlan when creating from plan

## SEO Guarantee System

Replace technical SEO fields with simple status indicators:

- **Google-friendly**: All SEO requirements met

- **Needs improvement**: Missing some SEO elements

- **High ranking potential**: Optimized for top rankings

Backend calculates this using MarketingIntelligence service checking:

- E-E-A-T signals

- Helpful Content guidelines

- Structured data presence

- Internal linking

- Canonical tags

- Performance budget

## Routes

Add to `routes/admin.php`:

```php
Route::prefix('marketing')->name('marketing.')->group(function () {
    Route::resource('intent-briefs', IntentBriefController::class);
    Route::resource('content-plans', ContentPlanController::class);
    Route::get('dashboard', [MarketingDashboardController::class, 'index'])->name('dashboard');
    Route::post('content-plans/{plan}/approve', [ContentPlanController::class, 'approve'])->name('content-plans.approve');
    Route::post('content-plans/{plan}/generate', [ContentPlanController::class, 'generate'])->name('content-plans.generate');
});
```



## Integration Points

1. **Existing Blog Model**: Extend with content plan relationships

2. **Existing SocialMediaPostingService**: Use for social post execution

3. **Existing AI Services**: Extend Groq/Gemini integration for content generation

4. **Existing Queue System**: Use Laravel queues for async processing

5. **Existing MarketingPersona/ContentType**: Use in strategy generation

## Learning Loop

1. Track performance via `ContentPerformance` model

2. Store metrics: CTR, impressions, engagement, ranking changes

3. Use `OptimizeContentPlanJob` to analyze patterns

4. Adjust future plans based on:

- Headline performance

- Content length optimization

- Posting time effectiveness
- Internal linking success

- Content depth requirements

## Implementation Order

1. Database migrations for new models

2. Model classes with relationships

3. StrategyEngine service (core AI logic)

4. ExecutionEngine service

5. MarketingIntelligence service

6. ContentScheduler service
7. Queue jobs
8. Admin controllers

9. Admin views

10. Blog controller updates (SEO guarantees)
11. Routes

12. Testing and refinement

## Key Files to Create/Modify

**New Files:**

- `app/Models/IntentBrief.php`

- `app/Models/ContentPlan.php`

- `app/Models/ContentPlanItem.php`

- `app/Models/ContentPerformance.php`

- `app/Services/StrategyEngine.php`
- `app/Services/ExecutionEngine.php`

- `app/Services/MarketingIntelligence.php`

- `app/Services/ContentScheduler.php`

- `app/Jobs/GenerateContentPlanJob.php`

- `app/Jobs/GenerateBlogContentJob.php`

- `app/Jobs/PublishScheduledContentJob.php`

- `app/Jobs/UpdateContentPerformanceJob.php`

- `app/Jobs/OptimizeContentPlanJob.php`

- `app/Http/Controllers/Admin/Marketing/IntentBriefController.php`

- `app/Http/Controllers/Admin/Marketing/ContentPlanController.php`

- `app/Http/Controllers/Admin/Marketing/MarketingDashboardController.php`
- Database migrations (5 new migration files)

**Modified Files:**

- `app/Models/Blog.php` (add relationships and fields)

- `app/Http/Controllers/Admin/Content/BlogController.php` (SEO guarantee display)

- `routes/admin.php` (add marketing routes)