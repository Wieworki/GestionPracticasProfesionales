<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use App\Repositories\PostulacionRepository;
use App\Services\PostulacionService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Oferta;
use App\Models\Postulacion;
use Illuminate\Support\Facades\Log;

class PostulacionController extends Controller
{

    protected PostulacionRepository $postulacionRepository;
    protected PostulacionService $postulacionService;

    public function __construct(
        PostulacionRepository $postulacionRepository,
        PostulacionService $postulacionService
    ) {
        $this->postulacionRepository = $postulacionRepository;
        $this->postulacionService = $postulacionService;
    }

    public function index(Request $request)
    {
        $usuario = $request->user();
        $empresa = $usuario->empresa;
        $filtro = $request->input('search');
        $ofertaId = $request->input('ofertaId');

        $oferta = Oferta::where('oferta.id', $ofertaId)->first();
        if (!$oferta) {
            abort(403, 'La oferta no existe.');
        }
        if ($oferta->empresa->id != $empresa->id) {
            abort(403, 'No puede ver los postulantes de esta oferta.');
        }

        $postulaciones = $this->postulacionRepository->getPostulacionesOferta(
            search: $filtro,
            perPage: 10,
            ofertaId: $ofertaId,
        );

        return Inertia::render('empresa/postulaciones/PostulacionesOferta', [
            'nombre' => $empresa->nombre,
            'postulaciones' => $postulaciones,
            'ofertaId' => $ofertaId,
            'tituloOferta' => $oferta->titulo,
            'filters' => [
                'search' => $request->input('search'),
            ],
        ]);
    }

    public function show(Request $request)
    {
        $empresa = $request->user()->empresa;
        $postulacionId = $request->input('postulacionId');
        $postulacion = Postulacion::where('postulacion.id', $postulacionId)->first();
        if (!$postulacion) {
            abort(403, 'La postulacion no existe.');
        }

        $empresaOferta = $postulacion->oferta->empresa;

        if ($empresaOferta->id != $empresa->id) {
            abort(403, 'No puede ver los postulantes de esta oferta.');
        }

        $facultad = "FICH - UNL";
        $canBeSelected = $postulacion->canBeSelected();
        return Inertia::render('empresa/postulaciones/ShowPostulacion', [
            'postulacion' => [
                'id' => $postulacion->id,
                'titulo' => $postulacion->oferta->titulo,
                'estado' => $postulacion->estado,
                'estudiante' => $postulacion->estudiante->nombre,
                'email_contacto' => $postulacion->estudiante->email,
                'fecha_creacion' => $postulacion->fecha_creacion->format('d/m/Y'),
                'facultad_estudiante' => $facultad,
                'canBeSelected' => $canBeSelected
            ],
            'nombre' => $empresa->nombre
        ]);
    }


    public function seleccionarPostulante(Request $request)
    {
        $empresa = $request->user()->empresa;
        $postulacionId = $request->input('postulacionId');
        $postulacion = Postulacion::where('postulacion.id', $postulacionId)->first();

        if (!$postulacion) {
            abort(403, 'La postulacion no existe.');
        }

        $empresaOferta = $postulacion->oferta->empresa;

        if ($empresaOferta->id != $empresa->id) {
            abort(403, 'No puede elegir un postulante para esta oferta.');
        }

        $this->postulacionService->seleccionarPostulacion($postulacionId);

        return redirect()->route('empresa.ofertas.postulantes', ['ofertaId' => $postulacion->oferta->id])
            ->with('success', 'Postulacion seleccionada correctamente.');
    }
}
