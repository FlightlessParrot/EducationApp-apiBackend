<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public string $token)
    {
        //
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
        $url=config('app.frontend_url')."/password-reset/$this->token?email={$notifiable->getEmailForPasswordReset()}";
       
        return (new MailMessage)
        ->greeting('Witaj')
                    ->line('Kliknij w poniższy przycisk, aby zresetować hasło.')
                    ->line('Jeśli nie chesz zmienić hasła zignoruj niniejszą wiadomość.')
                    ->action('Resetuj hasło', $url);
                   
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
