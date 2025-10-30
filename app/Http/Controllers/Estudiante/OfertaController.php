<?php

namespace App\Http\Controllers\Estudiante;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Services\OfertaService;
use App\Repositories\OfertaRepository;
use App\Repositories\PostulacionRepository;
use Illuminate\Support\Facades\Log;

class OfertaController extends Controller
{
    protected OfertaService $ofertaService;
    protected OfertaRepository $ofertaRepository;
    protected PostulacionRepository $postulacionRepository;

    public function __construct(
        OfertaService $ofertaService,
        OfertaRepository $ofertaRepository,
        PostulacionRepository $postulacionRepository
    )
    {
        $this->ofertaService = $ofertaService;
        $this->ofertaRepository = $ofertaRepository;
        $this->postulacionRepository = $postulacionRepository;
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
        $postulacion = $this->postulacionRepository->getPostulacion($id, $estudianteId);
        $fechaPostulacion = null;
        if ($postulacion) {
            $fechaPostulacion = $postulacion->fecha_creacion;
        }

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
            'postulacion' => $fechaPostulacion,
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
        $this->ofertaService->nuevaPostulacion($oferta, $estudianteId);

        return redirect()->route('estudiante.oferta.show', $oferta->id)
            ->with('success', 'Postulacion exitosa.');
    }
}
