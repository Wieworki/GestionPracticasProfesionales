<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Illuminate\Http\Request;

class EmpresaDashboardController extends Controller
{

    public function index(Request $request)
    {
        $empresa = $request->user();
        $mensajeBienvenida = 'Desde aquí podrás gestionar tus datos y crear nuevas ofertas de prácticas profesionales.';

        return Inertia::render('empresa/dashboard', [
            'empresa' => [
                'nombre' => $empresa->nombre,
                'habilitado' => (bool) true
            ],
            'mensajeBienvenida' => $mensajeBienvenida
        ]);
    }
}
