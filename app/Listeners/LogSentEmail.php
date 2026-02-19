<?php

namespace App\Listeners;

use App\Models\EmailLog;
use Illuminate\Mail\Events\MessageSent;
use Illuminate\Support\Facades\Log;

class LogSentEmail
{
    /**
     * Handle the event.
     */
    public function handle(MessageSent $event): void
    {
        try {
            $message = $event->message;
            $data = $event->data;

            // Extract recipient info
            $toAddresses = $message->getTo();
            $toEmail = !empty($toAddresses) ? array_key_first($toAddresses) : null;
            $toName = !empty($toAddresses) ? reset($toAddresses)?->getName() : null;

            // Extract sender info
            $fromAddresses = $message->getFrom();
            $fromEmail = !empty($fromAddresses) ? array_key_first($fromAddresses) : null;
            $fromName = !empty($fromAddresses) ? reset($fromAddresses)?->getName() : null;

            // Get mail class name if available
            $mailClass = isset($data['__laravel_notification'])
                ? get_class($data['__laravel_notification'])
                : (isset($event->mailable) ? get_class($event->mailable) : null);

            // Extract related model if available from mailable
            $relatedType = null;
            $relatedId = null;
            $metadata = [];

            if (isset($event->mailable)) {
                $mailable = $event->mailable;

                // Check if mailable has a subscription property
                if (property_exists($mailable, 'subscription')) {
                    $relatedType = get_class($mailable->subscription);
                    $relatedId = $mailable->subscription->id;
                    $metadata['demo_application_id'] = $mailable->subscription->id;
                }

                // Check if it's admin notification
                if (property_exists($mailable, 'isAdminNotification')) {
                    $metadata['is_admin_notification'] = $mailable->isAdminNotification;
                }

                // Check for old status
                if (property_exists($mailable, 'oldStatus')) {
                    $metadata['old_status'] = $mailable->oldStatus;
                }
            }

            // Create email log
            EmailLog::create([
                'subject' => $message->getSubject(),
                'to_email' => $toEmail,
                'to_name' => $toName,
                'from_email' => $fromEmail,
                'from_name' => $fromName,
                'cc' => $this->formatAddresses($message->getCc()),
                'bcc' => $this->formatAddresses($message->getBcc()),
                'body_html' => $message->getHtmlBody(),
                'body_text' => $message->getTextBody(),
                'mail_class' => $mailClass,
                'status' => 'sent',
                'sent_at' => now(),
                'related_type' => $relatedType,
                'related_id' => $relatedId,
                'metadata' => $metadata,
            ]);

        } catch (\Exception $e) {
        }
    }

    /**
     * Format addresses array to string.
     */
    private function formatAddresses(?array $addresses): ?string
    {
        if (empty($addresses)) {
            return null;
        }

        return implode(', ', array_keys($addresses));
    }
}
