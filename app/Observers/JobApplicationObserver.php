<?php

namespace App\Observers;

use App\Models\VacancyModule\JobApplication;
use App\Notifications\JobApplicationStatusUpdated;
use Illuminate\Support\Facades\Notification;

class JobApplicationObserver
{
    /**
     * Handle the JobApplication "created" event.
     */
    public function created(JobApplication $jobApplication): void
    {
        //
    }

    /**
     * Handle the JobApplication "updated" event.
     */
    public function updated(JobApplication $jobApplication): void
    {
        // Check if status was changed
        if ($jobApplication->isDirty('status')) {
            $oldStatus = $jobApplication->getOriginal('status');

            // Send notification to applicant
            Notification::route('mail', $jobApplication->email)
                ->notify(new JobApplicationStatusUpdated($jobApplication, $oldStatus));
        }
    }

    /**
     * Handle the JobApplication "deleted" event.
     */
    public function deleted(JobApplication $jobApplication): void
    {
        //
    }

    /**
     * Handle the JobApplication "restored" event.
     */
    public function restored(JobApplication $jobApplication): void
    {
        //
    }

    /**
     * Handle the JobApplication "force deleted" event.
     */
    public function forceDeleted(JobApplication $jobApplication): void
    {
        //
    }
}
