<?php

namespace App\Observers;

use App\Mail\DemoRequestStatusChanged;
use App\Models\Subscription;
use Illuminate\Support\Facades\Mail;

class SubscriptionObserver
{
    /**
     * Handle the Subscription "created" event.
     */
    public function created(Subscription $subscription): void
    {
        // Check if status is changing
        if ($subscription->isDirty('status')) {
            $oldStatus = $subscription->getOriginal('status');
            $newStatus = $subscription->status;

            // Store the old status for use in the updated event
            $subscription->_oldStatus = $oldStatus;
        }
    }

    /**
     * Handle the Subscription "updated" event.
     */
        public function updated(Subscription $subscription): void
    {
        // Check if status was changed
        if (isset($subscription->_oldStatus)) {
            $oldStatus = $subscription->_oldStatus;

            // Send email to customer
            try {
                Mail::to($subscription->email)
                    ->send(new DemoRequestStatusChanged($subscription, $oldStatus, false));
            } catch (\Exception $e) {

            }

            // Send email to admin
            try {
                $adminEmail = config('mail.admin_email', 'admin@openpublication.eu');
                Mail::to($adminEmail)
                    ->send(new DemoRequestStatusChanged($subscription, $oldStatus, true));
            } catch (\Exception $e) {

            }

            // Clean up
                        unset($subscription->_oldStatus);
        }
    }
}
