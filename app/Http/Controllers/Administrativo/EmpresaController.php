<?php

namespace App\Http\Controllers\Administrativo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Repositories\EmpresaRepository;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\Administrativo\StoreEmpresaRequest;
use App\Services\EmpresaService;

class EmpresaController extends Controller
{
    protected $empresaService;
    protected $empresaRepository;

    public function __construct(
        EmpresaRepository $empresaRepository,
        EmpresaService $empresaService
        )
    {
        $this->empresaRepository = $empresaRepository;
        $this->empresaService = $empresaService;
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

        return Inertia::render('administrativo/empresa/ShowEmpresa', [
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

    public function index(Request $request)
    {
        $search = request('search');
        $empresas = $this->empresaRepository->getAll($search);
        $usuario = $request->user();

        return Inertia::render('administrativo/empresa/VerEmpresas', [
            'administrativo' => [
                'nombre' => $usuario->nombre,
                'habilitado' => $usuario->habilitado,
            ],
            'empresas' => $empresas,
            'filters' => request()->only('search'),
            'showNewButton' => true,
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

    public function create(Request $request)
    {
        $usuario = $request->user();
        $administrativo = $usuario->administrativo;
        return Inertia::render('administrativo/empresa/CrearEmpresa', [
            'administrativo' => [
                'nombre' => $administrativo->nombre,
            ],
        ]);
    }

    public function store(StoreEmpresaRequest $request)
    {
        $userData = $request->only(['nombre', 'email', 'password', 'telefono']);
        $empresaData = $request->only(['cuit', 'descripcion', 'sitio_web']);
        $empresa = $this->empresaService->createEmpresaWithUser($userData, $empresaData);

        return redirect()->route('administrativo.empresas.index')
                         ->with('success', 'Empresa registrada correctamente.');
    }
}
