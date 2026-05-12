<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreProveedorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::check('proveedores.create');
    }

    public function rules(): array
    {
        return [
            'codigo' => 'required|string|max:10|unique:proveedores,codigo',
            'nombre_contacto' => 'required|string|max:60',
            'empresa' => 'required|string|max:60',
            'nit' => 'nullable|string|max:20|unique:proveedores,nit',
            'telefono' => 'required|string|max:15',
            'email' => 'nullable|email|max:100',
            'direccion' => 'nullable|string',
            'estado' => 'nullable|in:activo,suspendido,inactivo',
        ];
    }
}