<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PerfilController extends Controller
{
    public function index(Request $request)
    {
        $usuario = $request->user();
        $empresa = $usuario->empresa;

        return Inertia::render('empresa/Perfil', [
            'empresa' => [
                'nombre' => $usuario->nombre,
                'email' => $usuario->email,
                'cuit' => $empresa->cuit,
                'descripcion' => $empresa->descripcion,
                'sitio_web' => $empresa->sitio_web,
            ],
        ]);
    }
}
