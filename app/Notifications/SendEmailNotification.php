<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendEmailNotification extends Notification
{
    use Queueable;
    private $dynamicSubject;
    private $dynamicBody;
    private $dynamicAction;


    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($dynamicSubject,$dynamicBody,$dynamicAction)
    {
        //
        $this->dynamicSubject = $dynamicSubject;
        $this->dynamicBody = $dynamicBody;
        $this->dynamicAction = $dynamicAction;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject($this->dynamicSubject)
            ->greeting('Hello!')
            ->line($this->dynamicBody)
            ->action('Click Here to join', $this->dynamicAction)
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
