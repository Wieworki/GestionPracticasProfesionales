<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Http\Requests\StoreEmpresaRequest;
use App\Http\Requests\UpdateEmpresaRequest;
use Inertia\Inertia;
use App\Services\EmpresaService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use App\Repositories\EmpresaRepository;

class EmpresaController extends Controller
{

    protected $empresaService;
    protected $empresaRepository;

    public function __construct(
        EmpresaService $empresaService,
        EmpresaRepository $empresaRepository
        )
    {
        $this->empresaService = $empresaService;
        $this->empresaRepository = $empresaRepository;
    }

    public function indexEstudiante()
    {
        $empresas = $this->empresaRepository->getHabilitadas(); // solo habilitadas
        $usuario = Auth::user();

        return Inertia::render('estudiante/VerEmpresas', [
            'estudiante' => [
                'nombre' => $usuario->nombre,
                'habilitado' => $usuario->habilitado,
            ],
            'empresas' => $empresas,
            'showNewButton' => false,
        ]);
    }

    public function indexAdministrativo()
    {
        $empresas = $this->empresaRepository->getAll(); // todas
        $usuario = Auth::user();

        return Inertia::render('administrativo/VerEmpresas', [
            'administrativo' => [
                'nombre' => $usuario->nombre,
                'habilitado' => $usuario->habilitado,
            ],
            'empresas' => $empresas,
            'showNewButton' => true,
        ]);
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

}
