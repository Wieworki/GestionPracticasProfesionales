<?php

namespace App\Http\Requests\Empresa;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOfertaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // El control de permisos lo hacemos en el controlador
    }

    public function rules(): array
    {
        return [
            'titulo' => ['required', 'string', 'max:255'],
            'modalidad' => ['required', 'string', 'max:50'],
            'fecha_cierre' => ['required', 'date', 'after_or_equal:today'],
            'descripcion' => ['nullable', 'string'],
            'modalidad' => ['required', 'string', 'max:255'],
        ];
    }
}
