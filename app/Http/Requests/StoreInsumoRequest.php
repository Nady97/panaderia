<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreInsumoRequest extends FormRequest
{
     public function authorize(): bool
    {
        return Gate::check('insumos.create');
    }

    public function rules(): array
    {
        return [
            'nombre' => 'required|string|max:50',
            'unidad_medida' => 'required|in:kg,gr,lt,ml,unid,bolsa',
            'stock_actual' => 'required|numeric|min:0',
            'stock_minimo' => 'nullable|numeric|min:0',
            'precio_compra_promedio' => 'nullable|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre del insumo es obligatorio.',
            'nombre.max' => 'El nombre no puede exceder los 50 caracteres.',
            'unidad_medida.required' => 'La unidad de medida es obligatoria.',
            'unidad_medida.in' => 'Unidad de medida no válida.',
            'stock_actual.required' => 'El stock actual es obligatorio.',
            'stock_actual.min' => 'El stock no puede ser negativo.',
        ];
    }
}
