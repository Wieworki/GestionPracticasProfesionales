<?php

namespace App\Http\Controllers\Administrativo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PerfilController extends Controller
{
    public function index(Request $request)
    {
        $usuario = $request->user();

        return Inertia::render('administrativo/Perfil', [
            'administrativo' => [
                'nombre' => $usuario->nombre,
                'apellido' => $usuario->apellido,
                'email' => $usuario->email,
                'telefono' => $usuario->telefono,
            ],
        ]);
    }
}
