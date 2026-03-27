# ServiceOS — Laravel 13 Technical Implementation Plan (v2)

**A CMS + CRM + Telephony platform for service businesses. Based on the MasjidOS architecture.**
**Competitor benchmark: Teamleader Focus. We build what they have — plus AI, telephony, and a website builder.**

---

## The problem this solves

Service businesses (agencies, consultancies, contractors, freelancers, studios) burn time and money on:

1. **Websites that rot** — built in WordPress or a builder, costs €2k–€10k, then nobody updates it. Content goes stale. SEO tanks.
2. **Scattered tools** — CRM in HubSpot, invoicing in Moneybird, email in Mailchimp, ticketing in Freshdesk, messaging in WhatsApp, phone in a separate PBX. Nothing talks to each other.
3. **Manual inbound funnels** — leads come in from a contact form, someone manually adds them to a CRM, manually sends a follow-up, manually creates a quote. Leads fall through the cracks.
4. **No financial oversight** — the accountant works in Moneybird, the owner has no real-time dashboard, and monthly reports arrive 3 weeks late.
5. **Phone calls are a black hole** — calls come in, nobody logs them. No recording, no transcription, no link to the client record. Important follow-ups get forgotten.

ServiceOS replaces this with **one system**: a self-updating public presence, automated lead capture and nurturing, a CRM with ticketing and multi-user messaging, an integrated softphone with AI-powered call notes, and a live financial dashboard powered by accounting API integrations.

---

## Teamleader Focus — what we learn and where we go further

Teamleader Focus (teamleader.eu) is the closest competitor in the Benelux market. They offer CRM, invoicing, quotations, project management, time tracking, planning, statistics, lead capture, a shared inbox, and work orders. Their API is RPC-style (`resource.action` format at `api.focus.teamleader.eu`) with OAuth2 authentication, webhooks, and custom fields.

### What Teamleader does well (we adopt these patterns)

| Teamleader Feature | ServiceOS Equivalent |
|---|---|
| CRM (contacts + companies) | CRM module — contacts, companies, tags, custom fields |
| Deals + quotations with online signing | Deal pipeline + quotation builder with e-sign |
| Invoicing with payment tracking | Moneybird connector (and future Teamleader connector) |
| Project management with milestones | Project module with time tracking |
| Time tracking with timers | Built-in time tracking, start/stop from any context |
| Planning / resource scheduling | Team planning with availability view |
| Statistics / reporting | Financial dashboard + business intelligence |
| Lead capture forms | CMS form builder with auto-CRM enrollment |
| Shared inbox (Centrale Inbox) | Multi-channel messaging module |
| Work orders with field signatures | Work order / task module with mobile signatures |
| Marketplace integrations | Connector architecture (Moneybird, Teamleader, etc.) |

### Where ServiceOS goes further than Teamleader

| Capability | Teamleader | ServiceOS |
|---|---|---|
| Website / CMS | ✗ No built-in website | ✓ Full CMS with page builder, blog, SEO |
| AI agents | ✗ None | ✓ Content writer, lead scorer, ticket triager, call summarizer, financial insights |
| Integrated telephony (SIP/PBX) | ✗ External VoIP only | ✓ Built-in WebRTC softphone with recording, transcription, AI notes |
| Inbound marketing automation | ✗ Basic lead capture | ✓ Full funnel: SEO → capture → score → nurture → convert |
| Email marketing | ✗ Relies on integrations | ✓ Self-hosted Mailcoach with automations |
| Accounting depth | Built-in basic invoicing | ✓ Deep Moneybird sync + financial dashboard + P&L |
| Multi-tenancy SaaS | ✗ Single-vendor SaaS | ✓ Self-hostable + multi-tenant SaaS |
| Open source | ✗ Proprietary | ✓ Open source core |

---

## Confirmed tech stack

| Layer | Package | Version | Role |
|---|---|---|---|
| Framework | Laravel | 13.x (PHP 8.4) | Core application, API backend |
| Frontend | React 19 + Inertia.js | latest | Admin panel, client portal, public site |
| AI | laravel/ai | native SDK | Content generation, lead scoring, call summarization |
| Search | Laravel Scout + Typesense | native | Full-text search across all modules |
| Payments | mollie/laravel-cashier-mollie | v3 | Client invoicing, subscriptions, iDEAL/SEPA |
| Multi-tenancy | spatie/laravel-multitenancy | v4.1 | Per-business database isolation |
| Email marketing | spatie/laravel-mailcoach | v10 (self-hosted) | Drip campaigns, automations, transactional |
| Mail transport | Postfix on Debian/Ubuntu | local MTA | Outbound email, high deliverability |
| Queue | Laravel Horizon + Redis | native | Job processing, queue monitoring |
| Realtime | Laravel Reverb | native | Live chat, call events, notifications, dashboard |
| API auth | Laravel Sanctum | native | SPA + mobile + external API auth |
| Feature flags | Laravel Pennant | native | Gradual module rollout per tenant |
| Monitoring | Laravel Pulse | native | Performance & usage dashboards |
| Caching | Redis | — | Sessions, cache, queue, rate limiting |
| Database | MySQL | 8.x | Landlord + per-tenant databases |
| Files | S3-compatible (MinIO self-host) | — | Media, documents, call recordings |
| Accounting | Moneybird REST API | v2 | Financial sync, invoicing, reporting |
| Telephony | Asterisk / FreeSWITCH | via WebRTC | PBX, call routing, recording |
| WebRTC SIP | JsSIP / SIP.js | latest | Browser-based softphone in React |
| HTTP client | Saloon PHP | v3 | Clean API connectors (Moneybird, Teamleader, etc.) |
| Dev quality | Laravel Pint + Pest | native | Code style + testing |

### Frontend stack detail — React 19

