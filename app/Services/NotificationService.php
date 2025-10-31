<?php

namespace App\Services;

use App\Repositories\NotificationRepository;
use Carbon\Carbon;

class NotificationService
{
    public function __construct(protected NotificationRepository $repository) {}

    /**
     * Registra uma notificação no banco.
     *
     * @param  array|\Illuminate\Database\Eloquent\Model  $appointmentOrData
     * @param  string  $type
     * @param  string  $message
     * @return \App\Models\Notification
     */
    public function logNotification($appointmentOrData, string $type, string $message)
    {
        $data = [
            'type' => $type,
            'message' => $message,
            'sent_at' => Carbon::now(),
        ];

        // se recebeu um Appointment model, puxar ids
        if (is_object($appointmentOrData) && method_exists($appointmentOrData, 'getAttribute')) {
            $data['appointment_id'] = $appointmentOrData->id ?? null;
            // prefer user from appointment, fallback null
            $data['user_id'] = $appointmentOrData->user_id ?? null;
        } elseif (is_array($appointmentOrData)) {
            $data = array_merge($data, $appointmentOrData);
        }

        return $this->repository->create($data);
    }

    public function listByUser($userId)
    {
        return $this->repository->listByUser($userId);
    }
}
