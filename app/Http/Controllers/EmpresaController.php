<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Http\Requests\StoreEmpresaRequest;
use App\Http\Requests\UpdateEmpresaRequest;
use Inertia\Inertia;
use Illuminate\Support\Facades\Hash;
use App\Services\EmpresaService;

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

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Empresa $empresa)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEmpresaRequest $request, Empresa $empresa)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Empresa $empresa)
    {
        //
    }
}
