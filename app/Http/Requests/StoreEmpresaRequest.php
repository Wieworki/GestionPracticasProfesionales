<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEmpresaRequest extends FormRequest
{
    /**
     * TODO: Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre' => ['required', 'string', 'max:255'],
            'cuit' => ['string', 'max:20'],
            'descripcion' => ['required', 'string', 'max:255'],
            'sitioWeb' => ['string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:usuario,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'], // 'confirmed' => needs password_confirmation field
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique' => 'Este correo ya está en uso.',
        ];
    }
}
