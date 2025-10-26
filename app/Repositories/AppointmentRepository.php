<?php

namespace App\Repositories;

use App\Models\Appointment;

class AppointmentRepository extends BaseRepository
{
    public function __construct(Appointment $appointment)
    {
        $this->model = $appointment;
    }

    public function findByUser($userId)
    {
        return $this->model->where('user_id', $userId)->get();
    }
}
