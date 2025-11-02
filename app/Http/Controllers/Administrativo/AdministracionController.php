<?php

namespace App\Http\Controllers\Administrativo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdministracionController extends Controller
{
    public function index(Request $request)
    {
        $usuario = $request->user();

        return Inertia::render('administrativo/administracion/index', [
            'administrativo' => [
                'nombre' => $usuario->nombre,
                'habilitado' => $usuario->habilitado,
            ],
        ]);
    }
}
