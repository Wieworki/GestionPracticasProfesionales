<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use App\Services\OfertaService;
use App\Repositories\OfertaRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Http\Requests\Empresa\StoreOfertaRequest;
use App\Http\Requests\Empresa\UpdateOfertaRequest;
use App\Models\Carrera;
use App\Models\Oferta;

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

        return Inertia::render('empresa/ofertas/MisOfertas', [
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

        return Inertia::render('empresa/ofertas/NuevaOferta', [
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

    public function show(Request $request, $id)
    {
        $oferta = Oferta::findOrFail($id);

        // Autorización: solo la empresa propietaria puede ver la oferta
        $empresa = auth()->user()->empresa;
        if ($oferta->empresa_id !== $empresa->id) {
            abort(403, 'No tiene permiso para acceder a esta oferta.');
        }

        return Inertia::render('empresa/ofertas/ShowOferta', [
            'oferta' => [
                'id' => $oferta->id,
                'titulo' => $oferta->titulo,
                'descripcion' => $oferta->descripcion,
                'fecha_cierre' => $oferta->fecha_cierre->format('d/m/Y'),
                'modalidad' => $oferta->modalidad,
                'estado' => $oferta->estado,
            ],
            'empresa' => [
                'nombre' => $empresa->usuario->nombre,
            ],
        ]);
    }

    public function edit(Request $request, $id)
    {
        $oferta = Oferta::findOrFail($id);
        $empresa = Auth::user()->empresa;

        if ($oferta->empresa_id !== $empresa->id) {
            abort(403);
        }

        if ($oferta->estado === 'Finalizada') {
            abort(403, 'No se puede editar una oferta finalizada.');
        }

        return inertia('empresa/ofertas/EditOferta', [
            'empresa' => $empresa,
            'oferta' => [
                'id' => $oferta->id,
                'titulo' => $oferta->titulo,
                'descripcion' => $oferta->descripcion,
                'fecha_cierre' => $oferta->fecha_cierre->format('Y-m-d'),
                'modalidad' => $oferta->modalidad,
                'estado' => $oferta->estado,
            ],
        ]);
    }

    public function update(UpdateOfertaRequest $request, $id)
    {
        $oferta = Oferta::findOrFail($id);
        $empresa = Auth::user()->empresa;

        if ($oferta->empresa_id !== $empresa->id || $oferta->estado === 'Finalizada') {
            abort(403);
        }

        $oferta->update($request->validated());

        return redirect()->route('empresa.ofertas.show', $oferta->id)
                         ->with('success', 'Oferta actualizada correctamente.');
    }
}
