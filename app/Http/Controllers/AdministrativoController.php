<?php

namespace App\Http\Controllers;

use App\Models\Administrativo;
use App\Http\Requests\StoreAdministrativoRequest;
use App\Http\Requests\UpdateAdministrativoRequest;
use Inertia\Inertia;
use App\Services\AdministrativoService;

class AdministrativoController extends Controller
{

    protected $administrativoService;

    public function __construct(AdministrativoService $administrativoService)
    {
        $this->administrativoService = $administrativoService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('administrativo/index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
       return Inertia::render('administrativo/create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAdministrativoRequest $request)
    {
        $userData = $request->only(['nombre', 'apellido', 'email', 'password']);
        $administrativo = $this->administrativoService->createAdministrativoWithUser($userData, []);

        return Inertia::render('administrativo/show', ['administrativo' => $administrativo->id]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Administrativo $administrativo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Administrativo $administrativo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAdministrativoRequest $request, Administrativo $administrativo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Administrativo $administrativo)
    {
        //
    }
}