```
frontend/
├── package.json
├── vite.config.ts
├── tsconfig.json
├── tailwind.config.ts
├── src/
│   ├── app.tsx                          # Inertia app bootstrap
│   ├── layouts/
│   │   ├── AdminLayout.tsx              # Main dashboard layout
│   │   ├── PortalLayout.tsx             # Client portal layout
│   │   └── PublicLayout.tsx             # Public website layout
│   ├── pages/
│   │   ├── Admin/
│   │   │   ├── Dashboard.tsx
│   │   │   ├── CRM/
│   │   │   │   ├── Contacts/
│   │   │   │   │   ├── Index.tsx        # Contact list with search
│   │   │   │   │   ├── Show.tsx         # Contact detail + timeline
│   │   │   │   │   └── Form.tsx         # Create/edit contact
│   │   │   │   ├── Companies/
│   │   │   │   ├── Deals/
│   │   │   │   │   ├── Pipeline.tsx     # Kanban board (drag-and-drop)
│   │   │   │   │   └── Show.tsx         # Deal detail
│   │   │   │   └── Quotations/
│   │   │   ├── Telephony/
│   │   │   │   ├── Softphone.tsx        # WebRTC softphone panel
│   │   │   │   ├── CallLog.tsx          # Call history
│   │   │   │   ├── CallDetail.tsx       # Recording, transcript, notes
│   │   │   │   └── Voicemail.tsx
│   │   │   ├── Ticketing/
│   │   │   ├── Messaging/
│   │   │   │   └── Inbox.tsx            # Multi-channel team inbox
│   │   │   ├── CMS/
│   │   │   │   ├── Pages/
│   │   │   │   ├── Posts/
│   │   │   │   └── PageBuilder.tsx      # Block editor
│   │   │   ├── Projects/
│   │   │   │   ├── Index.tsx
│   │   │   │   ├── Board.tsx            # Kanban task board
│   │   │   │   └── Gantt.tsx            # Timeline view
│   │   │   ├── TimeTracking/
│   │   │   ├── Finance/
│   │   │   │   ├── Dashboard.tsx        # Financial KPI dashboard
│   │   │   │   ├── Invoices.tsx
│   │   │   │   └── CashFlow.tsx
│   │   │   ├── Marketing/
│   │   │   └── Settings/
│   │   ├── Portal/                      # Client-facing portal
│   │   └── Public/                      # Public website pages
│   ├── components/
│   │   ├── ui/                          # Shadcn/ui style primitives
│   │   ├── crm/
│   │   │   ├── ContactCard.tsx
│   │   │   ├── ActivityTimeline.tsx
│   │   │   └── DealKanban.tsx
│   │   ├── telephony/
│   │   │   ├── SoftphoneWidget.tsx      # Floating softphone
│   │   │   ├── Dialpad.tsx
│   │   │   ├── CallControls.tsx         # Hold, mute, transfer, record
│   │   │   ├── IncomingCallModal.tsx
│   │   │   └── CallNotes.tsx            # AI-generated notes editor
│   │   ├── messaging/
│   │   ├── cms/
│   │   │   └── BlockEditor.tsx          # Tiptap-based page builder
│   │   └── finance/
│   │       ├── RevenueChart.tsx
│   │       └── InvoiceAging.tsx
│   ├── hooks/
│   │   ├── useSip.ts                    # SIP/WebRTC connection hook
│   │   ├── useCall.ts                   # Active call management
│   │   ├── useReverb.ts                 # WebSocket subscriptions
│   │   ├── useSearch.ts                 # Global Typesense search
│   │   └── useTimer.ts                  # Time tracking timer
│   ├── lib/
│   │   ├── sip-client.ts               # JsSIP/SIP.js wrapper
│   │   ├── audio-manager.ts            # Call audio handling
│   │   └── api.ts                       # Typed API client
│   └── types/
│       ├── models.ts                    # TypeScript types for all models
│       └── sip.ts                       # SIP/call types
```

### React + Inertia.js + Laravel integration

```tsx
// src/app.tsx — Inertia React bootstrap
import { createInertiaApp } from '@inertiajs/react';
import { createRoot } from 'react-dom/client';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';

createInertiaApp({
    resolve: (name) =>
        resolvePageComponent(`./pages/${name}.tsx`, import.meta.glob('./pages/**/*.tsx')),
    setup({ el, App, props }) {
        createRoot(el).render(<App {...props} />);
    },
});
```

```php
// Laravel side — Inertia responses
class ContactController extends Controller
{
    public function index(Request $request): Response
    {
        return Inertia::render('Admin/CRM/Contacts/Index', [
            'contacts' => ContactResource::collection(
                Contact::query()
                    ->with(['company', 'tags'])
                    ->search($request->input('search'))
                    ->paginate(25)
            ),
            'filters' => $request->only('search', 'tags', 'pipeline'),
        ]);
    }
}
```

---

## Architecture: multi-tenancy design

Identical to MasjidOS — spatie/laravel-multitenancy v4 with **multi-database strategy**. Each business gets its own database. Complete data isolation, easy backups, GDPR-compliant deletion.

### Database topology

```
┌──────────────────────────────────────┐
│  LANDLORD DATABASE (serviceos_main)  │
│                                      │
│  tenants        – business registry  │
│  users          – platform admins    │
│  plans          – subscription tiers │
│  global_config  – platform settings  │
│  domains        – domain → tenant    │
│  connector_registry – API connectors │
└──────────────────────────────────────┘
          │ tenant_id resolves to:
          ▼
┌──────────────────────────────────────┐
│  TENANT DATABASE (serviceos_xxxx)    │
│                                      │
│  — CRM —                             │
│  contacts, companies, deals          │
│  pipelines, stages, activities       │
│  tags, custom_fields, lead_scores    │
│  quotations, quotation_lines         │
│                                      │
│  — CMS —                             │
│  pages, posts, sections, media       │
│  forms, form_submissions             │
│  templates, menus, seo_meta          │
│                                      │
│  — Ticketing —                       │
│  tickets, ticket_replies             │
│  ticket_categories, sla_configs      │
│                                      │
│  — Messaging —                       │
│  conversations, messages             │
│  channels, canned_responses          │
│                                      │
│  — Telephony —                       │
│  sip_accounts, call_logs             │
│  call_recordings, call_transcripts   │
│  call_notes, voicemails              │
│  ivr_configs, ring_groups            │
│                                      │
│  — Projects —                        │
│  projects, tasks, milestones         │
│  time_entries, timers                │
│                                      │
│  — Finance —                         │
│  moneybird_syncs, invoices_cache     │
│  financial_snapshots, kpi_targets    │
│  expense_categories, products        │
│                                      │
│  — Marketing —                       │
│  campaigns, automations, sequences   │
│  email_templates, conversion_events  │
│                                      │
│  — Team —                            │
│  team_members, roles, permissions    │
│  notifications, user_preferences     │
│                                      │
│  — AI —                              │
│  agent_conversations, embeddings     │
│  content_suggestions                 │
└──────────────────────────────────────┘
```

### Tenant resolution

```php
class DomainTenantFinder extends TenantFinder
{
    public function findForRequest(Request $request): ?IsTenant
    {
        $host = $request->getHost();

        return Business::whereDomain($host)
            ->orWhere('subdomain', explode('.', $host)[0])
            ->first();
    }
}
```

Each business can use either:
- A subdomain: `acme.serviceos.nl`
- A custom domain: `acme-consulting.nl`

### Switch tasks configuration

```php
// config/multitenancy.php
'switch_tenant_tasks' => [
    SwitchTenantDatabaseTask::class,
    PrefixCacheTask::class,
    SwitchMailcoachTenantTask::class,
    SwitchTypesenseCollectionTask::class,
    SwitchMoneybirdAdministrationTask::class,
    SwitchSipAccountTask::class,  // per-tenant PBX config
],
```

---

## Module architecture — domain-driven

