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
            'email' => ['required', 'email'],
            'dni' => ['required', 'nullable', 'string', 'max:20'],
            'telefono' => ['string', 'max:20'],
        ];
    }
}
