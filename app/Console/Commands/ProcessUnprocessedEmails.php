<?php

namespace App\Console\Commands;

use App\Services\EmailService;
use Illuminate\Console\Command;

class ProcessUnprocessedEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:process-unprocessed {--limit=50 : Number of emails to process} {--retry-failed : Also retry failed emails}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process unprocessed emails from the sent_emails table';

    /**
     * Execute the console command.
     */
    public function handle(EmailService $emailService)
    {
        $limit = (int) $this->option('limit');
        $retryFailed = $this->option('retry-failed');

        $this->info("Starting email processing...");
        $this->info("Limit: {$limit} emails");

        // Get statistics before processing
        $statsBefore = $emailService->getEmailStatistics();
        $this->info("Unprocessed emails: {$statsBefore['unprocessed_emails']}");
        
        if ($retryFailed) {
            $this->info("Failed emails: {$statsBefore['failed_emails']}");
        }

        // Process unprocessed emails
        $results = $emailService->processUnprocessedEmails($limit);

        $this->info("Processed: {$results['processed']} emails");
        
        if ($results['failed'] > 0) {
            $this->error("Failed: {$results['failed']} emails");
            
            if (!empty($results['errors'])) {
                $this->error("Errors:");
                foreach ($results['errors'] as $error) {
                    if (isset($error['email_id'])) {
                        $this->error("  Email ID {$error['email_id']}: {$error['error']}");
                    } else {
                        $this->error("  General: {$error['general_error']}");
                    }
                }
            }
        }

        // Retry failed emails if requested
        if ($retryFailed && $statsBefore['failed_emails'] > 0) {
            $this->info("\nRetrying failed emails...");
            $retryResults = $emailService->retryFailedEmails(10);
            
            $this->info("Retried successfully: {$retryResults['retried']} emails");
            
            if ($retryResults['failed_again'] > 0) {
                $this->error("Failed again: {$retryResults['failed_again']} emails");
            }
        }

        // Get final statistics
        $statsAfter = $emailService->getEmailStatistics();
        
        $this->info("\n--- Final Statistics ---");
        $this->info("Total emails: {$statsAfter['total_emails']}");
        $this->info("Sent emails: {$statsAfter['sent_emails']}");
        $this->info("Failed emails: {$statsAfter['failed_emails']}");
        $this->info("Pending emails: {$statsAfter['pending_emails']}");
        $this->info("Unprocessed emails: {$statsAfter['unprocessed_emails']}");

        if ($results['processed'] > 0 || ($retryFailed && isset($retryResults) && $retryResults['retried'] > 0)) {
            $this->info("\n✅ Email processing completed successfully!");
        } else {
            $this->comment("\n📧 No emails to process.");
        }

        return Command::SUCCESS;
    }
}
