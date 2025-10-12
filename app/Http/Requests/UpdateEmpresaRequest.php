<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class UpdateEmpresaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()->isEmpresa();
    }

    public function rules(): array
    {
        return [
            'nombre' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email'],
            'cuit' => ['nullable', 'string', 'max:20'],
            'descripcion' => ['nullable', 'string', 'max:500'],
            'sitio_web' => ['nullable', 'string', 'max:255'],
        ];
    }
}
