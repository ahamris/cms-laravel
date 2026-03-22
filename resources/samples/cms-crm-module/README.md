# CRM Inbound Marketing Module
## Integration Guide for Studio CMS (Laravel + Livewire + Tailwind)

---

## What this module adds

A **HubSpot-style CRM** built directly into your existing admin panel.
The funnel maps to your existing `funnel_fase` field values:

| DB Value      | Funnel Phase | HubSpot Equivalent |
|---------------|--------------|-------------------|
| `interesseer` | Attract      | Strangers → Visitors |
| `overtuig`    | Convert      | Visitors → Leads |
| `activeer`    | Close        | Leads → Customers |
| `inspireer`   | Delight      | Customers → Promoters |

---

## Module structure

```
app/
  Http/Controllers/Admin/Crm/
    CrmDashboardController.php   ← Funnel overview + stats
    CrmContactController.php     ← Contact CRUD + funnel stage
    CrmDealController.php        ← Kanban pipeline
    CrmTicketController.php      ← Support tickets
    CrmMessageController.php     ← ContactForm inbox wrapper
    CrmAppointmentController.php ← Calendar appointments
    CrmNoteController.php        ← Internal notes
    CrmFunnelController.php      ← Attract/Convert/Close/Delight views

  Models/
    CrmDeal.php
    CrmTicket.php
    CrmAppointment.php
    CrmNote.php

database/migrations/
  2025_03_22_create_crm_tables.php

resources/views/admin/crm/
  dashboard/index.blade.php      ← Main CRM dashboard (funnel visual)
  contacts/
    index.blade.php              ← Grid + table view, funnel filter
    show.blade.php               ← Contact detail + timeline
    create.blade.php
    edit.blade.php
  deals/
    index.blade.php              ← Kanban board (5 stages)
    show.blade.php
    create.blade.php
  tickets/
    index.blade.php
    show.blade.php               ← Ticket detail + reply thread
  messages/
    index.blade.php              ← Split inbox (ContactForm wrapper)
  appointments/
    index.blade.php              ← Calendar view
    create.blade.php
  notes/
    index.blade.php

routes/crm.php                   ← All CRM routes

```

---

## Step 1 — Run the migration

```bash
php artisan migrate
```

This adds:
- `crm_deals` table
- `crm_tickets` table
- `crm_ticket_replies` table
- `crm_appointments` table
- `crm_notes` table
- `funnel_fase`, `lead_score`, `lead_source` columns to `contacts`
- `lead_score`, `funnel_fase`, `converted_contact_id`, `converted_deal_id` to `contact_forms`

---

## Step 2 — Add routes to routes/admin.php

Inside the existing `Route::middleware(['auth', ...])` group, add at the bottom:

```php
// CRM Module
require __DIR__ . '/crm.php';
```

---

## Step 3 — Add to the admin sidebar menu

In your admin panel, go to **Settings → Sidebar Menu** and add these items:

```
Section: CRM
  - CRM Dashboard     → route: admin.crm.dashboard      icon: chart-bar
  - Contacts          → route: admin.crm.contacts.index  icon: users
  - Deals             → route: admin.crm.deals.index      icon: handshake
  - Messages          → route: admin.crm.messages.index   icon: envelope    badge: (new count)
  - Tickets           → route: admin.crm.tickets.index    icon: ticket      badge: (open count)
  - Appointments      → route: admin.crm.appointments.index icon: calendar-days
  - Notes             → route: admin.crm.notes.index      icon: note-sticky
```

Or add directly to the DB via the `MegaMenuItem` model / admin menu builder.

---

## Step 4 — Connect ContactForm submissions automatically

In your existing `ContactFormController::store()`, after saving the form, add:

```php
// Auto-score the lead and set funnel stage
$contactForm->update([
    'funnel_fase' => 'overtuig',  // Convert phase
    'lead_score'  => $this->calculateLeadScore($contactForm),
]);

// Fire event for AI nurture workflow trigger
event(new \App\Events\ContactFormSubmitted($contactForm));
```

---

## Step 5 — Wire up the AI workflows

Your existing `StrategyEngine`, `ExecutionEngine`, and `AIService` already handle content generation.
For CRM AI features, add to `AIService`:

```php
// In AIService.php - add these methods:

public function draftCrmReply(ContactForm $form): string
{
    $prompt = "Write a professional Dutch reply to this contact form submission...";
    $result = $this->callGroqAI($systemPrompt, $prompt);
    return $result['content'] ?? '';
}

public function scoreLead(ContactForm $form): int
{
    // Score 0-100 based on: reden, company, message length, contact preference
    $score = 0;
    if ($form->reden === 'demo')       $score += 40;
    if ($form->company_name)           $score += 20;
    if (strlen($form->bericht) > 100)  $score += 20;
    if ($form->phone)                  $score += 10;
    if ($form->avg_optin)              $score += 10;
    return $score;
}
```

---

## Funnel stage automation rules

The module automatically moves contacts through funnel stages:

| Trigger                           | From         | To           |
|-----------------------------------|--------------|--------------|
| Contact form submitted            | —            | `overtuig`   |
| Demo appointment booked           | `overtuig`   | `activeer`   |
| Deal marked as Won                | `activeer`   | `inspireer`  |
| NPS score submitted (≥ 9)         | `inspireer`  | Promoter     |
| No response after 14 days         | any          | stays, flagged |

---

## API endpoints exposed to React frontend

These are already served by your existing Frontend API. No changes needed:

```
GET  /api/pages          ← funnel_fase=interesseer   (Attract content)
GET  /api/blog           ← funnel_fase=interesseer   (Attract content)
POST /api/contact/verstuur ← Creates ContactForm     (Convert trigger)
```

The CRM module reads and processes these on the admin side only.

---

## Headless / React frontend integration

Your React marketing site (built by your UI/UX team) stays 100% separate.
The CRM module only touches the **admin panel**.

The React frontend continues to:
- Fetch content via `GET /api/pages`, `/api/blog`, `/api/solutions`
- Submit leads via `POST /api/contact/verstuur`
- Track visitors via `POST /api/analytics/guest-activity`

The CRM processes those submissions and moves contacts through the funnel automatically.

---

## Next iterations to build

1. **Email sequences** — wire `ExecutionEngine` to send nurture emails per funnel stage
2. **Lead scoring AI** — call `AIService::scoreLead()` on every form submit
3. **Social auto-posting** — `SocialMediaAutoPostService` triggered from content plans
4. **NPS survey** — send at day 30 after deal won, auto-promote to Delight
5. **Webhook triggers** — fire on stage changes for Zapier/Make/n8n integration
