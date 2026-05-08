<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateCategoriaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        $categoriaId = $this->route('categoria')->id ?? $this->route('categoria');

        return [
            'nombre' => 'required|string|max:255|unique:categorias,nombre,' . $categoriaId,
            'slug' => 'nullable|string|max:255',
            'descripcion' => 'nullable|string',
            'activo' => 'boolean',
        ];
    }
}
