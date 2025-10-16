<?php

namespace App\Http\Controllers;

use App\Models\Administrativo;
use App\Http\Requests\StoreAdministrativoRequest;
use App\Http\Requests\UpdateAdministrativoRequest;
use Inertia\Inertia;
use App\Services\AdministrativoService;
use Illuminate\Support\Facades\Auth;

class AdministrativoController extends Controller
{

    protected $administrativoService;

    public function __construct(AdministrativoService $administrativoService)
    {
        $this->administrativoService = $administrativoService;
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
     * Show the form for editing the specified resource.
     */
    public function edit(Administrativo $administrativo)
    {
        $administrativo = Auth::user()->administrativo;
        return Inertia::render('administrativo/Edit', [
            'administrativo' => [
                'nombre' => $administrativo->usuario->nombre,
                'apellido' => $administrativo->usuario->apellido,
                'email' => $administrativo->usuario->email,
                'telefono'=> $administrativo->usuario->telefono,
            ]
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAdministrativoRequest $request, Administrativo $administrativo)
    {
        $user = Auth::user();

        $user->update($request->only(['nombre', 'apellido', 'email', 'telefono']));

        return redirect()
            ->route('administrativo.perfil')
            ->with('success', 'Datos actualizados correctamente.');
    }
}
