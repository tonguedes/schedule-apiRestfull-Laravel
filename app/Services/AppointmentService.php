<?php

namespace App\Services;

use App\Repositories\AppointmentRepository;
use App\Enums\AppointmentStatus;
use App\Jobs\SendNotificationJob;

class AppointmentService
{
    public function __construct(protected AppointmentRepository $repository) {}

    public function listByUser($userId)
    {
        return $this->repository->findByUser($userId);
    }

    public function create(array $data)
    {
        $appointment = $this->repository->create([
            ...$data,
            'status' => AppointmentStatus::Pending,
        ]);

        // 🔹 Aqui é onde o erro estava — deve passar $appointment, não $user
        dispatch(new SendNotificationJob($appointment, 'pending'));

        return $appointment;
    }

    public function confirm($id)
    {
        $appointment = $this->repository->update($id, [
            'status' => AppointmentStatus::Confirmed,
        ]);

        dispatch(new SendNotificationJob($appointment, 'confirmed'));

        return $appointment;
    }

    public function cancel($id)
    {
        $appointment = $this->repository->update($id, [
            'status' => AppointmentStatus::Cancelled,
        ]);

        dispatch(new SendNotificationJob($appointment, 'cancelled'));

        return $appointment;
    }

    public function delete($id)
    {
        
        return $this->repository->delete($id);
    }

    public function find($id)
    {
        return $this->repository->find($id);
    }
}