```
app/
├── Domain/
│   ├── CMS/
│   │   ├── Models/          Page, Post, Section, MediaItem, Form, FormSubmission
│   │   ├── Actions/         PublishPage, CreatePost, ProcessFormSubmission
│   │   ├── Services/        SeoAnalyzer, SitemapGenerator, ThemeEngine
│   │   └── Resources/       PageResource, PostResource
│   │
│   ├── CRM/
│   │   ├── Models/          Contact, Company, Deal, Pipeline, Stage, Activity
│   │   │                    Quotation, QuotationLine, Product
│   │   ├── Actions/         CreateContact, MoveStage, ScoreLead, ImportContacts
│   │   │                    CreateQuotation, SendQuotation, AcceptQuotation
│   │   ├── Services/        LeadScoringEngine, DuplicateDetector
│   │   └── Resources/       ContactResource, DealResource, QuotationResource
│   │
│   ├── Ticketing/
│   │   ├── Models/          Ticket, TicketReply, TicketCategory, SlaConfig
│   │   ├── Actions/         CreateTicket, AssignTicket, EscalateTicket
│   │   └── Services/        SlaMonitor, AutoAssigner
│   │
│   ├── Messaging/
│   │   ├── Models/          Conversation, Message, Channel, CannedResponse
│   │   ├── Actions/         SendMessage, AssignConversation, MergeConversations
│   │   └── Services/        ChannelRouter, WhatsAppAdapter, EmailAdapter
│   │
│   ├── Telephony/
│   │   ├── Models/          SipAccount, CallLog, CallRecording, CallTranscript
│   │   │                    CallNote, Voicemail, IvrConfig, RingGroup
│   │   ├── Actions/         InitiateCall, EndCall, TransferCall, RecordCall
│   │   │                    TranscribeCall, GenerateCallNotes, CreateFollowUpTask
│   │   ├── Services/        AsteriskManager, FreeSwitchManager, PbxInterface
│   │   │                    CallRecordingService, TranscriptionService
│   │   │                    WebRtcProvisioner
│   │   ├── Events/          CallStarted, CallEnded, CallRecorded, CallTranscribed
│   │   └── Resources/       CallLogResource, VoicemailResource
│   │
│   ├── Projects/
│   │   ├── Models/          Project, Task, Milestone, TimeEntry, Timer
│   │   ├── Actions/         CreateProject, StartTimer, StopTimer, LogTime
│   │   └── Resources/       ProjectResource, TaskResource, TimeEntryResource
│   │
│   ├── Marketing/
│   │   ├── Models/          Campaign, Automation, Sequence, ConversionEvent
│   │   ├── Actions/         TriggerAutomation, EnrollInSequence, TrackConversion
│   │   └── Services/        FunnelAnalyzer, UtmTracker
│   │
│   ├── Finance/
│   │   ├── Models/          MoneybirdSync, InvoiceCache, FinancialSnapshot, KpiTarget
│   │   ├── Actions/         SyncFromMoneybird, CreateInvoice, GenerateReport
│   │   ├── Services/        MoneybirdClient, FinancialDashboardService
│   │   └── DTOs/            InvoiceData, ContactData, MutationData
│   │
│   └── Team/
│       ├── Models/          TeamMember, Role, Permission, Department
│       └── Actions/         InviteMember, AssignRole
│
├── Ai/
│   ├── Agents/
│   │   ├── BusinessAssistant.php      — answers client questions on public site
│   │   ├── LeadQualifier.php          — scores and routes inbound leads
│   │   ├── ContentWriter.php          — generates/improves CMS content
│   │   ├── TicketTriager.php          — auto-categorizes and suggests responses
│   │   ├── CallSummarizer.php         — transcribes and summarizes phone calls
│   │   └── FinancialInsights.php      — analyzes Moneybird data for trends
│   └── Tools/
│       ├── SearchContacts.php
│       ├── GetDealPipeline.php
│       ├── GetFinancials.php
│       ├── SearchContent.php
│       ├── CreateTicket.php
│       ├── GetCallHistory.php
│       └── CreateTask.php
│
├── Connectors/
│   ├── Moneybird/
│   │   ├── MoneybirdConnector.php     — Saloon HTTP connector
│   │   ├── Requests/                  — per-endpoint request classes
│   │   ├── Webhooks/                  — incoming webhook handlers
│   │   └── Sync/                      — bi-directional sync logic
│   ├── Teamleader/
│   │   ├── TeamleaderConnector.php    — Saloon connector for TL Focus API
│   │   ├── Requests/                  — RPC-style requests (contacts.list, deals.create)
│   │   ├── Webhooks/                  — TL webhook handlers
│   │   └── Sync/                      — import/export sync logic
│   └── ConnectorInterface.php         — contract for all accounting/CRM connectors
│
└── Http/
    ├── Controllers/
    │   ├── Api/             REST API controllers (Sanctum)
    │   ├── Admin/           Business admin dashboard (Inertia + React)
    │   ├── Portal/          Client portal (Inertia + React)
    │   └── Public/          Public website (Inertia SSR + React)
    └── Middleware/
```

---

## Module 6: Telephony — SIP/PBX integration with WebRTC softphone

This is the module that sets ServiceOS apart from Teamleader and every other CRM in the Benelux SMB market. Phone calls happen inside the CRM — not in a separate app.

### Architecture overview

```
┌─────────────────────────────────────────────────────────────────────┐
│                     TELEPHONY ARCHITECTURE                          │
│                                                                     │
│  ┌───────────────────┐         ┌──────────────────────┐            │
│  │  React Frontend    │         │  External SIP Phones  │            │
│  │                    │         │  (desk phones, mobile) │            │
│  │  ┌──────────────┐ │         └──────────┬───────────┘            │
│  │  │ SoftPhone     │ │                    │                        │
│  │  │ Component     │ │                    │ SIP/RTP               │
│  │  │ (JsSIP)       │ │                    │                        │
│  │  └──────┬───────┘ │                    │                        │
│  │         │ WebRTC   │                    │                        │
│  │         │ (WSS)    │                    │                        │
│  └─────────┼─────────┘                    │                        │
│            │                               │                        │
│            ▼                               ▼                        │
│  ┌─────────────────────────────────────────────────┐               │
│  │         ASTERISK / FreeSWITCH PBX               │               │
│  │                                                  │               │
│  │  • WebSocket listener (WSS) for WebRTC clients   │               │
│  │  • SIP registrar for hardware phones             │               │
│  │  • Call routing (ring groups, IVR, queues)        │               │
│  │  • Call recording (stored to S3/MinIO)            │               │
│  │  • DTLS-SRTP ↔ RTP transcoding                   │               │
│  │  • Conference bridges                             │               │
│  │  • Voicemail with email notification              │               │
│  └─────────────────┬───────────────────────────────┘               │
│                    │                                                │
│         ┌──────────┼──────────┐                                    │
│         │          │          │                                     │
│         ▼          ▼          ▼                                     │
│  ┌──────────┐ ┌────────┐ ┌──────────────┐                         │
│  │ SIP Trunk │ │ PSTN   │ │ Laravel API  │                         │
│  │ Provider  │ │Gateway │ │ (AMI/ESL)    │                         │
│  │ (VoIP)   │ │        │ │              │                         │
│  └──────────┘ └────────┘ │ • Call events │                         │
│                           │ • CDR records │                         │
│                           │ • Recording   │                         │
│                           │   webhooks    │                         │
│                           └──────┬───────┘                         │
│                                  │                                  │
│                                  ▼                                  │
│  ┌─────────────────────────────────────────────────┐               │
│  │         LARAVEL TELEPHONY MODULE                 │               │
│  │                                                  │               │
│  │  • Log call to CallLog (contact linked)          │               │
│  │  • Store recording to S3, link to CallRecording  │               │
│  │  • Queue transcription job (Whisper/AI)          │               │
│  │  • AI agent summarizes call → CallNote           │               │
│  │  • Create follow-up Task if needed               │               │
│  │  • Broadcast events via Reverb to React          │               │
│  └─────────────────────────────────────────────────┘               │
└─────────────────────────────────────────────────────────────────────┘
```

### PBX integration — Asterisk AMI / FreeSWITCH ESL

```php
// app/Domain/Telephony/Services/PbxInterface.php
// Abstraction over Asterisk AMI or FreeSWITCH ESL

interface PbxInterface
{
    // Call control
    public function originate(string $from, string $to, array $options = []): string; // returns call_id
    public function hangup(string $callId): void;
    public function transfer(string $callId, string $target): void;
    public function hold(string $callId): void;
    public function unhold(string $callId): void;
    public function startRecording(string $callId): void;
    public function stopRecording(string $callId): string; // returns recording path

    // Configuration
    public function createExtension(SipAccount $account): void;
    public function deleteExtension(SipAccount $account): void;
    public function configureRingGroup(RingGroup $group): void;
    public function configureIvr(IvrConfig $config): void;

    // Events
    public function onCallStarted(Closure $callback): void;
    public function onCallEnded(Closure $callback): void;
    public function onCallRecorded(Closure $callback): void;
}

// Asterisk implementation using AMI (Asterisk Manager Interface)
class AsteriskManager implements PbxInterface
{
    private AmiClient $ami;

    public function originate(string $from, string $to, array $options = []): string
    {
        $response = $this->ami->send(new OriginateAction(
            channel: "PJSIP/{$from}",
            extension: $to,
            context: 'serviceos-outbound',
            priority: 1,
            callerid: $options['caller_id'] ?? $from,
            variables: [
                'TENANT_ID' => Tenant::current()->id,
                'CONTACT_ID' => $options['contact_id'] ?? '',
                'RECORD' => $options['record'] ? 'true' : 'false',
            ],
        ));

        return $response->getActionId();
    }

    public function startRecording(string $callId): void
    {
        $filename = sprintf('%s/%s_%s',
            Tenant::current()->id,
            now()->format('Y-m-d_H-i-s'),
            $callId
        );

        $this->ami->send(new MixMonitorAction(
            channel: $callId,
            file: "/var/spool/asterisk/recording/{$filename}.wav",
            options: 'b',  // both directions
        ));
    }
}
```

