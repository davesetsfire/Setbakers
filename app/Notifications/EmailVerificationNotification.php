<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Auth\Notifications\VerifyEmail;

class EmailVerificationNotification extends VerifyEmail {

    use Queueable;

    private $name;
    private $accountType;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($name, $accountType) {
        $this->name = $name;
        $this->accountType = $accountType;
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
        $verificationUrl = $this->verificationUrl($notifiable);
        $template = $this->accountType == 'fundus' ? 'emails.email-verification-fundus' : 'emails.email-verification-complete';

        if (static::$toMailCallback) {
            return call_user_func(static::$toMailCallback, $notifiable, $verificationUrl);
        }

        return (new MailMessage)
                        ->subject('Bitte bestätige Deine E-Mail Adresse')
                        ->view($template, [
                            'name' => $this->name,
                            'verificationUrl' => $verificationUrl,
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
