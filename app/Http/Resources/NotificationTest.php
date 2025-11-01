<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Notifications\GenericNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotificationTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_can_list_user_notifications(): void
    {
        // Cria 3 notificações para o usuário
        $this->user->notify(new GenericNotification('Notificação 1'));
        $this->user->notify(new GenericNotification('Notificação 2'));
        $this->user->notify(new GenericNotification('Notificação 3'));

        $this->actingAs($this->user)
            ->getJson('/api/notifications')
            ->assertOk()
            ->assertJsonCount(3, 'data')
            ->assertJsonFragment(['message' => 'Notificação 1']);
    }
}