### SIP account management per tenant

```php
class SipAccount extends Model
{
    use UsesTenantConnection;

    protected $casts = [
        'type' => SipAccountType::class,  // extension, ring_group, queue, ivr
        'settings' => AsCollection::class,
        'is_active' => 'boolean',
    ];

    public function teamMember(): BelongsTo
    {
        return $this->belongsTo(TeamMember::class);
    }

    // Each team member gets a SIP extension
    // WebRTC credentials are provisioned automatically
    public function getWebRtcConfig(): array
    {
        return [
            'domain' => config('telephony.sip_domain'),
            'uri' => "sip:{$this->extension}@" . config('telephony.sip_domain'),
            'password' => decrypt($this->sip_password),
            'ws_servers' => config('telephony.websocket_url'),  // wss://pbx.serviceos.nl:8089/ws
            'display_name' => $this->teamMember->name,
        ];
    }
}
```

### React softphone component — the heart of the telephony UX

```tsx
// src/components/telephony/SoftphoneWidget.tsx

import { useState, useCallback } from 'react';
import { useSip } from '@/hooks/useSip';
import { useCall } from '@/hooks/useCall';
import { Dialpad } from './Dialpad';
import { CallControls } from './CallControls';
import { IncomingCallModal } from './IncomingCallModal';
import { CallNotes } from './CallNotes';

export function SoftphoneWidget() {
    const { status, register, unregister } = useSip();
    const {
        activeCall,
        incomingCall,
        callHistory,
        makeCall,
        answerCall,
        rejectCall,
        endCall,
        holdCall,
        transferCall,
        toggleMute,
        toggleRecord,
    } = useCall();

    const [dialNumber, setDialNumber] = useState('');
    const [showNotes, setShowNotes] = useState(false);

    const handleDial = useCallback(() => {
        if (dialNumber) {
            makeCall(dialNumber);
            setDialNumber('');
        }
    }, [dialNumber, makeCall]);

    return (
        <div className="fixed bottom-4 right-4 w-80 bg-white rounded-2xl shadow-2xl
                        border border-gray-200 overflow-hidden z-50">
            {/* Status bar */}
            <div className="px-4 py-2 bg-gray-50 flex items-center justify-between">
                <span className={`w-2 h-2 rounded-full ${
                    status === 'registered' ? 'bg-green-500' : 'bg-red-500'
                }`} />
                <span className="text-xs text-gray-500">
                    {status === 'registered' ? 'Ready' : 'Offline'}
                </span>
            </div>

            {/* Incoming call overlay */}
            {incomingCall && (
                <IncomingCallModal
                    call={incomingCall}
                    onAnswer={answerCall}
                    onReject={rejectCall}
                />
            )}

            {/* Active call view */}
            {activeCall ? (
                <div className="p-4">
                    <div className="text-center mb-4">
                        <p className="text-lg font-semibold">{activeCall.contactName || activeCall.number}</p>
                        <p className="text-sm text-gray-500">{activeCall.duration}</p>
                        {activeCall.contactId && (
                            <a href={`/crm/contacts/${activeCall.contactId}`}
                               className="text-xs text-blue-600 hover:underline">
                                View in CRM
                            </a>
                        )}
                    </div>
                    <CallControls
                        isMuted={activeCall.isMuted}
                        isOnHold={activeCall.isOnHold}
                        isRecording={activeCall.isRecording}
                        onToggleMute={toggleMute}
                        onToggleHold={holdCall}
                        onToggleRecord={toggleRecord}
                        onTransfer={transferCall}
                        onEnd={endCall}
                    />
                    {showNotes && <CallNotes callId={activeCall.id} />}
                </div>
            ) : (
                <Dialpad
                    value={dialNumber}
                    onChange={setDialNumber}
                    onDial={handleDial}
                />
            )}
        </div>
    );
}
```

### useSip hook — JsSIP/SIP.js wrapper

```tsx
// src/hooks/useSip.ts

import { useEffect, useRef, useState, useCallback } from 'react';
import JsSIP from 'jssip';

type SipStatus = 'disconnected' | 'connecting' | 'registered' | 'error';

export function useSip() {
    const uaRef = useRef<JsSIP.UA | null>(null);
    const [status, setStatus] = useState<SipStatus>('disconnected');

    const register = useCallback((config: SipConfig) => {
        const socket = new JsSIP.WebSocketInterface(config.ws_servers);

        const ua = new JsSIP.UA({
            sockets: [socket],
            uri: config.uri,
            password: config.password,
            display_name: config.display_name,
            register: true,
            session_timers: true,
            // SRTP required for WebRTC
            rtcpMuxPolicy: 'require',
        });

        ua.on('registered', () => setStatus('registered'));
        ua.on('unregistered', () => setStatus('disconnected'));
        ua.on('registrationFailed', () => setStatus('error'));

        ua.on('newRTCSession', (data) => {
            // Incoming or outgoing call — handled by useCall hook
            window.dispatchEvent(new CustomEvent('sip:newSession', { detail: data }));
        });

        ua.start();
        uaRef.current = ua;
        setStatus('connecting');
    }, []);

    const unregister = useCallback(() => {
        uaRef.current?.stop();
        uaRef.current = null;
        setStatus('disconnected');
    }, []);

    const makeCall = useCallback((target: string, options?: CallOptions) => {
        if (!uaRef.current) throw new Error('SIP not registered');

        return uaRef.current.call(`sip:${target}@${config.domain}`, {
            mediaConstraints: { audio: true, video: false },
            rtcOfferConstraints: { offerToReceiveAudio: true },
            ...options,
        });
    }, []);

    return { status, register, unregister, makeCall, ua: uaRef };
}
```

### Call lifecycle — Laravel backend

