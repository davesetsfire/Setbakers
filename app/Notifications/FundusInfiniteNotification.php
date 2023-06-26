<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FundusInfiniteNotification extends Notification {

    use Queueable;

    private $name;
    private $emailData;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($name, $emailData) {
        $this->name = $name;
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
        $template = 'emails.fundus-infinite-self';

        return (new MailMessage)
                        ->subject('New Fundus Infinite account upgrade request')
                        ->view($template, [
                            'name' => $this->name,
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
