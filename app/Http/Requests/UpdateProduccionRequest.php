<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdateProduccionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::check('produccion.edit');
    }

    public function rules(): array
    {
        return [
            'producto_id' => 'sometimes|exists:productos,id',
            'cantidad' => 'sometimes|numeric|min:0.01',
            'fecha_produccion' => 'sometimes|date',
            'estado' => 'sometimes|in:planificado,en_proceso,completado,fallido',
            'observaciones' => 'nullable|string',
        ];
    }
}