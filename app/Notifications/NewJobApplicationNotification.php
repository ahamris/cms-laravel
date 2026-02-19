<?php

namespace App\Notifications;

use App\Models\VacancyModule\JobApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewJobApplicationNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public JobApplication $application;

    /**
     * Create a new notification instance.
     */
    public function __construct(JobApplication $application)
    {
        $this->application = $application;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Job Application Received - ' . $this->application->vacancy->title)
            ->greeting('Hello Admin!')
            ->line('A new job application has been submitted.')
            ->line('**Position:** ' . $this->application->vacancy->title)
            ->line('**Applicant:** ' . $this->application->name)
            ->line('**Email:** ' . $this->application->email)
            ->line('**Phone:** ' . ($this->application->phone ?? 'Not provided'))
            ->action('View Application', config('app.url') . '/admin/job-application/' . $this->application->id . '/show')
            ->line('Please review the application at your earliest convenience.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'application_id' => $this->application->id,
            'vacancy_title' => $this->application->vacancy->title,
            'applicant_name' => $this->application->name,
        ];
    }
}
