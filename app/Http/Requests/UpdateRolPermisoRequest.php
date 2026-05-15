<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdateRolPermisoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::check('roles.edit');
    }

    public function rules(): array
    {
        return [
            'permisos' => 'nullable|array',
            'permisos.*' => 'integer|exists:permisos,id',
        ];
    }
}