<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Http\Requests\StoreEmpresaRequest;
use App\Http\Requests\UpdateEmpresaRequest;
use Inertia\Inertia;
use App\Services\EmpresaService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class EmpresaController extends Controller
{

    protected $empresaService;

    public function __construct(EmpresaService $empresaService)
    {
        $this->empresaService = $empresaService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('empresa/index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('empresa/create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEmpresaRequest $request)
    {
        $userData = $request->only(['nombre', 'email', 'password']);
        $empresaData = $request->only(['cuit', 'descripcion', 'sitioweb']);
        $empresa = $this->empresaService->createEmpresaWithUser($userData, $empresaData);

        return Inertia::render('empresa/show', ['empresa' => $empresa->id]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Empresa $empresa)
    {
        return Inertia::render('empresa/show', ['empresa' => $empresa->id]);
    }

    public function edit()
    {
        $empresa = Auth::user()->empresa;
        return Inertia::render('empresa/Edit', [
            'empresa' => [
                'nombre' => $empresa->usuario->nombre,
                'email' => $empresa->usuario->email,
                'cuit' => $empresa->cuit,
                'descripcion' => $empresa->descripcion,
                'sitio_web' => $empresa->sitio_web,
                'telefono'=> $empresa->usuario->telefono,
            ]
        ]);
    }

    public function update(UpdateEmpresaRequest $request): RedirectResponse
    {
        $user = Auth::user();
        $empresa = $user->empresa;

        $user->update($request->only(['nombre', 'email', 'telefono']));
        $empresa->update($request->only(['cuit', 'descripcion', 'sitio_web']));

        return redirect()
            ->route('empresa.perfil')
            ->with('success', 'Datos actualizados correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Empresa $empresa)
    {
        //
    }
}
