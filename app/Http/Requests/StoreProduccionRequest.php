<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreProduccionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::check('produccion.create');
    }

    public function rules(): array
    {
        return [
            'producto_id' => 'required|exists:productos,id',
            'cantidad' => 'required|numeric|min:0.01',
            'fecha_produccion' => 'required|date',
            'estado' => 'required|in:planificado,en_proceso,completado,fallido',
            'observaciones' => 'nullable|string',
        ];
    }
}