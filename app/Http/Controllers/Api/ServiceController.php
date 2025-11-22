<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ServiceResource;
use App\Http\Requests\UpdateServiceRequest;
use App\Http\Requests\StoreServiceRequest;
use App\Models\Service;
use Illuminate\Http\JsonResponse;

class ServiceController extends Controller
{
    /**
     * Lista todos os serviços.
     */
    public function index(): JsonResponse
    {
        // Retorna os serviços do usuário autenticado
        $services = auth()->user()->services()->get();
        return response()->json(['data' => ServiceResource::collection($services)]);
    }

    /**
     * Armazena um novo serviço.
     */
    public function store(StoreServiceRequest $request): ServiceResource
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();

        $service = Service::create($data);

        return new ServiceResource($service);
    }

    /**
     * Mostra um serviço específico.
     */
    public function show(Service $service): ServiceResource
    {
        // Idealmente, você adicionaria uma verificação para garantir que o usuário pode ver este serviço
        return new ServiceResource($service);
    }

    /**
     * Atualiza um serviço específico.
     */
    public function update(UpdateServiceRequest $request, Service $service): ServiceResource
    {
        // Idealmente, você adicionaria uma verificação para garantir que o usuário pode atualizar este serviço
        $service->update($request->validated());

        return new ServiceResource($service);
    }

    /**
     * Deleta um serviço específico.
     */
    public function destroy(Service $service): JsonResponse
    {
        // Idealmente, você adicionaria uma verificação para garantir que o usuário pode deletar este serviço
        $service->delete();

        return response()->json(null, 204);
    }
}