```php
// When Asterisk fires a call event via AMI, Laravel processes it:

class HandleCallEvent implements ShouldQueue
{
    public function handle(CallEvent $event): void
    {
        $tenant = Tenant::find($event->variables['TENANT_ID']);

        $tenant->execute(function () use ($event) {
            match ($event->type) {
                'call_started' => $this->handleCallStarted($event),
                'call_answered' => $this->handleCallAnswered($event),
                'call_ended' => $this->handleCallEnded($event),
                'recording_complete' => $this->handleRecordingComplete($event),
            };
        });
    }

    private function handleCallStarted(CallEvent $event): void
    {
        // Auto-detect contact by phone number
        $contact = Contact::where('phone', $event->callerNumber)
            ->orWhere('mobile', $event->callerNumber)
            ->first();

        $callLog = CallLog::create([
            'call_id' => $event->callId,
            'direction' => $event->direction,  // inbound / outbound
            'from_number' => $event->callerNumber,
            'to_number' => $event->calledNumber,
            'contact_id' => $contact?->id,
            'team_member_id' => $event->extension?->teamMember?->id,
            'status' => 'ringing',
            'started_at' => now(),
        ]);

        // Broadcast to React — show incoming call with contact info
        broadcast(new CallStarted($callLog, $contact))->toOthers();
    }

    private function handleCallEnded(CallEvent $event): void
    {
        $callLog = CallLog::where('call_id', $event->callId)->first();
        $callLog->update([
            'status' => 'completed',
            'ended_at' => now(),
            'duration_seconds' => $event->duration,
        ]);

        // Log as activity on the contact timeline
        if ($callLog->contact_id) {
            Activity::create([
                'contact_id' => $callLog->contact_id,
                'type' => 'phone_call',
                'subject' => "{$callLog->direction} call ({$callLog->formatted_duration})",
                'data' => ['call_log_id' => $callLog->id],
            ]);
        }

        broadcast(new CallEnded($callLog));
    }

    private function handleRecordingComplete(CallEvent $event): void
    {
        // Move recording from Asterisk spool to S3
        $s3Path = CallRecordingService::store($event->recordingPath, $event->callId);

        $recording = CallRecording::create([
            'call_log_id' => $event->callLogId,
            'file_path' => $s3Path,
            'duration_seconds' => $event->duration,
            'file_size' => $event->fileSize,
        ]);

        // Queue AI transcription + summarization
        TranscribeCallRecording::dispatch($recording);
    }
}
```

### AI call transcription + smart notes

```php
// After recording is stored, AI processes it:

class TranscribeCallRecording implements ShouldQueue
{
    public function handle(): void
    {
        // Step 1: Transcribe audio using Whisper or AI SDK
        $transcript = Ai::transcription()
            ->using('openai', 'whisper-1')
            ->from(Storage::url($this->recording->file_path))
            ->create();

        CallTranscript::create([
            'call_recording_id' => $this->recording->id,
            'call_log_id' => $this->recording->call_log_id,
            'text' => $transcript->text,
            'segments' => $transcript->segments,  // timestamped segments
            'language' => $transcript->language,
        ]);

        // Step 2: AI summarizes the call and extracts action items
        GenerateCallNotes::dispatch($this->recording->call_log_id);
    }
}

class GenerateCallNotes implements ShouldQueue
{
    public function handle(): void
    {
        $callLog = CallLog::with(['transcript', 'contact'])->find($this->callLogId);

        $result = Ai::agent(new CallSummarizer)
            ->prompt("Summarize this phone call and extract action items:

                Contact: {$callLog->contact?->name}
                Direction: {$callLog->direction}
                Duration: {$callLog->formatted_duration}

                Transcript:
                {$callLog->transcript->text}

                Provide:
                1. A concise summary (2-3 sentences)
                2. Key points discussed
                3. Action items / follow-up tasks
                4. Sentiment (positive, neutral, negative)
                5. Suggested next step")
            ->respond();

        CallNote::create([
            'call_log_id' => $callLog->id,
            'summary' => $result->summary,
            'key_points' => $result->key_points,
            'action_items' => $result->action_items,
            'sentiment' => $result->sentiment,
            'suggested_next_step' => $result->suggested_next_step,
            'is_ai_generated' => true,
        ]);

        // Auto-create tasks from action items
        foreach ($result->action_items as $item) {
            Task::create([
                'title' => $item,
                'contact_id' => $callLog->contact_id,
                'assigned_to' => $callLog->team_member_id,
                'due_date' => now()->addBusinessDays(2),
                'source' => 'ai_call_summary',
                'call_log_id' => $callLog->id,
            ]);
        }

        // Notify the team member
        broadcast(new CallNotesGenerated($callLog));
    }
}
```

### Call summarizer AI agent

```php
class CallSummarizer implements Agent
{
    use HasStructuredOutput;

    public function provider(): string { return 'anthropic'; }
    public function model(): string { return 'claude-sonnet-4-20250514'; }

    public function schema(): JsonSchema
    {
        return new CallSummarySchema;
        // { summary: string, key_points: string[],
        //   action_items: string[], sentiment: string,
        //   suggested_next_step: string }
    }

    public function instructions(): string
    {
        return "You summarize business phone calls for a service company.
            Be concise and action-oriented. Extract concrete follow-up tasks.
            If the caller expressed frustration, flag it.
            Write in the language the call was conducted in (Dutch or English).
            Format action items as clear, assignable tasks.";
    }
}
```

### Telephony server setup

```
┌─────────────────────────────────────────────┐
│  ASTERISK PBX (or FreeSWITCH)               │
│                                              │
│  Ubuntu 24.04 LTS                            │
│  ├── Asterisk 22 LTS with PJSIP             │
│  │   ├── WebSocket listener (WSS :8089)     │
│  │   ├── PJSIP endpoints (per-tenant)       │
│  │   ├── Dialplan (serviceos context)        │
│  │   ├── ARI (Asterisk REST Interface)       │
│  │   └── Recording spool → S3 sync          │
│  ├── STUN/TURN server (coturn)              │
│  │   └── Required for WebRTC NAT traversal  │
│  └── SIP trunk to VoIP provider             │
│      └── e.g., Voys, Voipgrid, Twilio SIP   │
└─────────────────────────────────────────────┘
```

### Click-to-call from CRM

The softphone is always present as a floating widget in the React admin panel. Any phone number in the CRM becomes clickable:

```tsx
// src/components/crm/PhoneLink.tsx

export function PhoneLink({ number, contactId }: { number: string; contactId?: string }) {
    const { makeCall } = useCall();

    const handleClick = (e: React.MouseEvent) => {
        e.preventDefault();
        makeCall(number, { contact_id: contactId });
    };

    return (
        <a href={`tel:${number}`} onClick={handleClick}
           className="text-blue-600 hover:underline cursor-pointer">
            {number}
        </a>
    );
}
```

Incoming calls auto-pop the contact record if the number is recognized.

---

## Module 7: Projects + Time Tracking (Teamleader-inspired)

Directly inspired by Teamleader's project management and time tracking features.

### Data model

```php
class Project extends Model
{
    use UsesTenantConnection, HasActivities;

    protected $casts = [
        'status' => ProjectStatus::class,  // active, on_hold, completed, archived
        'budget_amount' => 'decimal:2',
        'budget_type' => BudgetType::class,  // fixed_price, time_materials, non_billable
        'start_date' => 'date',
        'due_date' => 'date',
        'settings' => AsCollection::class,
    ];

    public function contact(): BelongsTo { return $this->belongsTo(Contact::class); }
    public function company(): BelongsTo { return $this->belongsTo(Company::class); }
    public function deal(): BelongsTo { return $this->belongsTo(Deal::class); }
    public function tasks(): HasMany { return $this->hasMany(Task::class); }
    public function milestones(): HasMany { return $this->hasMany(Milestone::class); }
    public function timeEntries(): HasMany { return $this->hasMany(TimeEntry::class); }

    // Profitability: budget vs actual time spent
    public function getSpentAmountAttribute(): float
    {
        return $this->timeEntries->sum(fn (TimeEntry $e) =>
            ($e->duration_minutes / 60) * $e->hourly_rate
        );
    }

    public function getProfitMarginAttribute(): float
    {
        if ($this->budget_amount <= 0) return 0;
        return (($this->budget_amount - $this->spent_amount) / $this->budget_amount) * 100;
    }
}

class Task extends Model
{
    use UsesTenantConnection;

    protected $casts = [
        'status' => TaskStatus::class,      // todo, in_progress, review, done
        'priority' => TaskPriority::class,   // low, medium, high, urgent
        'due_date' => 'date',
        'estimated_minutes' => 'integer',
    ];

    public function project(): BelongsTo { return $this->belongsTo(Project::class); }
    public function assignee(): BelongsTo { return $this->belongsTo(TeamMember::class, 'assigned_to'); }
    public function milestone(): BelongsTo { return $this->belongsTo(Milestone::class); }
    public function timeEntries(): HasMany { return $this->hasMany(TimeEntry::class); }

    // Can be linked to a call or CRM activity
    public function callLog(): BelongsTo { return $this->belongsTo(CallLog::class); }
    public function contact(): BelongsTo { return $this->belongsTo(Contact::class); }
}

class TimeEntry extends Model
{
    use UsesTenantConnection;

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
        'duration_minutes' => 'integer',
        'hourly_rate' => 'decimal:2',
        'is_billable' => 'boolean',
    ];

    public function project(): BelongsTo { return $this->belongsTo(Project::class); }
    public function task(): BelongsTo { return $this->belongsTo(Task::class); }
    public function teamMember(): BelongsTo { return $this->belongsTo(TeamMember::class); }
}

// Active timer — like Teamleader's time tracking with start/stop
class Timer extends Model
{
    use UsesTenantConnection;

    public function start(): void
    {
        $this->update(['started_at' => now(), 'is_running' => true]);
        broadcast(new TimerStarted($this));
    }

    public function stop(): TimeEntry
    {
        $duration = now()->diffInMinutes($this->started_at);

        $entry = TimeEntry::create([
            'project_id' => $this->project_id,
            'task_id' => $this->task_id,
            'team_member_id' => $this->team_member_id,
            'started_at' => $this->started_at,
            'ended_at' => now(),
            'duration_minutes' => $duration,
            'hourly_rate' => $this->teamMember->hourly_rate,
            'is_billable' => $this->project->budget_type !== 'non_billable',
            'description' => $this->description,
        ]);

        $this->update(['is_running' => false, 'started_at' => null]);
        broadcast(new TimerStopped($this, $entry));

        return $entry;
    }
}
```

