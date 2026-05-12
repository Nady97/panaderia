<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreRecetaRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado a hacer esta solicitud.
     */
    public function authorize(): bool
    {
        return Gate::check('recetas.create');
    }

    /**
     * Reglas de validación para almacenar una receta.
     */
    public function rules(): array
    {
        return [
            'nombre' => 'required|string|max:100',
            'rendimiento_estimado' => 'required|numeric|min:0.01',
            'tiempo_preparacion_min' => 'required|integer|min:1',
            'instrucciones' => 'nullable|string',
            'estado' => 'required|in:activa,borrador,obsoleta',
            'producto_id' => 'required|exists:productos,id',
        ];
    }

    /**
     * Mensajes de error personalizados.
     */
    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre de la receta es obligatorio.',
            'nombre.max' => 'El nombre no puede exceder los 100 caracteres.',
            'rendimiento_estimado.required' => 'El rendimiento estimado es obligatorio.',
            'rendimiento_estimado.min' => 'El rendimiento debe ser mayor a 0.',
            'tiempo_preparacion_min.required' => 'El tiempo de preparación es obligatorio.',
            'tiempo_preparacion_min.min' => 'El tiempo mínimo es 1 minuto.',
            'producto_id.required' => 'Debe seleccionar un producto.',
            'producto_id.exists' => 'El producto seleccionado no existe.',
            'estado.in' => 'El estado debe ser: activa, borrador u obsoleta.',
        ];
    }
}