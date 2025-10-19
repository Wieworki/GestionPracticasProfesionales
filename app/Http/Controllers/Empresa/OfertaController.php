<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use App\Services\OfertaService;
use App\Repositories\OfertaRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Http\Requests\StoreOfertaRequest;
use App\Models\Carrera;

class OfertaController extends Controller
{

    protected OfertaService $ofertaService;
    protected OfertaRepository $ofertaRepository;

    public function __construct(OfertaService $ofertaService, OfertaRepository $ofertaRepository)
    {
        $this->ofertaService = $ofertaService;
        $this->ofertaRepository = $ofertaRepository;
    }

    public function index(Request $request)
    {
        $usuario = $request->user();
        $empresa = $usuario->empresa;

        if (!$empresa) {
            abort(403, 'Solo los usuarios empresa pueden acceder a esta sección.');
        }

        $ofertas = $this->ofertaRepository->listarPorEmpresa(
            empresaId: $empresa->id,
            search: $request->input('search')
        );

        return Inertia::render('empresa/MisOfertas', [
            'empresa' => [
                'nombre' => $empresa->nombre,
            ],
            'ofertas' => $ofertas,
            'filters' => [
                'search' => $request->input('search'),
            ],
        ]);
    }

    /**
     * Muestra el formulario de creación de oferta.
     */
    public function create(Request $request)
    {
        $usuario = $request->user();

        return Inertia::render('empresa/NuevaOferta', [
            'empresa' => [
                'nombre' => $usuario->nombre,
            ],
        ]);
    }

    public function store(StoreOfertaRequest $request)
    {
        $validated = $request->validated();
        $usuario = Auth::user();
        $empresa = $usuario->empresa;

        if (!$empresa) {
            abort(403, 'No se encontró empresa asociada al usuario.');
        }

        try {
            $this->ofertaService->crearOferta($empresa, $validated, Carrera::generic);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()
                ->withErrors($e->errors())
                ->withInput();
        }

        return redirect()
            ->route('empresa.dashboard')
            ->with('success', 'Oferta creada correctamente y pendiente de aprobación.');
    }
}
