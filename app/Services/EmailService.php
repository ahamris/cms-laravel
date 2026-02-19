<?php

namespace App\Services;

use App\Models\MailSetting;
use App\Models\SentEmail;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class EmailService
{
    /**
     * Process unprocessed emails from the SentEmail table
     */
    public function processUnprocessedEmails(int $limit = 50): array
    {
        $results = [
            'processed' => 0,
            'failed' => 0,
            'errors' => [],
        ];

        try {
            // Configure mail settings from database
            $this->configureMailSettings();

            // Get unprocessed emails
            $unprocessedEmails = SentEmail::where('is_processed', false)
                ->where('status', 'pending')
                ->limit($limit)
                ->get();

            foreach ($unprocessedEmails as $sentEmail) {
                try {
                    $this->processSingleEmail($sentEmail);
                    $results['processed']++;
                } catch (Exception $e) {
                    $this->handleEmailFailure($sentEmail, $e);
                    $results['failed']++;
                    $results['errors'][] = [
                        'email_id' => $sentEmail->id,
                        'error' => $e->getMessage(),
                    ];
                }
            }

        } catch (Exception $e) {

            $results['errors'][] = [
                'general_error' => $e->getMessage(),
            ];
        }

        return $results;
    }

    /**
     * Process a single email from the SentEmail record
     */
    private function processSingleEmail(SentEmail $sentEmail): void
    {
        Log::info('Processing email', [
            'email_id' => $sentEmail->id,
            'to' => $sentEmail->to_email,
            'subject' => $sentEmail->subject,
        ]);

        // Parse CC and BCC emails
        $ccEmails = $sentEmail->cc_emails ?? [];
        $bccEmails = $sentEmail->bcc_emails ?? [];

        // Send email using Laravel's Mail facade
        Mail::raw($sentEmail->message, function ($mail) use ($sentEmail, $ccEmails, $bccEmails) {
            $mail->to($sentEmail->to_email)
                ->subject($sentEmail->subject);

            // Add CC recipients
            if (! empty($ccEmails)) {
                $mail->cc($ccEmails);
            }

            // Add BCC recipients
            if (! empty($bccEmails)) {
                $mail->bcc($bccEmails);
            }

            // Handle attachments if they exist
            if ($sentEmail->attachments && ! empty($sentEmail->attachments)) {
                $this->attachFiles($mail, $sentEmail->attachments);
            }
        });

        // Mark as processed and sent
        $sentEmail->update([
            'status' => 'sent',
            'is_processed' => true,
            'sent_at' => now(),
            'error_message' => null,
        ]);

    }

    /**
     * Handle email sending failure
     */
    private function handleEmailFailure(SentEmail $sentEmail, Exception $e): void
    {
        $sentEmail->update([
            'status' => 'failed',
            'is_processed' => true,
            'error_message' => $e->getMessage(),
            'sent_at' => now(),
        ]);

    }

    /**
     * Configure mail settings from database
     */
    private function configureMailSettings(): void
    {
        try {
            MailSetting::updateMailConfigForTesting();
        } catch (Exception $e) {

            // Continue with default mail configuration if database settings fail
        }
    }

    /**
     * Attach files to the mail message
     */
    private function attachFiles($mail, array $attachments): void
    {
        foreach ($attachments as $attachment) {
            if (isset($attachment['path']) && Storage::exists($attachment['path'])) {
                $mail->attach(Storage::path($attachment['path']), [
                    'as' => $attachment['name'] ?? 'attachment',
                    'mime' => $attachment['mime_type'] ?? 'application/octet-stream',
                ]);
            } else {
      
            }
        }
    }

    /**
     * Send a single email immediately (bypass queue)
     */
    public function sendEmailImmediately(array $emailData): SentEmail
    {
        // Configure mail settings
        $this->configureMailSettings();

        // Create SentEmail record
        $sentEmail = SentEmail::create([
            'to_email' => $emailData['to'],
            'cc_emails' => $emailData['cc'] ?? null,
            'bcc_emails' => $emailData['bcc'] ?? null,
            'subject' => $emailData['subject'],
            'message' => $emailData['message'],
            'user_id' => $emailData['user_id'] ?? auth()->id(),
            'attachments' => $emailData['attachments'] ?? null,
            'attachments_count' => count($emailData['attachments'] ?? []),
            'related_type' => $emailData['related_type'] ?? null,
            'related_id' => $emailData['related_id'] ?? null,
            'status' => 'pending',
            'is_processed' => false,
            'sent_at' => null,
        ]);

        try {
            $this->processSingleEmail($sentEmail);
        } catch (Exception $e) {
            $this->handleEmailFailure($sentEmail, $e);
            throw $e;
        }

        return $sentEmail;
    }

    /**
     * Queue emails for batch processing
     */
    public function queueEmail(array $emailData): SentEmail
    {
        return SentEmail::create([
            'to_email' => $emailData['to'],
            'cc_emails' => $emailData['cc'] ?? null,
            'bcc_emails' => $emailData['bcc'] ?? null,
            'subject' => $emailData['subject'],
            'message' => $emailData['message'],
            'user_id' => $emailData['user_id'] ?? auth()->id(),
            'attachments' => $emailData['attachments'] ?? null,
            'attachments_count' => count($emailData['attachments'] ?? []),
            'related_type' => $emailData['related_type'] ?? null,
            'related_id' => $emailData['related_id'] ?? null,
            'status' => 'pending',
            'is_processed' => false,
            'sent_at' => null,
        ]);
    }

    /**
     * Get email statistics
     */
    public function getEmailStatistics(): array
    {
        return [
            'total_emails' => SentEmail::count(),
            'sent_emails' => SentEmail::where('status', 'sent')->count(),
            'failed_emails' => SentEmail::where('status', 'failed')->count(),
            'pending_emails' => SentEmail::where('status', 'pending')->count(),
            'unprocessed_emails' => SentEmail::where('is_processed', false)->count(),
            'emails_today' => SentEmail::whereDate('created_at', today())->count(),
            'emails_this_week' => SentEmail::whereBetween('created_at', [
                now()->startOfWeek(),
                now()->endOfWeek(),
            ])->count(),
            'emails_this_month' => SentEmail::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
        ];
    }

    /**
     * Retry failed emails
     */
    public function retryFailedEmails(int $limit = 10): array
    {
        $failedEmails = SentEmail::where('status', 'failed')
            ->where('is_processed', true)
            ->limit($limit)
            ->get();

        $results = [
            'retried' => 0,
            'failed_again' => 0,
            'errors' => [],
        ];

        foreach ($failedEmails as $sentEmail) {
            // Reset status for retry
            $sentEmail->update([
                'status' => 'pending',
                'is_processed' => false,
                'error_message' => null,
            ]);

            try {
                $this->processSingleEmail($sentEmail);
                $results['retried']++;
            } catch (Exception $e) {
                $this->handleEmailFailure($sentEmail, $e);
                $results['failed_again']++;
                $results['errors'][] = [
                    'email_id' => $sentEmail->id,
                    'error' => $e->getMessage(),
                ];
            }
        }

        return $results;
    }

    /**
     * Test email configuration
     */
    public function testEmailConfiguration(string $testEmail): bool
    {
        try {
            $this->configureMailSettings();

            Mail::raw('This is a test email to verify your email configuration.', function ($mail) use ($testEmail) {
                $mail->to($testEmail)
                    ->subject('Email Configuration Test - '.config('app.name'));
            });

            return true;
        } catch (Exception $e) {

            return false;
        }
    }
}
