<?php

namespace App\Notifications;

use App\Mail\CustomVerifyEmail;
use Illuminate\Auth\Notifications\VerifyEmail as VerifyEmailNotification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Mail;

class CustomVerifyEmailNotification extends VerifyEmailNotification
{
    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable): CustomVerifyEmail
    {
        return new CustomVerifyEmail($notifiable);
    }
}
