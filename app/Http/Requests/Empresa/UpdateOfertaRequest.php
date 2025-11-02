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

    public function messages(): array
    {
        return [
            'titulo.required' => 'Debe inidicar un titulo.',
            'descripcion.required' => 'La oferta debe tener una descripcion.',
            'fecha_cierre.required' => 'Debe indicar una fecha de cierre.',
            'fecha_cierre.after_or_equal' => 'La fecha de cierre no puede ser anterior al dia de la fecha.',
            'modalidad.required' => 'Debe seleccionar una modalidad de trabajo.',
        ];
    }
}
