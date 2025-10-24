<?php

namespace App\Http\Controllers\Estudiante;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Repositories\EmpresaRepository;

class EmpresaController extends Controller
{
    protected $empresaRepository;

    public function __construct(
        EmpresaRepository $empresaRepository
        )
    {
        $this->empresaRepository = $empresaRepository;
    }

    public function show(Request $request, $id)
    {
        $usuario = $request->user();
        $estudiante = $usuario->estudiante;

        $empresa = $this->empresaRepository->findHabilitadaById($id);
        if (!$empresa) {
            return redirect()->route('estudiante.dashboard')
                ->with('error', 'No es posible acceder a esta empresa.');
        }

        return Inertia::render('estudiante/ShowEmpresa', [
            'empresa' => [
                'id' => $empresa->id,
                'nombre' => $empresa->nombre,
                'descripcion' => $empresa->descripcion,
                'email_contacto' => $empresa->email,
                'sitio_web' => $empresa->sitio_web,
            ],
            'estudiante' => [
                'nombre' => $estudiante->nombre,
            ],
        ]);
    }
}
