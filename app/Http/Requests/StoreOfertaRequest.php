<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOfertaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'titulo' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'fecha_cierre' => 'required|date|after_or_equal:today',
            'modalidad' => 'required|string|max:100',
        ];
    }

    public function messages(): array
    {
        return [
            'titulo.required' => 'Debe inidicar un titulo.',
            'descripcion.required' => 'La oferta debe tener una descripcion.',
            'fecha_cierre.required' => 'Debe indicar una fecha de cierre.',
            'modalidad.required' => 'Debe seleccionar una modalidad de trabajo.',
        ];
    }
}
