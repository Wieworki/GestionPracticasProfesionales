<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use App\Repositories\PostulacionRepository;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Oferta;
use Illuminate\Support\Facades\Log;

class PostulacionController extends Controller
{

    protected PostulacionRepository $postulacionRepository;

    public function __construct(PostulacionRepository $postulacionRepository)
    {
        $this->postulacionRepository = $postulacionRepository;
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

}
