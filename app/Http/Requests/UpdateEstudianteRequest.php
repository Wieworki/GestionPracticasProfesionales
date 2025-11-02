<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Models\Usuario;

class UpdateEstudianteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()->isEstudiante();
    }

    public function rules(): array
    {
        $usuario = Usuario::findOrFail(auth()->id());
        return [
            'nombre' => ['required', 'string', 'max:255'],
            'apellido' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                Rule::unique('usuario', 'email')->ignore(auth()->id(), 'id'),
            ],
            'dni' => [
                'required', 
                'string', 
                'max:20',
                Rule::unique('estudiante', 'dni')->ignore($usuario->estudiante->id, 'id'),
            ],
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
            'dni.unique' => 'Este DNI ya esta registrado.',
            'email.unique' => 'Este correo ya está en uso.'
        ];
    }
}
