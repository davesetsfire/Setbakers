<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PackageDowngradeAdminNotification extends Notification {

    use Queueable;

    private $emailData;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($emailData) {
        $this->emailData = $emailData;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable) {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable) {
        $template = 'emails.package-downgrade-admin-notification';

        return (new MailMessage)
                        ->subject('Plan Downgrade - Infinite to ' . ($this->emailData['package_name'] ?? ''))
                        ->view($template, [
                            'emailData' => $this->emailData
        ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable) {
        return [
                //
        ];
    }

}
