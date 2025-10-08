<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Illuminate\Http\Request;

class EstudianteDashboardController extends Controller
{

    public function index(Request $request)
    {
        $estudiante = $request->user();
        $mensajeBienvenida = 'Desde aquí podrás gestionar tus datos y ver las ofertas de prácticas profesionales.';

        return Inertia::render('estudiante/dashboard', [
            'estudiante' => [
                'nombre' => $estudiante->nombre,
                'habilitado' => (bool) $estudiante->habilitado
            ],
            'mensajeBienvenida' => $mensajeBienvenida
        ]);
    }
}
