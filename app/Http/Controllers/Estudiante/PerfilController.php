<?php

namespace App\Http\Controllers\Estudiante;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PerfilController extends Controller
{
    public function index(Request $request)
    {
        $usuario = $request->user();
        $estudiante = $usuario->estudiante;

        return Inertia::render('estudiante/Perfil', [
            'estudiante' => [
                'nombre' => $usuario->nombre,
                'apellido' => $usuario->apellido,
                'email' => $usuario->email,
                'dni' => $estudiante->dni,
                'telefono' => $usuario->telefono,
            ],
        ]);
    }
}
