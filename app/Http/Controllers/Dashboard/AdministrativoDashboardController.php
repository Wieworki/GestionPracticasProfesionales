<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Illuminate\Http\Request;

class AdministrativoDashboardController extends Controller
{

    public function index(Request $request)
    {
        $administrativo = $request->user();
        $mensajeBienvenida = 'Desde aquí podrás gestionar tus datos, ver las empresas, estudiantes y ofertas de practicas existentes.';

        return Inertia::render('administrativo/dashboard', [
            'administrativo' => [
                'nombre' => $administrativo->nombre,
                'habilitado' => (bool) $administrativo->habilitado
            ],
            'mensajeBienvenida' => $mensajeBienvenida
        ]);
    }
}
