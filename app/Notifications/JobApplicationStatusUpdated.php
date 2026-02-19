<?php

namespace App\Notifications;

use App\Models\VacancyModule\JobApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class JobApplicationStatusUpdated extends Notification implements ShouldQueue
{
    use Queueable;

    public JobApplication $application;
    public string $oldStatus;

    /**
     * Create a new notification instance.
     */
    public function __construct(JobApplication $application, string $oldStatus)
    {
        $this->application = $application;
        $this->oldStatus = $oldStatus;
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
        $statusMessages = [
            'pending' => 'Your application is currently under review.',
            'reviewed' => 'Your application has been reviewed by our team.',
            'shortlisted' => 'Congratulations! You have been shortlisted for the next round.',
            'rejected' => 'Unfortunately, we have decided to move forward with other candidates.',
            'hired' => 'Congratulations! We are pleased to offer you the position.',
        ];

        $message = (new MailMessage)
            ->subject('Application Status Update - ' . $this->application->vacancy->title)
            ->greeting('Hello ' . $this->application->name . '!')
            ->line('Your application status for **' . $this->application->vacancy->title . '** has been updated.')
            ->line('**New Status:** ' . ucfirst($this->application->status))
            ->line($statusMessages[$this->application->status] ?? '');

        if ($this->application->status === 'shortlisted') {
            $message->line('We will contact you soon with further details about the next steps.');
        } elseif ($this->application->status === 'hired') {
            $message->line('Our HR team will reach out to you shortly with the offer details.');
        } elseif ($this->application->status === 'rejected') {
            $message->line('We appreciate your interest in joining our team and wish you the best in your job search.');
        }

        if ($this->application->notes) {
            $message->line('**Additional Notes:** ' . $this->application->notes);
        }

        return $message->line('Thank you for your interest in our company!');
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
            'old_status' => $this->oldStatus,
            'new_status' => $this->application->status,
        ];
    }
}
