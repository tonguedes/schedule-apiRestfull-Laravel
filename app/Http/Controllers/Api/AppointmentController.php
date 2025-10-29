<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AppointmentRequest;
use App\Services\AppointmentService;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function __construct(protected AppointmentService $service) {}

    public function index(Request $request)
    {
        return response()->json($this->service->listByUser($request->user()->id));
    }

    public function store(AppointmentRequest $request)
    {
        $data = [
            'user_id' => $request->user()->id,
            'service_id' => $request->service_id,
            'date' => $request->date,
            'time' => $request->time,
        ];

        $appointment = $this->service->create($data);
        return response()->json($appointment, 201);
    }

    public function confirm($id)
    {
        return response()->json($this->service->confirm($id));
    }

    public function cancel($id)
    {
        return response()->json($this->service->cancel($id));
    }


public function show($id)
{
   
    $appointment = $this->service->find($id);

    // Retorna 404 se o agendamento não for encontrado
    if (!$appointment) {
        return response()->json(['message' => 'Appointment not found'], 404);
    }

    return response()->json($appointment);
}

    public function destroy($id)
    {
        $this->service->delete($id);
        return response()->json(['message' => 'Appointment deleted successfully'], 200);
    }
}
