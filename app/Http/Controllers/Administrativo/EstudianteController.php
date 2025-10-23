<?php

namespace App\Http\Controllers\Administrativo;

use App\Http\Controllers\Controller;
use App\Repositories\EstudianteRepository;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EstudianteController extends Controller
{
    protected $estudianteRepository;

    public function __construct(
        EstudianteRepository $estudianteRepository
        )
    {
        $this->estudianteRepository = $estudianteRepository;
    }

    public function index(Request $request)
    {
        $usuario = $request->user();
        $administrativo = $usuario->administrativo;
        
        $filters = [
            'search' => $request->input('search', ''),
        ];

        $estudiantes = $this->estudianteRepository->listar($filters);

        return Inertia::render('administrativo/VerEstudiantes', [
            'estudiantes' => $estudiantes,
            'filters' => $filters,
            'searchRoute' => route('administrativo.estudiantes.index'),
            'administrativo' => [
                'nombre' => $administrativo->nombre,
            ],
        ]);
    }
}
