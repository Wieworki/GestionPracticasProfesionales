<?php

namespace App\Http\Controllers\Administrativo;

use App\Http\Controllers\Controller;
use App\Services\OfertaService;
use App\Repositories\OfertaRepository;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;
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
        $search = $request->input('search');
        $empresaId = $request->input('empresaId');

        $ofertas = $this->ofertaRepository->getAll(
            search: $search,
            perPage: 10,
            empresaId: $empresaId
        );

        return Inertia::render('administrativo/ofertas/ListadoOfertas', [
            'ofertas' => $ofertas,
            'filters' => [
                'search' => $search,
            ],
            'nombre' => $usuario->nombre,
        ]);
    }

    public function show(Request $request, $id)
    {
        $usuario = $request->user();
        /** @var Oferta $oferta */
        $oferta = Oferta::findOrFail($id);

        return Inertia::render('administrativo/ofertas/ShowOferta', [
            'oferta' => [
                'id' => $oferta->id,
                'titulo' => $oferta->titulo,
                'descripcion' => $oferta->descripcion,
                'fecha_creacion' => $oferta->created_at->format('d/m/Y'),
                'fecha_cierre' => $oferta->fecha_cierre->format('d/m/Y'),
                'modalidad' => $oferta->modalidad,
                'estado' => $oferta->estado,
                'canBeVisible' => $oferta->isPendiente(),
                'empresa' => $oferta->empresa->usuario->nombre,
            ],
            'nombre' => $usuario->nombre,
        ]);
    }

    public function confirmarVisibilidad($ofertaId)
    {
        $oferta = Oferta::findOrFail($ofertaId);

        if (!$oferta->isPendiente()) {
            abort(403, 'No se puede confirmar visibilidad de esta oferta');
        }

        $oferta->update(['estado' => Oferta::ESTADO_ACTIVA]);

        return redirect()
            ->route('administrativo.ofertas.index')
            ->with('success', 'La oferta fue marcada como visible.');
    }
}
