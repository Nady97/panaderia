<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdateProveedorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::check('proveedores.edit');
    }

    public function rules(): array
    {
        return [
            'nombre_contacto' => 'sometimes|string|max:60',
            'empresa' => 'sometimes|string|max:60',
            'nit' => 'sometimes|nullable|string|max:20|unique:proveedores,nit,' . $this->route('proveedor') . ',codigo',
            'telefono' => 'sometimes|string|max:15',
            'email' => 'nullable|email|max:100',
            'direccion' => 'nullable|string',
            'estado' => 'sometimes|in:activo,suspendido,inactivo',
        ];
    }
}