### React timer component

```tsx
// Timer runs in the admin toolbar — always visible
// Can be started from any context: project, task, contact, even during a phone call

export function TimerWidget() {
    const { activeTimer, start, stop, elapsed } = useTimer();

    return (
        <div className="flex items-center gap-2 px-3 py-1.5 bg-gray-50 rounded-lg">
            {activeTimer ? (
                <>
                    <span className="text-sm font-mono text-red-600">
                        {formatDuration(elapsed)}
                    </span>
                    <span className="text-xs text-gray-500 truncate max-w-32">
                        {activeTimer.project?.name}
                    </span>
                    <button onClick={stop}
                        className="p-1 rounded bg-red-100 text-red-600 hover:bg-red-200">
                        <StopIcon className="w-3.5 h-3.5" />
                    </button>
                </>
            ) : (
                <button onClick={() => start()}
                    className="text-sm text-gray-600 hover:text-gray-900">
                    Start timer
                </button>
            )}
        </div>
    );
}
```

---

## Teamleader Focus API connector

For businesses already using Teamleader, ServiceOS can import data and maintain sync.

### Connector architecture

```php
// app/Connectors/Teamleader/TeamleaderConnector.php

class TeamleaderConnector extends Connector
{
    // Teamleader uses RPC-style: POST to resource.action
    public function resolveBaseUrl(): string
    {
        return 'https://api.focus.teamleader.eu';
    }

    protected function defaultHeaders(): array
    {
        return [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $this->accessToken,
        ];
    }

    // Teamleader rate limiting: sliding window, headers indicate remaining
    protected function defaultMiddleware(): MiddlewarePipeline
    {
        return new MiddlewarePipeline([
            new TeamleaderRateLimitMiddleware(),
        ]);
    }
}

// RPC-style request classes
class ContactsList extends Request
{
    protected Method $method = Method::POST;

    public function resolveEndpoint(): string
    {
        return '/contacts.list';
    }

    protected function defaultBody(): array
    {
        return [
            'page' => ['size' => 100, 'number' => $this->page],
            'sort' => [['field' => 'updated_since', 'order' => 'desc']],
        ];
    }
}

class DealsCreate extends Request
{
    protected Method $method = Method::POST;

    public function resolveEndpoint(): string
    {
        return '/deals.create';
    }
}

class TimeTrackingStart extends Request
{
    protected Method $method = Method::POST;

    public function resolveEndpoint(): string
    {
        return '/timeTracking.start';
    }
}
```

### Teamleader API endpoints we map

| TL Endpoint | Direction | ServiceOS Module |
|---|---|---|
| `contacts.list / .info / .create / .update` | Bi-directional | CRM contacts |
| `companies.list / .info / .create / .update` | Bi-directional | CRM companies |
| `deals.list / .info / .create / .move` | Bi-directional | CRM deals |
| `quotations.list / .info / .create / .send` | Import | CRM quotations |
| `invoices.list / .info / .book / .send` | Import | Finance invoices |
| `creditNotes.list / .info` | Import | Finance |
| `projects-v2/*` | Import | Projects |
| `tasks.list / .create / .complete` | Bi-directional | Projects tasks |
| `timeTracking.list / .start / .stop` | Import | Time tracking |
| `events.list / .info` | Import | Calendar |
| `tickets.list / .info` | Import | Ticketing |
| `users.list / .me` | Import | Team |
| `customFieldDefinitions.list` | Import | Custom fields |
| `webhooks.register / .list` | Setup | Event sync |

### Teamleader webhook handling

```php
// Teamleader sends webhooks for events like deal.won, contact.updated, invoice.booked

Route::post('/webhooks/teamleader', [TeamleaderWebhookController::class, 'handle']);

class TeamleaderWebhookController extends Controller
{
    public function handle(Request $request): Response
    {
        $event = $request->input('subject_type') . '.' . $request->input('event');
        $entityId = $request->input('subject_id');

        match ($event) {
            'contact.added', 'contact.updated' => SyncTeamleaderContact::dispatch($entityId),
            'company.added', 'company.updated' => SyncTeamleaderCompany::dispatch($entityId),
            'deal.won' => HandleTeamleaderDealWon::dispatch($entityId),
            'deal.lost' => HandleTeamleaderDealLost::dispatch($entityId),
            'invoice.booked' => SyncTeamleaderInvoice::dispatch($entityId),
            default => Log::info("Unhandled TL webhook: {$event}"),
        };

        return response()->json(['ok' => true]);
    }
}
```

---

## Quotation module — Teamleader-inspired

Teamleader's quotation flow is excellent: create quote → send with link → client views online → signs digitally → converts to project/invoice. We replicate this.

```php
class Quotation extends Model
{
    use UsesTenantConnection, HasActivities;

    protected $casts = [
        'status' => QuotationStatus::class,  // draft, sent, viewed, accepted, rejected, expired
        'valid_until' => 'date',
        'total_excl_vat' => 'decimal:2',
        'total_incl_vat' => 'decimal:2',
        'signed_at' => 'datetime',
        'signed_ip' => 'string',
        'viewed_at' => 'datetime',
    ];

    public function contact(): BelongsTo { return $this->belongsTo(Contact::class); }
    public function company(): BelongsTo { return $this->belongsTo(Company::class); }
    public function deal(): BelongsTo { return $this->belongsTo(Deal::class); }
    public function lines(): HasMany { return $this->hasMany(QuotationLine::class)->orderBy('position'); }

    // Public URL for client viewing + signing
    public function getPublicUrl(): string
    {
        return route('quotations.public', ['token' => $this->public_token]);
    }

    // On accept: create Moneybird invoice + project
    public function accept(string $signatureData, string $ipAddress): void
    {
        $this->update([
            'status' => 'accepted',
            'signed_at' => now(),
            'signed_ip' => $ipAddress,
            'signature' => $signatureData,
        ]);

        if ($this->deal) {
            $this->deal->update(['status' => 'won']);
        }

        CreateInvoiceFromQuotation::dispatch($this);
        CreateProjectFromQuotation::dispatch($this);
        TriggerAutomation::dispatch($this->contact, 'quotation_accepted');
    }
}
```

