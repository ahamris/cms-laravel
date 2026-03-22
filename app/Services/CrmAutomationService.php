<?php

namespace App\Services;

use App\Events\ContactFunnelChanged;
use App\Models\Contact;
use App\Models\CrmDeal;
use App\Models\FormSubmission;
use Illuminate\Support\Facades\Log;

class CrmAutomationService
{
    /**
     * Process automatic funnel transitions after form submission.
     */
    public function onFormSubmitted(FormSubmission $submission): void
    {
        if ($submission->converted_contact_id) {
            $contact = Contact::find($submission->converted_contact_id);
            if ($contact && !$contact->funnel_fase) {
                $oldStage = $contact->funnel_fase;
                $contact->update(['funnel_fase' => 'overtuig']);
                ContactFunnelChanged::dispatch($contact, $oldStage, 'overtuig');
            }
        }
    }

    /**
     * Process deal won: move contact to inspireer/customer.
     */
    public function onDealWon(CrmDeal $deal): void
    {
        if ($deal->contact) {
            $oldStage = $deal->contact->funnel_fase;
            $deal->contact->update([
                'funnel_fase'     => 'inspireer',
                'lifecycle_stage' => 'customer',
            ]);
            ContactFunnelChanged::dispatch($deal->contact, $oldStage, 'inspireer');
        }
    }

    /**
     * Process demo appointment: move contact to activeer.
     */
    public function onDemoBooked(Contact $contact): void
    {
        if (in_array($contact->funnel_fase, ['interesseer', 'overtuig'])) {
            $oldStage = $contact->funnel_fase;
            $contact->update(['funnel_fase' => 'activeer']);
            ContactFunnelChanged::dispatch($contact, $oldStage, 'activeer');
        }
    }

    /**
     * Flag stale contacts (no activity in 14 days).
     */
    public function flagStaleContacts(): int
    {
        $staleDate = now()->subDays(14);
        $staleContacts = Contact::whereNotNull('funnel_fase')
            ->where(function ($q) use ($staleDate) {
                $q->whereNull('last_activity_at')
                  ->orWhere('last_activity_at', '<', $staleDate);
            })
            ->get();

        foreach ($staleContacts as $contact) {
            $tags = $contact->tags ?? [];
            if (!in_array('stale', $tags)) {
                $tags[] = 'stale';
                $contact->update(['tags' => $tags]);
            }
        }

        return $staleContacts->count();
    }

    /**
     * Auto-assign high-score leads.
     */
    public function autoAssignHighScoreLeads(int $threshold = 70): int
    {
        $contacts = Contact::where('lead_score', '>=', $threshold)
            ->whereNull('funnel_fase')
            ->orWhere('funnel_fase', 'interesseer')
            ->get();

        foreach ($contacts as $contact) {
            $oldStage = $contact->funnel_fase;
            $contact->update([
                'funnel_fase'     => 'overtuig',
                'lifecycle_stage' => 'mql',
            ]);

            if ($oldStage !== 'overtuig') {
                ContactFunnelChanged::dispatch($contact, $oldStage, 'overtuig');
            }

            Log::info("Auto-assigned high-score lead: {$contact->organization_name} (score: {$contact->lead_score})");
        }

        return $contacts->count();
    }
}
