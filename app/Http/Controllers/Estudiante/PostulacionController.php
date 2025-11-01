<?php

namespace App\Http\Controllers\Estudiante;

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
        $estudiante = $usuario->estudiante;
        $filtro = $request->input('search');

        $postulaciones = $this->postulacionRepository->getPostulacionesEstudiante(
            search: $filtro,
            perPage: 10,
            estudianteId: $estudiante->id
        );

        return Inertia::render('estudiante/postulaciones/Index', [
            'nombre' => $estudiante->nombre,
            'postulaciones' => $postulaciones,
            'filters' => [
                'search' => $request->input('search'),
            ],
        ]);
    }

    public function confirmar(Request $request)
    {
        $usuario = $request->user();
        $postulacionId = $request->input('postulacionId');

        $postulacion = Postulacion::where('postulacion.id', $postulacionId)->first();
        if (!$postulacion) {
            abort(403, 'La postulacion no existe.');
        }

        $estudiantePostulacion = $postulacion->estudiante;
        if ($estudiantePostulacion->id != $usuario->estudiante->id) {
            abort(403, 'No puede confirmar esta postulacion.');
        }

        if (!$postulacion->isSeleccionada()) {
            abort(403, 'No puede confirmar esta postulacion.');
        }

        $this->postulacionService->confirmarPostulacion($postulacionId);

        return redirect()->route('estudiante.oferta.show', $postulacion->oferta->id)
            ->with('success', 'Postulacion exitosa.');
    }
}
