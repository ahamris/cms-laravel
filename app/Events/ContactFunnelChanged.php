<?php

namespace App\Events;

use App\Models\Contact;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ContactFunnelChanged
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Contact $contact,
        public ?string $oldStage,
        public ?string $newStage
    ) {}
}
