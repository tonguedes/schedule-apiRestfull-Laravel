<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Http\Resources\UserResource;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(protected UserService $service) {}

    // Lista todos os usuários (apenas para admin ou debug)
    public function index()
    {
        $users = $this->service->all();
        return UserResource::collection($users);
    }

    // Retorna os dados do usuário autenticado
    public function show(User $user)
    {
        return new UserResource($user);
    }

    // Atualiza os dados do usuário autenticado
    public function update(UserRequest $request, User $user)
    {
        $updatedUser = $this->service->update($user->id, $request->validated());
        return new UserResource($updatedUser);
    }

    // Deleta um usuário (opcional, se houver permissão)
    public function destroy(User $user)
    {
        // Adicione sua lógica de autorização aqui. Ex:
        // $this->authorize('delete', $user);

        $this->service->delete($user->id);
        return response()->noContent();
    }
}
