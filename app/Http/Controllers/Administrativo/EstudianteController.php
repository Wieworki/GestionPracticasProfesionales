<?php

namespace App\Http\Controllers\Administrativo;

use App\Http\Controllers\Controller;
use App\Repositories\EstudianteRepository;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Estudiante;
use Illuminate\Support\Facades\Log;

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

    public function show(Request $request, $id)
    {
        $usuario = $request->user();
        $administrativo = $usuario->administrativo;

        $estudiante = Estudiante::findOrFail($id);

        return Inertia::render('administrativo/ShowEstudiante', [
            'estudiante' => [
                'id' => $estudiante->id,
                'nombre' => $estudiante->nombre,
                'apellido' => $estudiante->apellido,
                'email' => $estudiante->email,
                'dni' => $estudiante->dni,
                'telefono' => $estudiante->telefono,
                'habilitado' => $estudiante->isHabilitado(),
                'fecha_registro' => $estudiante->created_at,
            ],
            'administrativo' => [
                'nombre' => $administrativo->nombre,
            ],
        ]);
    }
}
