<?php

namespace App\Jobs;

use App\Notifications\GenericNotification;
use App\Models\Appointment;
use App\Services\NotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Log;

class SendNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Appointment $appointment;
    protected string $event;

    public function __construct(Appointment $appointment, string $event)
    {
        $this->appointment = $appointment;
        $this->event = $event;
    }

    public function handle(NotificationService $notificationService): void
    {
        $user = $this->appointment->user->name ?? 'Usuário desconhecido';
        $appointmentUser = $this->appointment->user;
        $service = $this->appointment->service->name ?? 'Serviço não informado';

        $message = match ($this->event) {
            'pending' => "Novo agendamento criado por {$user} para {$service}",
            'confirmed' => "Agendamento de {$user} para {$service} foi confirmado",
            'cancelled' => "Agendamento de {$user} para {$service} foi cancelado",
            'scheduled' => "Seu agendamento para o serviço {$service} foi realizado com sucesso.",
            default => "Evento {$this->event} para agendamento {$this->appointment->id}",
        };

        // log no arquivo
        Log::info("[NOTIFICATION] {$message}");

        // gravar no banco via NotificationService
        try {
            $notificationService->logNotification($this->appointment, $this->event, $message);
        } catch (\Throwable $e) {
            Log::error('[NOTIFICATION][ERROR] ' . $e->getMessage());
        }

        // Envia a notificação por e-mail para o usuário
        if ($appointmentUser) {
            $subject = "Agendamento " . ucfirst($this->event);
            $appointmentUser->notify(new GenericNotification($message, $subject));
        }
    }
}
