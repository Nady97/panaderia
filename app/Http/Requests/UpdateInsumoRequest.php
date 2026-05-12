<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdateInsumoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::check('insumos.edit');
    }

    public function rules(): array
    {
        return [
            'nombre' => 'sometimes|string|max:50',
            'unidad_medida' => 'sometimes|in:kg,gr,lt,ml,unid,bolsa',
            'stock_actual' => 'sometimes|numeric|min:0',
            'stock_minimo' => 'nullable|numeric|min:0',
            'precio_compra_promedio' => 'nullable|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.max' => 'El nombre no puede exceder los 50 caracteres.',
            'unidad_medida.in' => 'Unidad de medida no válida.',
            'stock_actual.min' => 'El stock no puede ser negativo.',
        ];
    }
}