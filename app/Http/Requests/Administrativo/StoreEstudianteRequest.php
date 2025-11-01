<?php

namespace App\Http\Requests\Administrativo;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreEstudianteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()->isAdministrativo();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nombre' => ['required', 'string', 'max:255'],
            'apellido' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:usuario,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'], // 'confirmed' => needs password_confirmation field
            'dni' => ['required', 'string', 'max:20', 'unique:estudiante,dni'],
            'telefono' => ['nullable', 'string', 'max:20'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique' => 'Este correo ya está en uso.',
            'dni.unique' => 'Este DNI ya está registrado.',
        ];
    }
}
