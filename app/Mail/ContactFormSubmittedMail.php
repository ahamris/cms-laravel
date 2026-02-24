<?php

namespace App\Mail;

use App\Models\ContactForm;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class ContactFormSubmittedMail extends Mailable
{
    use Queueable, SerializesModels;

    public ContactForm $contactForm;
    public bool $isAdminEmail;

    /**
     * Create a new message instance.
     */
    public function __construct(ContactForm $contactForm, bool $isAdminEmail = false)
    {
        $this->contactForm = $contactForm;
        $this->isAdminEmail = $isAdminEmail;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->isAdminEmail
                ? 'New Contact Form Submission'
                : 'Thank you for your contact request',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: $this->isAdminEmail
                ? 'emails.contact-form-admin'
                : 'emails.contact-form-customer',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        $list = $this->contactForm->attachment_list;
        $attachments = [];
        foreach ($list as $item) {
            $path = $item['path'] ?? null;
            $name = $item['name'] ?? basename($path);
            if ($path && Storage::disk('public')->exists($path)) {
                $attachments[] = Attachment::fromStorageDisk('public', $path)
                    ->as($name);
            }
        }
        return $attachments;
    }
}
