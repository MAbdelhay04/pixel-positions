<?php

namespace App\Notifications;

use App\Models\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ApplicationStatusUpdated extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public Application $application,
        public string $jobTitle,
        public string $statusLabel,
        public string $statusValue,
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__('Your application status was updated'))
            ->view('emails.application-status-changed', [
                'application' => $this->application,
                'notifiable' => $notifiable,
                'statusLabel' => $this->statusLabel,
                'statusValue' => $this->statusValue,
            ]);
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
            'application_uuid' => $this->application->uuid,
            'job_title' => $this->jobTitle,
            'status' => $this->statusLabel,
            'status_value' => $this->statusValue,

            'message' => __('Your application for :job is now :status.', [
                'job' => $this->jobTitle,
                'status' => $this->statusLabel,
            ]),
            'url' => route('dashboard'),
        ];
    }
}
