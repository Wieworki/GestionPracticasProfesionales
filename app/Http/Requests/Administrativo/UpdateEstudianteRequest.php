<?php

namespace App\Http\Requests\Administrativo;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateEstudianteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()->isAdministrativo();
    }

    public function rules(): array
    {
        return [
            'nombre' => ['required', 'string', 'max:255'],
            'apellido' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email'],
            'dni' => ['required', 'string', 'max:20'],
            'telefono' => ['nullable', 'string', 'max:20'],
            'habilitado' => ['required', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'Debe ingresar un nombre.',
            'apellido.required' => 'Debe ingresar un apellido.',
            'email.required' => 'Debe ingresar un email.',
            'dni.required' => 'Debe ingresar un DNI.',
        ];
    }
}
