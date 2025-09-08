<?php

namespace App\Repositories;

use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;

class UsuarioRepository
{
    public function create(array $data): Usuario
    {
        $data['password'] = Hash::make($data['password']);
        return Usuario::create($data);
    }

    public function findByEmail(string $email): ?Usuario
    {
        return Usuario::where('email', $email)->first();
    }
}