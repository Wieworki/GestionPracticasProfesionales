<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEstudianteRequest;
use App\Http\Requests\StoreEmpresaRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;
use App\Services\EstudianteService;
use App\Services\EmpresaService;
use Illuminate\Support\Facades\Log;

class RegisteredUserController extends Controller
{

    protected $estudianteService;
    protected $empresaService;

    public function __construct(
        EstudianteService $estudianteService, 
        EmpresaService $empresaService
        )
    {
        $this->estudianteService = $estudianteService;
        $this->empresaService = $empresaService;
    }

    /**
     * Show the registration page for guests
     */
    public function create(): Response
    {
        return Inertia::render('auth/registro/index');
    }

    /**
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function storeEstudiante(StoreEstudianteRequest $request): RedirectResponse
    {
        $userData = $request->only(['nombre', 'apellido', 'email', 'telefono', 'password']);
        $estudianteData = $request->only(['dni']);
        $estudiante = $this->estudianteService->createEstudianteWithUser($userData, $estudianteData);
        
        Auth::login($estudiante->usuario);

        return redirect()
            ->route('dashboard')
            ->with('success', 'Cuenta creada con éxito');
    }

    /**
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function storeEmpresa(StoreEmpresaRequest $request): RedirectResponse
    {
        $userData = $request->only(['nombre', 'email', 'password', 'telefono']);
        $empresaData = $request->only(['cuit', 'descripcion', 'sitio_web']);
        $empresa = $this->empresaService->createEmpresaWithUser($userData, $empresaData);
        
        Auth::login($empresa->usuario);

        return redirect()
            ->route('dashboard')
            ->with('success', 'Cuenta creada con éxito');
    }
}
