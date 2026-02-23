<?php

namespace App\Jobs;

use App\Models\ContactForm;
use App\Services\PerfexCrmService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

/**
 * Queued job to send a contact form or demo request to Perfex CRM as a lead.
 * Dispatched after contact form submission; no-op when Perfex is disabled.
 */
class SendToPerfexCrmJob implements ShouldQueue
{
    use Queueable;

    /**
     * Number of times the job may be attempted.
     */
    public int $tries = 3;

    /**
     * Seconds to wait before retrying.
     */
    public int $backoff = 60;

    /**
     * Create a new job instance.
     *
     * @param  'contact'|'demo'  $type  Use 'demo' when the form reason is a demo request (e.g. reden = demo).
     */
    public function __construct(
        public ContactForm $contactForm,
        public string $type = 'contact'
    ) {}

    /**
     * Execute the job.
     */
    public function handle(PerfexCrmService $perfex): void
    {
        if (! $perfex->isEnabled()) {
            return;
        }

        $name = trim($this->contactForm->first_name.' '.$this->contactForm->last_name) ?: 'Unknown';
        $note = $this->buildNote();

        $payload = [
            'name' => $name,
            'email' => $this->contactForm->email,
            'company' => $this->contactForm->company_name ?? '',
            'title' => $this->contactForm->reden,
            'note' => $note,
            'source' => $this->type === 'demo' ? 'demo' : config('perfex.default_lead_source', 'contact_form'),
        ];

        $result = $perfex->sendLead($payload);

        if ($result === null && $perfex->isEnabled()) {
            $this->release($this->backoff);
        }
    }

    /**
     * Build the lead description/note from contact form data.
     */
    private function buildNote(): string
    {
        $lines = [
            'Onderwerp: '.$this->contactForm->reden,
            'Bericht: '.$this->contactForm->bericht,
            'Contactvoorkeur: '.$this->contactForm->contact_preference,
            'Telefoon: '.($this->contactForm->phone ?: '—'),
        ];

        if ($this->contactForm->bijlage) {
            $lines[] = 'Bijlage: '.$this->contactForm->bijlage;
        }

        $lines[] = 'Ingediend: '.$this->contactForm->created_at?->toIso8601String();
        $lines[] = 'Bron: '.$this->type;

        return implode("\n", $lines);
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('SendToPerfexCrmJob failed after retries.', [
            'contact_form_id' => $this->contactForm->id,
            'type' => $this->type,
            'message' => $exception->getMessage(),
        ]);
    }
}
