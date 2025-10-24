<?php

namespace App\Http\Controllers\Administrativo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Repositories\EmpresaRepository;
use Illuminate\Support\Facades\Log;

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
        $administrativo = $usuario->administrativo;

        $empresa = $this->empresaRepository->findById($id);
        if (!$empresa) {
            return redirect()->route('administrativo.dashboard')
                ->with('error', 'No es posible acceder a esta empresa.');
        }

        return Inertia::render('administrativo/ShowEmpresa', [
            'empresa' => [
                'id' => $empresa->id,
                'nombre' => $empresa->nombre,
                'descripcion' => $empresa->descripcion,
                'email_contacto' => $empresa->email,
                'sitio_web' => $empresa->sitio_web,
                'convenio' => $empresa->convenio(),
                'permitir_habilitar_convenio' => !$empresa->habilitado,
                'cuit' => $empresa->cuit,
                'telefono' => $empresa->telefono,
            ],
            'administrativo' => [
                'nombre' => $administrativo->nombre,
            ],
        ]);
    }

    public function confirmarConvenio(Request $request)
    {
        $id = $request->only(['id']);
        $empresa = $this->empresaRepository->findById($id);
        if (!$empresa) {
            return redirect()->route('administrativo.dashboard')
                ->with('error', 'No es posible acceder a esta empresa.');
        }

        $empresa->usuario->update(['habilitado' => true]);

        return redirect()->route('administrativo.empresas.show', $id)
            ->with('success', 'Empresa actualizada correctamente.');
    }
}
