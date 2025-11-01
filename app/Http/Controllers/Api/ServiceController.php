<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ServiceRequest;
use App\Http\Resources\ServiceResource;
use App\Repositories\ServiceRepository;
use App\Models\Service;

class ServiceController extends Controller
{
    public function __construct(protected ServiceRepository $repository) {}

    public function index()
    {
        return ServiceResource::collection($this->repository->all());
    }

   public function store(ServiceRequest $request)
    {
    // Mescla os dados validados com o ID do usuário autenticado
    $data = [
        ...$request->validated(),
        'user_id' => $request->user()->id,
    ];

    $service = $this->repository->create($data);

    return new ServiceResource($service);
    }


    public function show(Service $service)
    {
        return new ServiceResource($service);
    }

    public function update(ServiceRequest $request, Service $service)
    {
        // Adicione a lógica de autorização aqui, se necessário.
        // $this->authorize('update', $service);
        $updatedService = $this->repository->update($service->id, $request->validated());
        return new ServiceResource($updatedService);
    }

    public function destroy(Service $service)
    {
        $this->repository->delete($service->id);
        return response()->noContent();
    }
}
