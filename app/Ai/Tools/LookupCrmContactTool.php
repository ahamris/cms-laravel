<?php

namespace App\Ai\Tools;

use App\Models\Contact;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

class LookupCrmContactTool implements Tool
{
    public function __construct(
        protected int $contactId,
    ) {}

    public function description(): Stringable|string
    {
        return 'Load read-only CRM summary for the linked contact (organization, funnel, lifecycle, open ticket count).';
    }

    public function handle(Request $request): Stringable|string
    {
        $id = $this->contactId;
        if ($id < 1) {
            return json_encode(['error' => 'No contact id']);
        }

        $contact = Contact::query()
            ->withCount(['tickets as open_tickets_count' => fn ($q) => $q->open()])
            ->find($id);

        if (! $contact) {
            return json_encode(['error' => 'Contact not found']);
        }

        return json_encode([
            'organization_name' => $contact->organization_name,
            'email' => $contact->email,
            'funnel_fase' => $contact->funnel_fase,
            'lifecycle_stage' => $contact->lifecycle_stage,
            'open_tickets_count' => (int) ($contact->open_tickets_count ?? 0),
        ], JSON_THROW_ON_ERROR);
    }

    public function schema(JsonSchema $schema): array
    {
        return [];
    }
}
