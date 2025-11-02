<?php

namespace App\Http\Controllers;

use App\Models\Administrativo;
use App\Http\Requests\StoreAdministrativoRequest;
use App\Http\Requests\UpdateAdministrativoRequest;
use Inertia\Inertia;
use App\Services\AdministrativoService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AdministrativoController extends Controller
{

    protected $administrativoService;

    public function __construct(AdministrativoService $administrativoService)
    {
        $this->administrativoService = $administrativoService;
    }

    public function create(Request $request)
    {
        $usuario = $request->user();
        $administrativo = $usuario->administrativo;
        return Inertia::render('administrativo/administracion/CrearAdministrativo', [
            'administrativo' => [
                'nombre' => $administrativo->nombre,
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAdministrativoRequest $request)
    {
        $userData = $request->only(['nombre', 'apellido', 'telefono', 'email', 'password']);
        $administrativo = $this->administrativoService->createAdministrativoWithUser($userData, []);
        $administrativo->usuario->update(['habilitado' => true]);

        return redirect()->route('administrativo.administracion.index')
                         ->with('success', 'Usuario creado correctamente.');
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
