<?php

namespace App\Http\Controllers;

use App\Models\Estudiante;
use App\Http\Requests\StoreEstudianteRequest;
use App\Http\Requests\UpdateEstudianteRequest;
use Inertia\Inertia;
use App\Services\EstudianteService;

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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEstudianteRequest $request, Estudiante $estudiante)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Estudiante $estudiante)
    {
        //
    }
}