---

## The inbound marketing funnel — automated end-to-end

```
┌─────────────────────────────────────────────────────────────────────┐
│                    INBOUND MARKETING FUNNEL                         │
│                                                                     │
│  1. ATTRACT                                                         │
│  ┌──────────────────┐                                               │
│  │  Public Website   │  SEO pages, blog, AI-written service         │
│  │  (CMS module)     │  descriptions, auto sitemap + structured data│
│  └────────┬─────────┘                                               │
│           ▼                                                         │
│  2. CAPTURE                                                         │
│  ┌──────────────────┐                                               │
│  │  Forms + Chatbot  │  Contact forms, quote requests, AI chatbot   │
│  │  + Phone (SIP)    │  on every page. Inbound phone calls auto-    │
│  │                   │  linked to contacts, recorded, transcribed.   │
│  └────────┬─────────┘                                               │
│           ▼                                                         │
│  3. QUALIFY (automated)                                             │
│  ┌──────────────────┐                                               │
│  │  Lead Qualifier   │  AI scores form data + call transcripts.     │
│  │  (AI Agent)       │  Routes to correct pipeline stage.           │
│  └────────┬─────────┘                                               │
│           ▼                                                         │
│  4. NURTURE (automated)                                             │
│  ┌──────────────────┐                                               │
│  │  Email Sequences  │  Mailcoach drip: welcome → value → case      │
│  │  + Phone follow-up│  study → offer. AI suggests when to call.    │
│  └────────┬─────────┘                                               │
│           ▼                                                         │
│  5. QUOTE + CONVERT                                                 │
│  ┌──────────────────┐                                               │
│  │  Quotation Builder│  Create quote from deal → send link →        │
│  │  + E-signature    │  client views online → signs → auto-invoice  │
│  │  → Moneybird      │  created in Moneybird.                       │
│  └────────┬─────────┘                                               │
│           ▼                                                         │
│  6. DELIVER                                                         │
│  ┌──────────────────┐                                               │
│  │  Projects + Time  │  Project created from accepted quote.        │
│  │  Tracking         │  Tasks, milestones, time tracking, budget    │
│  │                   │  monitoring. Profitability per project.       │
│  └────────┬─────────┘                                               │
│           ▼                                                         │
│  7. SERVE                                                           │
│  ┌──────────────────┐                                               │
│  │  Ticketing +      │  Client portal. Tickets, messaging,         │
│  │  Messaging +      │  phone support — all logged in CRM.         │
│  │  Phone Support    │  AI summarizes every interaction.            │
│  └────────┬─────────┘                                               │
│           ▼                                                         │
│  8. RETAIN (automated)                                              │
│  ┌──────────────────┐                                               │
│  │  Re-engagement    │  Check-ins, satisfaction surveys, upsell     │
│  │  + Review requests│  sequences, Google review requests.          │
│  └──────────────────┘                                               │
└─────────────────────────────────────────────────────────────────────┘
```

---

## Moneybird connector — unchanged from v1

Full bi-directional sync using Saloon PHP. Endpoints: contacts, sales_invoices, recurring_sales_invoices, estimates, financial_mutations, financial_statements, ledger_accounts, products, tax_rates, payments, reports, webhooks.

Rate limit: 150 requests per 5 minutes (50 for `/reports/`). Reports endpoint uses separate stricter limit per the Moneybird API documentation.

The `AccountingConnector` interface is now shared between Moneybird and future Teamleader invoicing import:

```php
interface AccountingConnector
{
    public function getContacts(int $page = 1): PaginatedResult;
    public function createContact(ContactData $data): string;
    public function updateContact(string $externalId, ContactData $data): void;
    public function getInvoices(int $page = 1, ?string $state = null): PaginatedResult;
    public function createInvoice(InvoiceData $data): string;
    public function sendInvoice(string $externalId): void;
    public function getFinancialMutations(Carbon $from, Carbon $to): Collection;
    public function getLedgerAccounts(): Collection;
    public function getProducts(): Collection;
    public function registerWebhook(string $url, array $events): void;
}
```

---

## Feature flags — plan-based modules

| Module | Starter (€29/mo) | Growth (€59/mo) | Pro (€99/mo) |
|---|---|---|---|
| CMS (pages + blog) | 5 pages, 10 posts | Unlimited | Unlimited |
| CRM contacts | 250 | 2,500 | Unlimited |
| Deal pipelines | 1 | 3 | Unlimited |
| Quotations with e-sign | 5/month | Unlimited | Unlimited |
| Projects + time tracking | 3 active | 10 active | Unlimited |
| Ticketing | 50/month | Unlimited | Unlimited |
| Messaging channels | Email only | + WhatsApp | + Live chat |
| Telephony (SIP) | — | 3 extensions | Unlimited extensions |
| Call recording + AI notes | — | — | ✓ |
| Marketing sequences | 1 | 5 | Unlimited |
| Moneybird integration | View-only dashboard | Full sync | Full sync + auto-invoice |
| Teamleader import | — | One-time import | Continuous sync |
| AI agents | — | Website chatbot | All agents |
| Custom domain | — | ✓ | ✓ |
| Team members | 1 | 5 | Unlimited |
| Client portal | — | ✓ | ✓ |
| API access | — | — | ✓ |

---

## Server architecture (Debian/Ubuntu)

### Full-stack setup

```
Ubuntu 24.04 LTS
├── Nginx (reverse proxy + static)
├── PHP 8.4-FPM (Laravel 13)
├── Node.js 22 LTS (React SSR via Inertia, Vite build)
├── MySQL 8.x (landlord + tenant DBs)
├── Redis 7 (cache, queue, sessions, Reverb)
├── Typesense 27.x (search engine)
├── Postfix + OpenDKIM (mail transport)
├── Asterisk 22 LTS (PBX)
│   ├── PJSIP (WebSocket + SIP trunks)
│   ├── ARI (REST interface for Laravel)
│   └── Recording spool → S3 sync
├── coturn (STUN/TURN for WebRTC NAT traversal)
├── Supervisor
│   ├── php artisan horizon       (queue workers)
│   ├── php artisan reverb:start  (WebSocket)
│   ├── typesense-server          (search)
│   └── asterisk                  (PBX)
├── Certbot (Let's Encrypt SSL)
└── MinIO (S3-compatible: media, recordings, documents)
```

### Scaling path

```
Phase 1: Single server (€60/mo VPS — slightly higher for Asterisk)
  → handles ~50 businesses, ~5k contacts, ~20 concurrent calls

Phase 2: Separate PBX + DB servers
  → Asterisk on dedicated node (low latency critical for voice)
  → MySQL on dedicated node
  → Typesense on dedicated node

Phase 3: Horizontal scaling
  → Multiple app servers behind load balancer
  → Asterisk cluster with Kamailio SIP proxy
  → Redis Cluster
  → Shared MinIO/S3
```

---

## Build sequence — phased delivery

### Sprint 1–2 (weeks 1–4): Foundation

```bash
laravel new serviceos
composer require spatie/laravel-multitenancy
composer require laravel/ai laravel/scout laravel/horizon
composer require laravel/reverb laravel/sanctum
composer require laravel/pennant laravel/pulse
composer require sammyjo20/saloon

# React frontend setup
npm install react react-dom @inertiajs/react
npm install -D @types/react @types/react-dom typescript
npm install -D tailwindcss @tailwindcss/vite
npm install jssip                              # SIP library
npm install @tiptap/react @tiptap/starter-kit  # Block editor
npm install recharts                           # Charts
npm install @dnd-kit/core @dnd-kit/sortable    # Drag and drop (Kanban)
```

