<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
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
    public function show(Request $request)
    {
        return new UserResource($request->user());
    }

    // Atualiza os dados do usuário autenticado
    public function update(UserRequest $request)
    {
        $user = $this->service->update($request->user()->id, $request->validated());
        return new UserResource($user);
    }

    // Deleta um usuário (opcional, se houver permissão)
    public function destroy($id)
    {
        $userToDelete = $this->service->find($id);

        // Adicione sua lógica de autorização aqui. Ex:
        // $this->authorize('delete', $userToDelete);

        $this->service->delete($userToDelete->id);
        return response()->noContent();
    }
}
