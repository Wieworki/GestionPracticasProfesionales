<?php

namespace App\Http\Controllers\Estudiante;

use App\Helper\PostulacionHelper;
use App\Http\Controllers\Controller;
use App\Models\Empresa;
use App\Models\Postulacion;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Services\OfertaService;
use App\Services\PostulacionService;
use App\Repositories\OfertaRepository;
use App\Repositories\PostulacionRepository;
use Illuminate\Support\Facades\Log;

class OfertaController extends Controller
{
    protected OfertaService $ofertaService;
    protected OfertaRepository $ofertaRepository;
    protected PostulacionRepository $postulacionRepository;
    protected PostulacionService $postulacionService;

    public function __construct(
        OfertaService $ofertaService,
        OfertaRepository $ofertaRepository,
        PostulacionRepository $postulacionRepository,
        PostulacionService $postulacionService
    )
    {
        $this->ofertaService = $ofertaService;
        $this->ofertaRepository = $ofertaRepository;
        $this->postulacionRepository = $postulacionRepository;
        $this->postulacionService = $postulacionService;
    }

    public function index(Request $request)
    {
        $usuario = $request->user();
        $search = $request->input('search');
        $empresaId = $request->input('empresaId');

        $ofertas = $this->ofertaRepository->getOfertasVisibles(
            search: $search,
            perPage: 10,
            empresaId: $empresaId
        );

        $nombreEmpresaFiltro = null;
        if ($empresaId) {
            $nombreEmpresaFiltro = Empresa::where('empresa.id', $empresaId)->first()->nombre;
        }

        return Inertia::render('estudiante/ofertas/ListadoOfertas', [
            'ofertas' => $ofertas,
            'filters' => [
                'search' => $search,
            ],
            'nombre' => $usuario->nombre,
            'nombreEmpresaFiltro' => $nombreEmpresaFiltro
        ]);
    }

    public function show(Request $request, int $id)
    {
        $usuario = $request->user();
        $estudianteId = $usuario->estudiante->id;

        $oferta = $this->ofertaService->getVisibleOfertaForEstudiante($id);
        $canPostularse = $this->ofertaService->canEstudiantePostularse($oferta, $estudianteId);
        /** @var Postulacion $postulacion */
        $postulacion = $this->postulacionRepository->getPostulacion($id, $estudianteId);

        return Inertia::render('estudiante/ofertas/ShowOferta', [
            'oferta' => [
                'id' => $oferta->id,
                'titulo' => $oferta->titulo,
                'descripcion' => $oferta->descripcion,
                'fecha_cierre' => $oferta->fecha_cierre->format('d/m/Y'),
                'modalidad' => $oferta->modalidad,
                'estado' => $oferta->estado,
                'canPostularse' => $canPostularse
            ],
            'postulacion' => [
                'id' => $postulacion?->id,
                'existe' => $postulacion ? true : false,
                'fecha' => $postulacion?->fecha_creacion->format('d/m/Y'),
                'seleccionada' => $postulacion?->isSeleccionada(),
                'confirmada' => $postulacion?->isConfirmada(),
                'canAnular' => $postulacion?->canBeAnulada(),
                'mensajePostulacion' => PostulacionHelper::getMensajePostulacion($postulacion)
            ],
            'nombre' => $usuario->nombre
        ]);
    }

    public function postular(Request $request, int $id)
    {
        $usuario = $request->user();
        $estudianteId = $usuario->estudiante->id;

        $oferta = $this->ofertaService->getVisibleOfertaForEstudiante($id);
        if (!$this->ofertaService->canEstudiantePostularse($oferta, $estudianteId)) {
            abort(403, 'No puede postularse a esta oferta.');
        }
        $this->postulacionService->nuevaPostulacion($oferta, $estudianteId);

        return redirect()->route('estudiante.oferta.show', $oferta->id)
            ->with('success', 'Postulacion exitosa.');
    }
}
