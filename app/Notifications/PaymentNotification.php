<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentNotification extends Notification {

    use Queueable;

    private $name;
    private $paymentMethod;
    private $emailData;
    private $attachment;
    private $attachmentName;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($name, $paymentMethod, $emailData = [], $attachment = null, $attachmentName = 'File') {
        $this->name = $name;
        $this->paymentMethod = $paymentMethod;
        $this->emailData = $emailData;
        $this->attachment = $attachment;
        $this->attachmentName = $attachmentName;
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
        $template = $this->paymentMethod == 'paypal' ? 'emails.pay-with-paypal' : 'emails.pay-with-bank';
        //$subject = $this->paymentMethod == 'paypal' ? 'Rechnung ' . ($this->emailData['invoice_number'] ?? '') . ' zu Deinem Projektkonto' : 'Rechnung zu Deinem Projektkonto';
        $subject = $this->paymentMethod == 'paypal' ? 'Rechnung zu Deinem Projektkonto' : 'Rechnung zu Deinem Projektkonto';

        return (new MailMessage)
                        ->subject($subject)
                        ->view($template, [
                            'name' => $this->name,
                            'emailData' => $this->emailData
                        ])
                        ->attachData($this->attachment, $this->attachmentName);
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
