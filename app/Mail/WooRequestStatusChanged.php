<?php

namespace App\Mail;

use App\Models\WooRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WooRequestStatusChanged extends Mailable
{
    use Queueable, SerializesModels;

    public WooRequest $wooRequest;
    public string $oldStatus;
    public string $newStatus;

    /**
     * Create a new message instance.
     */
    public function __construct(WooRequest $wooRequest, string $oldStatus, string $newStatus)
    {
        $this->wooRequest = $wooRequest;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'WOO Request Status Update - ' . $this->wooRequest->tracking_number,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.woo-request-status-changed',
            with: [
                'wooRequest' => $this->wooRequest,
                'oldStatus' => $this->oldStatus,
                'newStatus' => $this->newStatus,
                'statusMessage' => $this->getStatusMessage(),
                'nextSteps' => $this->getNextSteps(),
            ]
        );
    }

    /**
     * Get status-specific message
     */
    private function getStatusMessage(): string
    {
        return match ($this->newStatus) {
            'pending' => 'Your WOO request has been received and is pending review.',
            'in_review' => 'Your WOO request is currently being reviewed by our team.',
            'approved' => 'Great news! Your WOO request has been approved.',
            'rejected' => 'We regret to inform you that your WOO request has been rejected.',
            'completed' => 'Your WOO request has been completed and the information is now available.',
            'on_hold' => 'Your WOO request has been temporarily placed on hold.',
            'requires_clarification' => 'We need additional information to process your WOO request.',
            default => 'Your WOO request status has been updated.',
        };
    }

    /**
     * Get next steps based on status
     */
    private function getNextSteps(): string
    {
        return match ($this->newStatus) {
            'pending' => 'We will review your request and contact you within 5 business days.',
            'in_review' => 'Our team is currently processing your request. You will be notified of any updates.',
            'approved' => 'We will begin processing your request and provide the requested information soon.',
            'rejected' => 'If you have questions about this decision, please contact us for more information.',
            'completed' => 'You can now access the requested information through our publication system.',
            'on_hold' => 'We will contact you with more information about the delay.',
            'requires_clarification' => 'Please check your email for specific questions or contact us directly.',
            default => 'Please contact us if you have any questions about this update.',
        };
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}
