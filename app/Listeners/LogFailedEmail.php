<?php

namespace App\Listeners;

use App\Models\EmailLog;
use Illuminate\Mail\Events\MessageSendingFailed;
use Illuminate\Support\Facades\Log;

class LogFailedEmail
{
    /**
     * Handle the event.
     */
    public function handle(MessageSendingFailed $event): void
    {
        try {
            $message = $event->message;
            
            // Extract recipient info
            $toAddresses = $message->getTo();
            $toEmail = !empty($toAddresses) ? array_key_first($toAddresses) : null;
            $toName = !empty($toAddresses) ? reset($toAddresses)?->getName() : null;
            
            // Extract sender info
            $fromAddresses = $message->getFrom();
            $fromEmail = !empty($fromAddresses) ? array_key_first($fromAddresses) : null;
            $fromName = !empty($fromAddresses) ? reset($fromAddresses)?->getName() : null;
            
            // Create email log
            EmailLog::create([
                'subject' => $message->getSubject(),
                'to_email' => $toEmail,
                'to_name' => $toName,
                'from_email' => $fromEmail,
                'from_name' => $fromName,
                'body_html' => $message->getHtmlBody(),
                'body_text' => $message->getTextBody(),
                'status' => 'failed',
                'error_message' => 'Email sending failed',
                'failed_at' => now(),
            ]);
            
        } catch (\Exception $e) {

        }
    }
}
