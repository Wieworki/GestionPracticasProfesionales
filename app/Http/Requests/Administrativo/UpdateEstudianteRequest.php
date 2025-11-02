<?php

namespace App\Http\Requests\Administrativo;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use App\Models\Estudiante;

class UpdateEstudianteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()->isAdministrativo();
    }

    public function rules(): array
    {
        $idEstudiante = $this->route('id');
        $estudiante = Estudiante::findOrFail($idEstudiante);
        return [
            'nombre' => ['required', 'string', 'max:255'],
            'apellido' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                Rule::unique('usuario', 'email')->ignore($estudiante->usuario->id, 'id'),
            ],
            'dni' => [
                'required', 
                'string', 
                'max:20',
                Rule::unique('estudiante', 'dni')->ignore($estudiante->id, 'id'),
            ],
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
            'dni.unique' => 'Este DNI ya esta registrado.',
            'email.unique' => 'Este correo ya está en uso.'
        ];
    }
}