- Multi-tenancy setup (landlord DB, tenant finder, switch tasks)
- Auth system (Fortify + Sanctum, team member roles)
- Base domain models: Business (tenant), TeamMember, Contact, Company
- React + Inertia setup, Tailwind CSS, component library (shadcn-style)
- Admin dashboard shell with sidebar navigation
- CI/CD pipeline (GitHub Actions, Pest + Vitest tests)

### Sprint 3–4 (weeks 5–8): CMS + Public Site

- Page builder: Tiptap-based block editor with section types
- Blog/posts with categories and tags
- Form builder with field types and submission handling
- Theme engine: per-tenant branding (colors, logo, fonts)
- Public site rendering (Inertia SSR for SEO)
- Auto-SEO: meta tags, sitemap.xml, structured data (JSON-LD)
- Scout + Typesense integration

### Sprint 5–6 (weeks 9–12): CRM + Deals + Quotations

- Contact and company management with custom fields
- Deal pipelines with drag-and-drop Kanban (React + dnd-kit)
- Activity timeline on contacts and deals
- Lead scoring engine with configurable rules
- Quotation builder with line items, VAT calculation
- E-signature flow: public link → view → sign → accept
- Contact import (CSV, vCard)
- Global search (Typesense) across all modules

### Sprint 7–8 (weeks 13–16): Moneybird + Teamleader Integration + Finance Dashboard

- Moneybird Saloon connector with all endpoints
- OAuth2 flow for connecting tenant's Moneybird account
- Bi-directional contact sync (CRM ↔ Moneybird)
- Invoice creation from accepted quotations
- Moneybird webhook handling
- Financial dashboard (React + Recharts): revenue, cash flow, P&L, aging, BTW
- Teamleader Focus connector: OAuth2 + contact/company/deal import
- One-time migration tool: "Import from Teamleader"

### Sprint 9–10 (weeks 17–20): Ticketing + Messaging

- Ticket management: create, assign, prioritize, SLA tracking
- Inbound email → ticket parsing
- Multi-channel messaging: email, WhatsApp Business API
- React team inbox with real-time updates (Reverb)
- Canned responses with variable substitution
- Client portal (React): tickets, invoices, messages, documents

### Sprint 11–12 (weeks 21–24): Telephony — SIP/PBX

- Asterisk setup: PJSIP, WebSocket, ARI
- coturn (STUN/TURN) server setup
- SIP account provisioning per tenant/team member
- React softphone widget (JsSIP): dial, answer, hold, transfer, record
- Click-to-call from any phone number in CRM
- Incoming call screen pop with contact recognition
- Call logging with automatic CRM activity creation
- Call recording → S3 storage
- Voicemail system with email notification
- Ring groups and basic IVR per tenant

### Sprint 13–14 (weeks 25–28): Projects + Time Tracking + AI

- Project management: tasks, milestones, Kanban board
- Time tracking with start/stop timer (React widget)
- Project profitability dashboard (budget vs actual)
- Billable time → invoice generation
- AI transcription of call recordings (Whisper)
- AI call summarizer with auto task creation
- AI agents: LeadQualifier, TicketTriager, ContentWriter, FinancialInsights
- Content embeddings for RAG search on public site

### Sprint 15–16 (weeks 29–32): Marketing Automation + Polish + Launch

```bash
composer require spatie/laravel-mailcoach
```

- Mailcoach setup + Postfix DKIM/SPF/DMARC
- Drip sequences: welcome, nurture, quote follow-up, re-engagement
- Automation triggers: form, score threshold, deal stage, invoice paid, call completed
- Team planning / resource scheduling view
- Work order module with mobile signatures
- Advanced reporting: conversion rates, team performance, call analytics
- White-label theming
- API documentation (OpenAPI spec)
- Performance optimization
- Beta launch with 3–5 pilot businesses

---

## Key Artisan commands

```bash
# Tenant management
php artisan serviceos:create-business {name} {domain}
php artisan serviceos:migrate-tenant {business_id}
php artisan serviceos:seed-tenant {business_id}

# Connectors
php artisan serviceos:sync-moneybird                    # full sync
php artisan serviceos:sync-moneybird {business_id}      # single tenant
php artisan serviceos:import-teamleader {business_id}   # one-time import
php artisan serviceos:snapshot-financials                # hourly cache

# Telephony
php artisan serviceos:provision-sip {business_id}       # create Asterisk extensions
php artisan serviceos:sync-recordings                   # spool → S3
php artisan serviceos:transcribe-pending                # queue pending transcriptions

# CRM
php artisan serviceos:import-contacts {file}
php artisan serviceos:recalculate-lead-scores

# Marketing
php artisan serviceos:process-automations

# Ticketing
php artisan serviceos:check-sla-deadlines               # every 5 min

# AI
php artisan serviceos:index-embeddings
php artisan serviceos:train-assistant

# Maintenance
php artisan horizon
php artisan pulse
```

---

## Testing strategy

```bash
php artisan test
php artisan test --group=cms
php artisan test --group=crm
php artisan test --group=telephony
php artisan test --group=ticketing
php artisan test --group=messaging
php artisan test --group=projects
php artisan test --group=moneybird
php artisan test --group=teamleader
php artisan test --group=marketing
php artisan test --group=ai-agents
php artisan test --group=multitenancy

# React component tests
npm run test              # Vitest
npm run test:e2e          # Playwright
```

Every module gets:

- **Unit tests** (Pest) for business logic
- **Feature tests** for API endpoints (Sanctum auth, tenant scoping)
- **Connector tests** using Saloon MockClient (Moneybird + Teamleader)
- **AI agent tests** using Laravel AI SDK fakes
- **React component tests** (Vitest + React Testing Library)
- **E2E tests** (Playwright) for critical flows
- **Telephony tests** using Asterisk Test Suite + mocked AMI events

---

## Summary: what makes ServiceOS powerful

1. **Replaces 7+ tools** — website builder, CRM, helpdesk, email marketing, financial dashboard, phone system, project management. One login, one bill, everything connected.

2. **Automated inbound funnel** — from SEO website → form/phone capture → AI qualification → drip nurture → quotation → e-sign → invoice → project → support portal.

3. **Built-in telephony** — WebRTC softphone in the browser (React + JsSIP), connected to Asterisk PBX. Click-to-call from CRM, automatic call logging, recording, AI transcription, smart notes with auto-generated follow-up tasks. No other Dutch SMB CRM has this built in.

4. **Teamleader feature parity plus more** — everything Teamleader Focus offers (CRM, deals, quotations, projects, time tracking, invoicing, planning, tickets, shared inbox) plus a website builder, AI agents, telephony, and deep Moneybird integration.

5. **React frontend** — modern, fast, component-based UI. Drag-and-drop Kanban, real-time updates, floating softphone widget, timer in toolbar. Built with React 19 + Inertia.js + TypeScript + Tailwind CSS.

6. **Moneybird-native + Teamleader-compatible** — deep accounting sync via Moneybird API. Import/sync from Teamleader for migration. Extensible `AccountingConnector` interface for Exact Online, Xero, etc.

7. **AI at every layer** — chatbot, lead scoring, ticket triage, content generation, call summarization, financial insights. Each agent does a specific job with real tools.

8. **Multi-tenant SaaS** — same MasjidOS architecture. Self-hostable or SaaS. One codebase, complete data isolation per business.

The result: a Dutch service business can run their entire operation — website, marketing, CRM, quotations, projects, phone calls, support, and finances — from one system. The days of juggling 7 different logins are over.
