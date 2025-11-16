<?php

namespace Tests\Feature\Api;

use App\Models\Appointment;
use App\Models\Service;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AppointmentTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_can_list_user_appointments(): void
    {
        Appointment::factory()->count(3)->create(['user_id' => $this->user->id]);
        // Cria um agendamento para outro usuário que não deve aparecer na lista
        Appointment::factory()->create();

        $this->actingAs($this->user)
            ->getJson('/api/appointments')
            ->assertOk()
            ->assertJsonCount(3, 'data');
    }

    public function test_can_create_an_appointment(): void
    {
        $service = Service::factory()->create();
        $appointmentDate = Carbon::now()->addDay()->setHour(14)->setMinutes(0)->setSeconds(0);

        $data = [
            'service_id' => $service->id,
            'date' => $appointmentDate->format('Y-m-d'),
            'time' => $appointmentDate->format('H:i'),
        ];

        $this->actingAs($this->user)
            ->postJson('/api/appointments', $data)
            ->assertCreated()
            ->assertJsonFragment([
                'status' => 'scheduled',
            ]);

        $this->assertDatabaseHas('appointments', [
            'user_id' => $this->user->id,
            'service_id' => $service->id,
        ]);
    }

    public function test_can_cancel_an_appointment(): void
    {
        $appointment = Appointment::factory()->create([
            'user_id' => $this->user->id,
            'status' => 'scheduled',
        ]);

        $this->actingAs($this->user)
            ->patchJson("/api/appointments/{$appointment->uuid}/cancel")
            ->assertOk()
            ->assertJsonPath('data.status', 'canceled');

        $this->assertDatabaseHas('appointments', [
            'id' => $appointment->id,
            'status' => 'canceled',
        ]);
    }

    // Você pode adicionar testes para 'confirm', 'show', 'destroy' seguindo o mesmo padrão.
}