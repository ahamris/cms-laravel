<?php

namespace App\Listeners;

use App\Events\ContactFormSubmitted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ContactFormSubmittedListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ContactFormSubmitted $event): void
    {
        \App\Models\ContactFormMessage::create([
            'contact_form_id' => $event->contactForm->id,
            'direction' => 'inbound',
            'subject' => 'Initial Submission',
            'message' => $event->contactForm->bericht,
            'sent_at' => now(),
            'status' => 'sent',
        ]);
    }
}
