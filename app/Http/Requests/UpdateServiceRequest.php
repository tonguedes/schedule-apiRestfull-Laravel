<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateServiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Adicionar lógica de autorização se necessário, por exemplo:
        // return $this->service->user_id === auth()->id();
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'duration' => 'sometimes|required|integer|min:1',
            'price' => 'sometimes|required|numeric|min:0',
        ];
    }
}
