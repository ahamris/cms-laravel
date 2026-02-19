<?php

namespace App\Mail;

use App\Models\WooRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WooRequestWorkflowEmail extends Mailable
{
    use Queueable, SerializesModels;

    public WooRequest $wooRequest;
    public string $emailSubject;
    public string $emailBody;

    /**
     * Create a new message instance.
     */
    public function __construct(WooRequest $wooRequest, string $emailSubject, string $emailBody)
    {
        $this->wooRequest = $wooRequest;
        $this->emailSubject = $emailSubject;
        $this->emailBody = $emailBody;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->emailSubject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.woo-request-workflow',
            with: [
                'wooRequest' => $this->wooRequest,
                'emailBody' => $this->emailBody,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
