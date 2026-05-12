<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdateRecetaRequest extends FormRequest
{public function rules(): array
{
    return [
        'nombre' => 'sometimes|string|max:100',
        'rendimiento_estimado' => 'sometimes|numeric|min:0.01',
        'tiempo_preparacion_min' => 'sometimes|integer|min:1',
        'instrucciones' => 'nullable|string',
        'estado' => 'sometimes|in:activa,borrador,obsoleta',
        'producto_id' => 'sometimes|exists:productos,id',
    ];
}

public function authorize(): bool
{
    return Gate::check('recetas.edit');
}
}
