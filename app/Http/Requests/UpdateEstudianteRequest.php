<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateEstudianteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()->isEstudiante();
    }

    public function rules(): array
    {
        return [
            'nombre' => ['required', 'string', 'max:255'],
            'apellido' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email'],
            'dni' => ['required', 'string', 'max:20'],
            'telefono' => ['nullable', 'string', 'max:20'],
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'Debe ingresar su nombre.',
            'apellido.required' => 'Debe ingresar su apellido.',
            'email.required' => 'Debe ingresar su email.',
            'dni.required' => 'Debe ingresar su DNI.',
        ];
    }
}
