<?php

namespace App\Jobs;

use App\Models\Appointment;
use App\Services\NotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
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
        $service = $this->appointment->service->name ?? 'Serviço não informado';

        $message = match ($this->event) {
            'pending' => "Novo agendamento criado por {$user} para {$service}",
            'confirmed' => "Agendamento de {$user} para {$service} foi confirmado",
            'cancelled' => "Agendamento de {$user} para {$service} foi cancelado",
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
    }
}
