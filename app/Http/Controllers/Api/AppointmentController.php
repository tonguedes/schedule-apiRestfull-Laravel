<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AppointmentRequest;
use App\Http\Resources\AppointmentResource;
use App\Models\Service;
use App\Services\AppointmentService;
use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function __construct(protected AppointmentService $service) {}

    public function index(Request $request)
    {
        $appointments = $this->service->listByUser($request->user()->id);
        return AppointmentResource::collection($appointments);
    }

    public function store(AppointmentRequest $request)
    {
        $validatedData = $request->validated();

        // Combina a data e a hora em um único objeto Carbon (timestamp)
        $appointmentTimestamp = Carbon::parse($validatedData['date'] . ' ' . $validatedData['time']);

        // Encontra o serviço pelo UUID e pega seu ID para a chave estrangeira
        $service = Service::where('uuid', $validatedData['service_uuid'])->firstOrFail();

        $appointment = $this->service->create([
            'user_id' => $request->user()->id,
            'service_id' => $service->id,
            'appointment_time' => $appointmentTimestamp,
        ]);

        return new AppointmentResource($appointment);
    }

    public function confirm(Appointment $appointment)
    {
        $confirmedAppointment = $this->service->confirm($appointment->id);
        return new AppointmentResource($confirmedAppointment);
    }

    public function cancel(Appointment $appointment)
    {
        $canceledAppointment = $this->service->cancel($appointment->id);
        return new AppointmentResource($canceledAppointment);
    }


    public function show(Appointment $appointment)
    {
        // Opcional: carregar relacionamentos para exibir no resource
        $appointment->load(['user', 'service']);
        return new AppointmentResource($appointment);
    }

    public function destroy(Appointment $appointment)
    {
        $this->service->delete($appointment->id);
        return response()->noContent();
    }
}
