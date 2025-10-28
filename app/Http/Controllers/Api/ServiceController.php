<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ServiceRequest;
use App\Repositories\ServiceRepository;

class ServiceController extends Controller
{
    public function __construct(protected ServiceRepository $repository) {}

    public function index()
    {
        return response()->json($this->repository->all());
    }

   public function store(ServiceRequest $request)
    {
    // Mescla os dados validados com o ID do usuário autenticado
    $data = [
        ...$request->validated(),
        'user_id' => $request->user()->id,
    ];

    $service = $this->repository->create($data);

    return response()->json($service, 201);
    }


    public function show($id)
    {
        return response()->json($this->repository->find($id));
    }

    public function update(ServiceRequest $request, $id)
    {
        $service = $this->repository->update($id, $request->validated());
        return response()->json($service);
    }

    public function destroy($id)
    {
        $this->repository->delete($id);
        return response()->json(null, 204);
    }
}
