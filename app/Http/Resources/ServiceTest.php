<?php

namespace Tests\Feature\Api;

use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ServiceTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        // Cria um usuário para autenticação nos testes
        $this->user = User::factory()->create();
    }

    public function test_guest_cannot_access_services_endpoints(): void
    {
        $this->getJson('/api/services')->assertUnauthorized();
        $this->postJson('/api/services')->assertUnauthorized();
    }

    public function test_can_list_services(): void
    {
        // Cria 3 serviços no banco de dados
        Service::factory()->count(3)->create();

        $response = $this->actingAs($this->user)->getJson('/api/services');

        $response
            ->assertOk()
            ->assertJsonCount(3, 'data') // Verifica se há 3 itens dentro da chave 'data'
            ->assertJsonStructure([     // Verifica a estrutura da resposta
                'data' => [
                    '*' => [ // O '*' significa "cada item no array"
                        'uuid',
                        'name',
                        'description',
                        'price',
                        'duration',
                    ]
                ]
            ]);
    }

    public function test_can_create_a_service(): void
    {
        $data = [
            'name' => 'Novo Serviço',
            'description' => 'Descrição do novo serviço',
            'price' => 99.90,
            'duration' => 60,
        ];

        $response = $this->actingAs($this->user)->postJson('/api/services', $data);

        $response
            ->assertCreated() // Verifica se o status é 201
            ->assertJsonStructure([
                'data' => ['uuid', 'name', 'price']
            ])
            ->assertJsonFragment(['name' => 'Novo Serviço']);

        // Garante que o serviço foi realmente salvo no banco
        $this->assertDatabaseHas('services', [
            'name' => 'Novo Serviço',
            'user_id' => $this->user->id,
        ]);
    }

    public function test_can_show_a_service(): void
    {
        $service = Service::factory()->create();

        $this->actingAs($this->user)
            ->getJson("/api/services/{$service->uuid}")
            ->assertOk()
            ->assertJsonFragment(['uuid' => $service->uuid]);
    }

    public function test_can_delete_a_service(): void
    {
        $service = Service::factory()->create();

        $this->actingAs($this->user)
            ->deleteJson("/api/services/{$service->uuid}")
            ->assertNoContent(); // Verifica se o status é 204

        // Garante que o serviço foi removido do banco
        $this->assertDatabaseMissing('services', ['id' => $service->id]);
    }

    public function test_can_update_a_service(): void
    {
        $service = Service::factory()->create(['user_id' => $this->user->id]);

        $updateData = [
            'name' => 'Serviço Atualizado',
            'price' => 123.45,
        ];

        $this->actingAs($this->user)
            ->putJson("/api/services/{$service->uuid}", $updateData)
            ->assertOk()
            ->assertJsonFragment([
                'name' => 'Serviço Atualizado',
                'price' => 123.45,
            ]);

        $this->assertDatabaseHas('services', [
            'id' => $service->id,
            'name' => 'Serviço Atualizado',
        ]);
    }
}