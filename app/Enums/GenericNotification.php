<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class GenericNotification extends Notification
{
    use Queueable;

    protected string $message;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $message)
    {
        $this->message = $message;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        // Define que esta notificação será enviada para o canal 'database'.
        // Outras opções poderiam ser 'mail', 'slack', etc.
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * Este é o formato que será salvo na coluna 'data' da tabela 'notifications'.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'message' => $this->message,
            // Você pode adicionar outros dados úteis aqui
            // 'appointment_id' => $this->appointment->id,
            // 'link' => '/appointments/' . $this->appointment->id,
        ];
    }
}