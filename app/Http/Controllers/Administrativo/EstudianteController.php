<?php

namespace App\Http\Controllers\Administrativo;

use App\Http\Controllers\Controller;
use App\Repositories\EstudianteRepository;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Estudiante;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\Administrativo\UpdateEstudianteRequest;
use App\Http\Requests\Administrativo\StoreEstudianteRequest;
use App\Services\EstudianteService;

class EstudianteController extends Controller
{
    protected $estudianteRepository;
    protected $estudianteService;

    public function __construct(
        EstudianteRepository $estudianteRepository,
        EstudianteService $estudianteService, 
        )
    {
        $this->estudianteRepository = $estudianteRepository;
        $this->estudianteService = $estudianteService;
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

    public function edit(Request $request, $id)
    {
        $usuario = $request->user();
        $administrativo = $usuario->administrativo;

        $estudiante = Estudiante::findOrFail($id);

        return Inertia::render('administrativo/EditEstudiante', [
            'estudiante' => [
                'id' => $estudiante->id,
                'nombre' => $estudiante->nombre,
                'apellido' => $estudiante->apellido,
                'email' => $estudiante->email,
                'dni' => $estudiante->dni,
                'telefono' => $estudiante->telefono,
                'habilitado' => $estudiante->isHabilitado(),
            ],
            'administrativo' => [
                'nombre' => $usuario->nombre,
            ],
        ]);
    }

    public function update(UpdateEstudianteRequest $request, $id)
    {
        $estudiante = Estudiante::findOrFail($id);

        $estudiante->usuario->update($request->only(['nombre', 'apellido', 'email', 'telefono', 'habilitado']));
        $estudiante->update($request->only(['dni']));

        return redirect()->route('administrativo.estudiantes.show', $estudiante->id)
                         ->with('success', 'Datos del estudiante actualizados correctamente.');
    }

    public function create(Request $request)
    {
        $usuario = $request->user();
        $administrativo = $usuario->administrativo;

        return Inertia::render('administrativo/CrearEstudiante', [
            'administrativo' => [
                'nombre' => $administrativo->nombre,
            ],
        ]);
    }


    public function store(StoreEstudianteRequest $request)
    {
        // Creamos el usuario, como es creado por un usuario administrativo, se encuentra habilitado por defecto
        $userData = $request->only(['nombre', 'apellido', 'email', 'telefono', 'password']);
        $estudianteData = $request->only(['dni']);
        $estudiante = $this->estudianteService->createEstudianteWithUser($userData, $estudianteData);
        $estudiante->usuario->update(['habilitado' => true]);

        return redirect()->route('administrativo.estudiantes.index')
            ->with('success', 'Estudiante creado correctamente.');
    }
}
