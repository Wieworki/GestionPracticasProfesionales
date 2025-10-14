<?php

namespace App\Http\Controllers;

use App\Models\Estudiante;
use App\Http\Requests\StoreEstudianteRequest;
use App\Http\Requests\UpdateEstudianteRequest;
use Inertia\Inertia;
use App\Services\EstudianteService;
use Illuminate\Support\Facades\Auth;

class EstudianteController extends Controller
{

    protected $estudianteService;

    public function __construct(EstudianteService $estudianteService)
    {
        $this->estudianteService = $estudianteService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('estudiante/index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('estudiante/create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEstudianteRequest $request)
    {
        $userData = $request->only(['nombre', 'apellido', 'email', 'password']);
        $estudianteData = $request->only(['dni']);
        $estudiante = $this->estudianteService->createEstudianteWithUser($userData, $estudianteData);

        return Inertia::render('estudiante/show', ['estudiante' => $estudiante->id]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Estudiante $estudiante)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Estudiante $estudiante)
    {
        $estudiante = Auth::user()->estudiante;
        return Inertia::render('estudiante/Edit', [
            'estudiante' => [
                'nombre' => $estudiante->usuario->nombre,
                'apellido' => $estudiante->usuario->apellido,
                'email' => $estudiante->usuario->email,
                'dni' => $estudiante->dni,
                'telefono' => $estudiante->usuario->telefono,
            ]
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEstudianteRequest $request, Estudiante $estudiante)
    {
        $user = Auth::user();
        $estudiante = $user->estudiante;

        $user->update($request->only(['nombre', 'apellido', 'email', 'telefono']));
        $estudiante->update($request->only(['dni']));

        return redirect()
            ->route('estudiante.perfil')
            ->with('success', 'Datos actualizados correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Estudiante $estudiante)
    {
        //
    }
}
